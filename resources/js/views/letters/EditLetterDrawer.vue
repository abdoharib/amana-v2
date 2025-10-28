<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  letter: Object, // The letter being edited
  errors: {
    required: true,
  },
});

const emit = defineEmits([
  'update:isDrawerOpen',
  'letterFormData',
]);

const isFormValid = ref(false);
const refForm = ref();
const selectedImage = ref(null);
const selectedAudio = ref(null);
const selectedWordAudio = ref(null);

const form = ref({
  id: 0,
  letter: '',
  word: '',
});

// Populate form when editing a letter
watch(() => props.letter, newLetter => {

    
  if (newLetter) {    
    form.value = { ...newLetter };
  }
}, { immediate: true });

const handleImageChange = (event) => {
  selectedImage.value = event.target.files[0];
};

const handleAudioChange = (event) => {
  selectedAudio.value = event.target.files[0];
};
const handleWordAudioChange = (event) => {
  selectedWordAudio.value = event.target.files[0];
};

// Close the drawer and reset form
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false);
  nextTick(() => {
    refForm.value?.reset();
    selectedImage.value = null;
    selectedAudio.value = null;
    selectedWordAudio.value = null;
    refForm.value?.resetValidation();
  });
};

// Submit the form
const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      const formData = new FormData();
      if (selectedImage.value) formData.append("image", selectedImage.value);
      if (selectedAudio.value) formData.append("audio", selectedAudio.value);
      if (selectedWordAudio.value) formData.append("word_audio", selectedWordAudio.value);
      formData.append("letter", form.value.letter);
      formData.append("word", form.value.word);

      emit('letterData', formData);

      if (Object.values(props.errors).length) {
        closeNavigationDrawer();
        nextTick(() => {
          refForm.value?.reset();
          refForm.value?.resetValidation();
        });
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
  <VNavigationDrawer
    temporary
    :width="400"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <AppDrawerHeaderSection title="تعديل الحرف" @cancel="closeNavigationDrawer" />

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
              <!-- 👉 Letter -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.letter"
                  :rules="[requiredValidator]"
                  label="الحرف"
                  placeholder="أدخل الحرف"
                  type="letter"
                />
              </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="form.word"
                  :rules="[requiredValidator]"
                  label="كلمة"
                  placeholder="كلمات"
                  type="letter"
                />
              </VCol>

              <!-- 👉 Image Upload -->
              <VCol cols="12">
                <VFileInput label="صوت الكلمة" @change="handleWordAudioChange" />
              </VCol>
              <!-- 👉 Image Upload -->
              <VCol cols="12">
                <VFileInput label="تحميل صورة الحرف" @change="handleImageChange" />
              </VCol>

              <!-- 👉 Audio Upload -->
              <VCol cols="12">
                <VFileInput label="تحميل صوت الحرف" @change="handleAudioChange" />
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
