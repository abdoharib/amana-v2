<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  user: Object,
  errors: {
    required: true
  }
})

const emit = defineEmits([
  'update:isDrawerOpen',
  'userData',
])

const isFormValid = ref(false)
const refForm = ref()



const form = ref({
  id:0,
  name: '',
  email: '',
  phone_number: '',
  is_activated: 1,
  image: '',
})


watch(
  () => props.user,
  newUser => {
    if (newUser) {
      form.value = { ...newUser } // Populate form with user data when editing
    } else {
      form.value = { name: '', email: '', phone_number: '', is_activated: 1 }
    }
  },
  { immediate: true }
)

// 👉 إغلاق النافذة الجانبية
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      emit('userData', form.value)

      if (Object.values(props.errors).length) {
        emit('update:isDrawerOpen', false)
        nextTick(() => {
          refForm.value?.reset()
          refForm.value?.resetValidation()
        })
      }
    }
  })
}

const handleDrawerModelValueUpdate = val => {
  emit('update:isDrawerOpen', val)
}
</script>

<template>
  <VNavigationDrawer temporary :width="400" location="end" class="scrollable-content" :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate">
    <!-- 👉 العنوان -->
    <AppDrawerHeaderSection title="إضافة مستخدم جديد" @cancel="closeNavigationDrawer" />

    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardItem class="pb-4" v-if="Object.values(errors).length">
          <VAlert color="error" variant="tonal" class="mb-4">
            {{ Object.values(errors).flatMap(errorObj => Object.values(errorObj).flat()).join("\n") }}
          </VAlert>
        </VCardItem>
        <VCardText>
          <!-- 👉 النموذج -->
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <!-- 👉 الاسم -->
              <VCol cols="12">
                <AppTextField v-model="form.name" :rules="[requiredValidator]" label="الاسم"
                  placeholder="أدخل الاسم الكامل" />
              </VCol>

              <!-- 👉 رقم الهاتف -->
              <VCol cols="12">
                <AppTextField v-model="form.phone_number" type="number" :rules="[requiredValidator]" label="رقم الهاتف"
                  placeholder="09XXXXXXXX" dir="ltr" />
              </VCol>

              <!-- 👉 البريد الإلكتروني -->
              <VCol cols="12">
                <AppTextField v-model="form.email" :rules="[requiredValidator, emailValidator]" label="البريد الإلكتروني"
                  placeholder="example@email.com" class="text-right" />
              </VCol>

              <!-- 👉 الصورة -->
              <VCol cols="12">
                <VFileInput label="صورة" v-model="form.image" />
              </VCol>

              <!-- 👉 الحالة -->
              <VCol cols="12">
                <AppSelect v-model="form.is_activated" label="الحالة"
                  :items="[{ title: 'نشط', value: 1 }, { title: 'غير نشط', value: 0 }]" />
              </VCol>

              <!-- 👉 الأزرار -->
              <VCol cols="12">
                <VBtn type="submit" class="me-3">إرسال</VBtn>
                <VBtn type="reset" variant="tonal" color="error" @click="closeNavigationDrawer">إلغاء</VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
