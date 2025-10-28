<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
const props = defineProps({
    isDrawerOpen: {
        type: Boolean,
        required: true,
    },
    charityWorkDetail: Object,
    errors: {
        required: true,
    },
    charityWorkId: {
        required: true,
    }
});

const emit = defineEmits([
    'update:isDrawerOpen',
    'charityWorkDetailData',
]);

const isFormValid = ref(false);
const refForm = ref();
const selectedFile = ref(null);
const selectedImage = ref(null);

const form = ref({
    id: 0,
    title: '',
    description: '',
    image_path: '',
});

watch(() => props.charityWorkDetail, newCharityWorks => {
    if (newCharityWorks) {
        form.value = { ...newCharityWorks };
    }
}, { immediate: true });

const handleImageChange = (event) => {
    selectedImage.value = event.target.files[0]; // Get the first file
};

// Close the drawer and reset form
const closeNavigationDrawer = () => {
    emit('update:isDrawerOpen', false);
    nextTick(() => {
        refForm.value?.reset();
        selectedFile.value = null;
        refForm.value?.resetValidation();
    });
};

// Submit the form
const onSubmit = () => {
    refForm.value?.validate().then(({ valid }) => {
        if (valid) {
            const formData = new FormData();
            if (selectedImage.value) formData.append("image", selectedImage.value);
            formData.append("title", form.value.title);
            formData.append("description", form.value.content);
        
            emit('charityWorkDetailData', formData);

            if (Object.values(props.errors).length) {
                closeNavigationDrawer();
                emit('update:isDrawerOpen', false);
                nextTick(() => {
                    refForm.value?.reset()
                    refForm.value?.resetValidation()
                })
            }

        }
    });
};

// Close the drawer when the model updates
const handleDrawerModelValueUpdate = val => {
    emit('update:isDrawerOpen', val);
};
</script>

<template>
    <VNavigationDrawer temporary :width="400" location="end" class="scrollable-content"
        :model-value="props.isDrawerOpen" @update:model-value="handleDrawerModelValueUpdate">
        <AppDrawerHeaderSection title="القصص" @cancel="closeNavigationDrawer" />

        <VDivider />

        <PerfectScrollbar :options="{ wheelPropagation: false }">
            <VCard flat>
                <VCardItem class="pb-4" v-if="Object.values(errors).length">
                    <VAlert color="error" variant="tonal" class="mb-4">
                        {{Object.values(errors).flatMap(errorObj => Object.values(errorObj).flat()).join("\n")}}
                    </VAlert>
                </VCardItem>

                <VCardText>
                    <!-- 👉 Form -->
                    <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
                        <VRow>
                            <!-- 👉 Title -->
                            <VCol cols="12">
                                <AppTextField v-model="form.title" :rules="[requiredValidator]" label="العنوان"
                                    placeholder="أدخل العنوان" />
                            </VCol>
                            <VCol cols="12">
                                <AppTextarea v-model="form.content" :rules="[requiredValidator]" label="الوصف"
                                    placeholder="أدخل الوصف" />
                            </VCol>

                            <!-- 👉 Image Path -->
                        
                            <VCol cols="12">
                                <VFileInput label="تحميل الصورة" @change="handleImageChange" />
                            </VCol>

                            <!-- 👉 Form Actions -->
                            <VCol cols="12">
                                <VBtn type="submit" class="me-3">حفظ</VBtn>
                                <VBtn type="reset" variant="tonal" color="error" @click="closeNavigationDrawer">إلغاء
                                </VBtn>
                            </VCol>
                        </VRow>
                    </VForm>
                </VCardText>
            </VCard>
        </PerfectScrollbar>
    </VNavigationDrawer>
</template>
