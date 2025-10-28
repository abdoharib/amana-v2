<script setup>
import { ref, watch, defineProps, defineEmits, nextTick } from 'vue';
import { PerfectScrollbar } from 'vue3-perfect-scrollbar';

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  content: Object, // Education content being edited
  errors: {
    required: true,
  }
});

const emit = defineEmits([
  'update:isDrawerOpen',
  'contentData',
]);

const isFormValid = ref(false);
const refForm = ref();
const selectedFile = ref(null);

const form = ref({
  id: 0,
  title: '',
  image_path: '',
  type: '',
  description: '',
  education_stage_id: '',
});

// Populate form when editing a content
watch(() => props.content, newContent => {
  if (newContent) {
    form.value = { ...newContent };
  }
}, { immediate: true });

const handleFileChange = (event) => {
  selectedFile.value = event.target.files[0]; // Get the first file
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
      formData.append("image", selectedFile.value);
      formData.append("title", form.value.title);
      formData.append("description", form.value.description);
      formData.append("image_path", form.value.image_path);
      formData.append("type", form.value.type);
      formData.append("education_stage_id", form.value.education_stage_id);
      formData.append("_method", 'put');
      emit('contentData', formData);
      closeNavigationDrawer()
      emit('update:isDrawerOpen', false);
    }
  });
};

// Close the drawer when the model updates
const handleDrawerModelValueUpdate = val => {
  emit('update:isDrawerOpen', val);
};
</script>

<template>
  <VNavigationDrawer temporary :width="400" location="end" class="scrollable-content" :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate">
    <AppDrawerHeaderSection title="تعديل محتوى التعليمي" @cancel="closeNavigationDrawer" />

    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardItem class="pb-4" v-if="Object.values(errors).length">
          <VAlert color="error" variant="tonal" class="mb-4">
            {{ Object.values(errors).flatMap(errorObj => Object.values(errorObj).flat()).join("\n") }}
          </VAlert>
        </VCardItem>

        <VCardText>
          <!-- 👉 Form -->
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <!-- 👉 Title -->
              <VCol cols="12">
                <AppTextField v-model="form.title" :rules="[requiredValidator]" label="العنوان"
                  placeholder="أدخل اسم المرحلة التعليمية" />
              </VCol>

              <!-- 👉 Description -->
              <VCol cols="12">
                <AppTextField v-model="form.description" label="الوصف" placeholder="أدخل وصف المرحلة التعليمية" />
              </VCol>
              <VCol cols="12">
                <AppTextField v-model="form.type" label="النوع" placeholder="النوع" />
              </VCol>

              <!-- 👉 Image Path -->
              <VCol cols="12">
                <VFileInput label="تحميل صورة" @change="handleFileChange" />
              </VCol>

              <!-- 👉 Form Actions -->
              <VCol cols="12">
                <VBtn type="submit" class="me-3">حفظ</VBtn>
                <VBtn type="reset" variant="tonal" color="error" @click="closeNavigationDrawer">إلغاء</VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
