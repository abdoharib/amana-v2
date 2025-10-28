<script setup>
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const imageFile = ref(null);
const isFormValid = ref(false);
const refForm = ref();
const deletedIds = ref([]);
const id = route.params.id;


const errors = ref({});
const errorMessage = ref();
const isSnackbarVisible = ref(false);
const Message = ref('');
const color = ref('error');
const isLoading = ref(false);

const item = ref({
    id: null,
    title: '',
    image_path: '',
    category_id: '',
    educational_content_id: ''
});

const { data: EducationContentData } = await useApi(createUrl('/admin/educational-contents', {
    query: { 'exact[education_stage_id]': 3 }
}));
const educationContent = computed(() => EducationContentData.value || []);

const { data: categoryData } = await useApi(createUrl('/admin/categories'));
const categories = computed(() => categoryData.value || []);

const itemDetails = ref([]);

const { data: itemData } = await useApi(createUrl(`/items/${id}`));
item.value = itemData.value.data;
itemDetails.value = itemData.value.data.details;



const addItemDetail = () => {
    itemDetails.value.push({ id: 0, title: '', description: '', audio: '', image: '' });
};

const removeItemDetail = (index, id) => {
    itemDetails.value.splice(index, 1);
    deletedIds.value.push(id)
};
const submitForm = async () => {
    refForm.value?.validate().then(async ({ valid }) => {
        if (valid && item.value.id) {
            try {
                const formData = new FormData();
                formData.append('title', item.value.title);
                if (imageFile.value) {
                    formData.append('image', imageFile.value);
                }
                formData.append('category_id', item.value.category_id);

                formData.append('educational_content_id', item.value.educational_content_id);

                formData.append('deletedIds', deletedIds.value);

                itemDetails.value.forEach((detail, index) => {
                    formData.append(`details[${index}][id]`, detail.id);
                    formData.append(`details[${index}][title]`, detail.title);
                    formData.append(`details[${index}][description]`, detail.description);
                    if (detail.audio_path) {
                        formData.append(`details[${index}][audio]`, detail.audio_path);
                    }
                    if (detail.image_path) {
                        formData.append(`details[${index}][image]`, detail.image_path);
                    }
                });
                formData.append('_method', 'put');
                await $api(`/admin/items/${item.value.id}`, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                });
                isSnackbarVisible.value = true;
                Message.value = 'تم إضافة العنصر بنجاح';
                color.value = 'success';
                isLoading.value = false;
                router.go(-1)
            } catch (error) {
                isSnackbarVisible.value = true;
                Message.value = error.response._data.message;
                color.value = 'error';
                isLoading.value = false;
                errorMessage.value = error.response._data.message
                errors.value = error.response._data.errors
            }
        }
    });
};

const handleFileChange = (event) => {
    imageFile.value = event.target.files[0];
};

const uploadDetailImage = (event, index) => {
    itemDetails.value[index].image_path = event.target.files[0];
};

const uploadAudio = (event, index) => {
    itemDetails.value[index].audio_path = event.target.files[0];
};
</script>

<template>
    <snackbar v-model:visible="isSnackbarVisible" :message="Message" :color="color" />
    <VCard>
        <VCardTitle>تحديث العنصر</VCardTitle>

        <VCardItem class="pb-4" v-if="errorMessage">
            <VAlert color="error" variant="tonal" class="mb-4">
                {{ errorMessage }}
            </VAlert>
        </VCardItem>
        <VCardText>
            <VForm ref="refForm" v-model="isFormValid" @submit.prevent="submitForm">
                <VRow>
                    <VCol cols="12" md="6">
                        <VTextField v-model="item.title" label="عنوان العنصر" :rules="[requiredValidator]" />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VFileInput label="تحميل صورة جديدة" @change="handleFileChange" />
                    </VCol>
                    <VCol cols="12" md="6">
                        <AppSelect v-model="item.category_id" label="التصنيف" :items="categories" item-title="title"
                            item-value="id" :rules="[requiredValidator]" />
                    </VCol>
                    <VCol cols="12" md="6">
                        <AppSelect v-model="item.educational_content_id" label="المحتوى" :items="educationContent"
                            item-title="title" item-value="id" :rules="[requiredValidator]" />
                    </VCol>
                </VRow>

                <VCardTitle class="mt-4">تفاصيل العنصر</VCardTitle>
                <VRow v-for="(detail, index) in itemDetails" :key="index" class="mt-2">
                    <VCol cols="12" md="6">
                        <VTextField v-model="detail.title" label="عنوان التفاصيل" :rules="[requiredValidator]" />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextField v-model="detail.description" label="الوصف" :rules="[requiredValidator]" />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VFileInput label="تحميل صوت جديد" @change="(e) => uploadAudio(e, index)" />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VFileInput label="تحميل صورة جديدة" @change="(e) => uploadDetailImage(e, index)" />
                    </VCol>
                    <VCol cols="12" class="text-right">
                        <VBtn color="error" @click="removeItemDetail(index, detail.id)" v-if="itemDetails.length > 1">
                            إزالة
                        </VBtn>
                    </VCol>
                </VRow>
                <VBtn color="primary" class="ml-2 mt-3" @click="addItemDetail">إضافة تفاصيل جديدة</VBtn>
                <VBtn type="submit" color="success" class="mt-3 ml-2">تحديث العنصر</VBtn>
            </VForm>
        </VCardText>
    </VCard>
</template>
