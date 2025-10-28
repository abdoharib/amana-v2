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


const item = ref({
    id: null,
    title: '',
    image_path: '',
});

const itemDetails = ref([]);

const { data: itemData } = await useApi(createUrl(`/prophet-stories/${id}`));
item.value = itemData.value.data;
itemDetails.value = itemData.value.data.details;



const addItemDetail = () => {
    itemDetails.value.push({ id: 0, title: '', content: '', audio: '', image: '' });
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
                formData.append('deletedIds', deletedIds.value);

                itemDetails.value.forEach((detail, index) => {
                    formData.append(`details[${index}][id]`, detail.id);
                    formData.append(`details[${index}][title]`, detail.title);
                    formData.append(`details[${index}][content]`, detail.content);
                    if (detail.audio) {
                        formData.append(`details[${index}][audio]`, detail.audio);
                    }
                    
                    if (detail.image) {
                        formData.append(`details[${index}][image]`, detail.image);
                    }
                });
                formData.append('_method', 'put');
                await $api(`/admin/prophet-stories/${item.value.id}`, {
                    method: 'POST',
                    body: formData,
                });

                // router.go(-1)

            } catch (error) {
                console.log(error);
                
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
    itemDetails.value[index].image = event.target.files[0];
};

const uploadAudio = (event, index) => {
    itemDetails.value[index].audio = event.target.files[0];
};
</script>

<template>
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
                </VRow>

                <VCardTitle class="mt-4">تفاصيل العنصر</VCardTitle>
                <VRow v-for="(detail, index) in itemDetails" :key="index" class="mt-2">
                    <VCol cols="12" md="12">
                        <VTextField v-model="detail.title" label="عنوان الفصل" :rules="[requiredValidator]" />
                    </VCol>

                    <VCol cols="12" md="6">
                        <VFileInput label="تحميل الصوت" @change="uploadAudio($event, index)" />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VFileInput label="تحميل صورة" @change="uploadDetailImage($event, index)" />
                    </VCol>
                    <VCol cols="12" md="12">
                        <VTextarea v-model="detail.content" label="الوصف" :rules="[requiredValidator]" />
                    </VCol>
                    <VCol cols="12" class="text-right">
                        <VBtn color="error" @click="removeItemDetail(index,detail.id)" v-if="itemDetails.length > 1">
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
