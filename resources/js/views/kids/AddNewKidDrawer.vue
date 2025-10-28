<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { ref, watch, nextTick, defineProps, defineEmits } from 'vue'

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  user: Object, 
  guardians: Array, 
  errors: {
    required: true,
    type:Object
  }
})

const emit = defineEmits([
  'update:isDrawerOpen',
  'userData',
])

const isFormValid = ref(false)
const refForm = ref()

const form = ref({
  id: 0,
  first_name: '',
  last_name: '',
  date_of_birth: '',
  sex: '',
  weight: '',
  height: '',
  guardian_id: '',
})

// Populate form when editing an existing kid
watch(
  () => props.user,
  newUser => {
    if (newUser) {
      form.value = { ...newUser } // Prefill with kid's data when editing
    } else {
      // Reset form for a new entry
      form.value = {
        first_name: '',
        last_name: '',
        date_of_birth: '',
        sex: '',
        weight: '',
        height: '',
        guardian_id: '',
      }
    }
  },
  { immediate: true }
)

// 👉 Close the drawer and reset the form
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}




// 👉 Handle form submission
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
  <VNavigationDrawer temporary :width="400" location="end" class="scrollable-content"
    :model-value="props.isDrawerOpen" @update:model-value="handleDrawerModelValueUpdate">
    
    <!-- 👉 Title -->
    <AppDrawerHeaderSection title="إضافة/تعديل طفل" @cancel="closeNavigationDrawer" />

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
              <!-- 👉 First Name -->
              <VCol cols="12">
                <AppTextField v-model="form.first_name" :rules="[requiredValidator]" label="الإسم الأول"
                  placeholder="أدخل الإسم الأول" />
              </VCol>

              <!-- 👉 Last Name -->
              <VCol cols="12">
                <AppTextField v-model="form.last_name" :rules="[requiredValidator]" label="الإسم الأخير"
                  placeholder="أدخل الإسم الأخير" />
              </VCol>

              <!-- 👉 Date of Birth -->
              <VCol cols="12">
                <AppTextField v-model="form.date_of_birth" type="date" :rules="[requiredValidator]"
                  label="تاريخ الميلاد" placeholder="YYYY-MM-DD" />
              </VCol>

              <!-- 👉 Gender -->
              <VCol cols="12">
                <AppSelect v-model="form.sex" label="الجنس"
                  :items="[{ title: 'ذكر', value: 'male' }, { title: 'أنثى', value: 'female' }]" />
              </VCol>

              <!-- 👉 Weight -->
              <VCol cols="12">
                <AppTextField v-model="form.weight" type="number" label="الوزن (كغ)"
                  placeholder="أدخل الوزن" />
              </VCol>

              <!-- 👉 Height -->
              <VCol cols="12">
                <AppTextField v-model="form.height" type="number" label="الطول (سم)"
                  placeholder="أدخل الطول" />
              </VCol>

              <!-- 👉 Guardian ID -->
              <VCol cols="12">
              <!-- Guardian Select Dropdown -->
              <AppSelect v-model="form.guardian_id" label="معرف الأب" :items="guardians" item-title="name" item-value="id" required />
            </VCol>

              <!-- 👉 Form Actions -->
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
