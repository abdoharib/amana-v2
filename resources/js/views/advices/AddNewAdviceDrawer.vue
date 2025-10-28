<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { ref, watch, nextTick, defineProps, defineEmits, computed } from 'vue'

const props = defineProps({
    isDrawerOpen: {
        type: Boolean,
        required: true,
    },
    advice: Object,
    guardians: Array,
    errors: {
        required: true,
        type: Object
    }
})

const emit = defineEmits([
    'update:isDrawerOpen',
    'adviceData',
])

const isFormValid = ref(false)
const refForm = ref()

const form = ref({
    id: 0,
    title: '',
    description: '',
    image: '',
    guardian_id: '',
    kid_id: '',
})

const filteredKids = ref([])

// Watch for guardian selection and fetch related kids
watch(
    () => form.value.guardian_id,
    async (newGuardianId) => {
        if (newGuardianId) {
            const { data } = await useApi(createUrl(`/kids/all`, {
                query: { 'exact[guardian_id]': newGuardianId }
            }))
            filteredKids.value = data.value || []
        } else {
            filteredKids.value = []
        }
    }
)

// Populate form when editing an existing advice
watch(
    () => props.advice,
    newAdvice => {
        if (newAdvice) {
            form.value = { ...newAdvice }
        } else {
            form.value = {
                title: '',
                description: '',
                image: '',
                guardian_id: '',
                kid_id: '',
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
            emit('adviceData', form.value)
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
        <AppDrawerHeaderSection title="إضافة/تعديل نصيحة" @cancel="closeNavigationDrawer" />

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

                            <!-- 👉 Description -->
                            <VCol cols="12">
                                <AppTextarea v-model="form.description" :rules="[requiredValidator]" label="الوصف"
                                    placeholder="أدخل الوصف" />
                            </VCol>

                            <!-- 👉 Image -->
                            <VCol cols="12">
                                <AppTextField v-model="form.image" type="text" label="رابط الصورة"
                                    placeholder="أدخل رابط الصورة" />
                            </VCol>

                            <!-- 👉 Guardian ID -->
                            <VCol cols="12">
                                <AppSelect v-model="form.guardian_id" label="معرف الأب" :items="guardians"
                                    item-title="name" item-value="id" required />
                            </VCol>

                            <!-- 👉 Kid ID -->
                            <VCol cols="12">
                                <AppSelect v-model="form.kid_id" label="معرف الطفل" :items="filteredKids"
                                    item-title="first_name" item-value="id" required />
                            </VCol>

                            <!-- 👉 Form Actions -->
                            <VCol cols="12">
                                <VBtn type="submit" class="me-3">إرسال</VBtn>
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
