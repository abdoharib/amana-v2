<script setup>

const imageFile = ref();
const isFormValid = ref(false);
const refForm = ref();
const isSnackbarVisible = ref(false);
const Message = ref('');
const errors = ref({});
const color = ref('error');
const isLoading = ref(false);

const item = ref({
    title: '',
    image_path: '',
    category_id: '',
    educational_content_id: ''
});

const {
    data: EducationContentData
} = await useApi(createUrl(`/admin/educational-contents`, {
    query: {
        'exact[education_stage_id]': 3,
    }
}))

const educationContent = computed(() => EducationContentData.value)

const {
    data: categoryData
} = await useApi(createUrl(`/admin/categories`))

const categories = computed(() => categoryData.value)

const itemDetails = ref([
    { title: '', description: '', audio_path: '', image_path: '' }
]);

const addItemDetail = () => {
    itemDetails.value.push({ title: '', description: '', audio: '', image: '' });
};

const removeItemDetail = (index) => {
    itemDetails.value.splice(index, 1);
};

const submitForm = () => {
    isLoading.value = true;
    refForm.value?.validate().then(({ valid }) => {
        if (valid) {
            try {
                const formData = new FormData();
                formData.append('title', item.value.title);
                formData.append('image', imageFile.value);
                formData.append('category_id', item.value.category_id);
                formData.append('educational_content_id', item.value.educational_content_id);

                itemDetails.value.forEach((detail, index) => {
                    formData.append(`details[${index}][title]`, detail.title);
                    formData.append(`details[${index}][description]`, detail.description);
                    formData.append(`details[${index}][audio]`, detail.audio || '');
                    formData.append(`details[${index}][image]`, detail.image || '');
                });

                $api('/admin/items', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    Message.value = 'تم إضافة العنصر بنجاح';
                    color.value = 'success';
                    isSnackbarVisible.value = true;
                    isLoading.value = false;

                }).catch(error => {
                    errors.value = error.response._data.errors
                    Message.value = error.response._data.message;
                    color.value = 'error';
                    isSnackbarVisible.value = true;
                    isLoading.value = false;
                });
            }
            catch (err) {
                isSnackbarVisible.value = true;
                Message.value = error.response._data.message;
                color.value = 'error';
                isLoading.value = false;
                console.log(err);
            }
        }
    });
};

const handleFileChange = (event) => {
    imageFile.value = event.target.files[0]; // Get the first file
};

const uploadDetailImage = (event, index) => {
    itemDetails.value[index].image = event.target.files[0];
}
const uploadAudio = (event, index) => {
    itemDetails.value[index].audio = event.target.files[0];
}
</script>

<template>
    <snackbar v-model:visible="isSnackbarVisible" :message="Message" :color="color"  />
    <VCard>
        <VCardTitle>إضافة عنصر جديد للعلم والاكتشاف</VCardTitle>
        <VCardText>
            <VCardItem class="pb-4" v-if="Object.values(errors).length">
                <VAlert color="error" variant="tonal" class="mb-4">
                    {{Object.values(errors).flatMap(errorObj => Object.values(errorObj).flat()).join("\n")}}
                </VAlert>
            </VCardItem>
            <VForm ref="refForm" v-model="isFormValid" @submit.prevent="submitForm">
                <VRow>
                    <VCol cols="12" md="6">
                        <VTextField v-model="item.title" label="عنوان العنصر" :rules="[requiredValidator]" />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VFileInput label="تحميل صورة" @change="handleFileChange" />
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
                        <VFileInput label="تحميل الصوت" @change="uploadAudio($event, index)" />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VFileInput label="تحميل صورة" @change="uploadDetailImage($event, index)" />
                    </VCol>
                    <VCol cols="12" class="text-right">
                        <VBtn color="error" @click="removeItemDetail(index)" v-if="itemDetails.length > 1">
                            إزالة
                        </VBtn>
                    </VCol>
                </VRow>

                <VBtn  color="primary" class="ml-2 mt-3" @click="addItemDetail">إضافة تفاصيل جديدة</VBtn>
                <VBtn  :loading="isLoading" type="submit" color="success" class="mt-3 ml-2">حفظ العنصر</VBtn>
            </VForm>
        </VCardText>
    </VCard>
</template>
