<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  prayer: Object, // The prayer being edited
  errors: {
    required: true,
  }
});

const emit = defineEmits([
  'update:isDrawerOpen',
  'prayerData',
]);

const isFormValid = ref(false);
const refForm = ref();
const selectedFile = ref(null);

const form = ref({
  id: 0,
  title: '',
  image_path: '',
});

// Populate form when editing a prayer
watch(() => props.prayer, newPrayer => {
  if (newPrayer) {    
    form.value = { ...newPrayer };
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
      if(selectedFile.value) formData.append("image", selectedFile.value);
      formData.append("title", form.value.title);

      emit('prayerData', formData);

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
  <VNavigationDrawer temporary :width="400" location="end" class="scrollable-content" :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate">
    <AppDrawerHeaderSection title="تعديل الذكر" @cancel="closeNavigationDrawer" />

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
                  placeholder="أدخل اسم الذكر" />
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
