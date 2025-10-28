<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
    isDrawerOpen: {
        type: Boolean,
        required: true,
    },
    post: Object,
    errors: {
        required: true
    },
    categories: {
        type: Array,
        required: true
    }
})

const emit = defineEmits([
    'update:isDrawerOpen',
    'postData',
])

const isFormValid = ref(false)
const refForm = ref()

const form = ref({
    id: 0,
    title: '',
    body: '',
    is_pinned: 0,
    is_published: 0,
    image: null,
    video: null,
    categories: []
})

watch(
    () => props.post,
    newPost => {
        if (newPost) {
            form.value = {
                id: newPost.id,
                title: newPost.title,
                body: newPost.body,
                is_pinned: newPost.is_pinned,
                is_published: newPost.is_published,
                image: null,
                video: null,
                categories: newPost.categories?.map(c => c.id) || []
            }
        } else {
            form.value = { id: 0, title: '', body: '', image: null, video: null, categories: [], is_published: 0, is_pinned: 0 }
        }
    },
    { immediate: true }
)

const closeNavigationDrawer = () => {
    
    emit('update:isDrawerOpen', false)
    nextTick(() => {
        refForm.value?.reset()
        refForm.value?.resetValidation()
    })
    form.value = { id: 0, title: '', body: '', image: null, video: null, categories: [], is_published: 0, is_pinned: 0 }

}

const onSubmit = () => {
    refForm.value?.validate().then(({ valid }) => {
        if (valid) {
            const postFormData = new FormData()
            postFormData.append('title', form.value.title)
            postFormData.append('body', form.value.body)
            postFormData.append('is_published', form.value.is_published)
            postFormData.append('is_pinned', form.value.is_pinned || 0)
             if (form.value.id) postFormData.append('_method', 'put')
            if (form.value.image) postFormData.append('image', form.value.image)
            if (form.value.video) postFormData.append('video', form.value.video)
            form.value.categories.forEach(cat => postFormData.append('categories[]', cat))

            emit('postData', postFormData)

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
const handleVideoChange = (event) => {
    form.value.video = event.target.files[0]; // Get the first file
};
const handleImageChange = (event) => {
    form.value.image = event.target.files[0]; // Get the first file
};
const handleDrawerModelValueUpdate = val => {
    emit('update:isDrawerOpen', val)
}
</script>

<template>
    <VNavigationDrawer temporary :width="400" location="end" class="scrollable-content"
        :model-value="props.isDrawerOpen" @update:model-value="handleDrawerModelValueUpdate">
        <AppDrawerHeaderSection title="إضافة منشور" @cancel="closeNavigationDrawer" />

        <VDivider />

        <PerfectScrollbar :options="{ wheelPropagation: false }">
            <VCard flat>
                <VCardItem class="pb-4" v-if="Object.values(errors).length">
                    
                </VCardItem>
                <VCardText>
                    <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
                        <VRow>
                            <!-- 👉 عنوان المنشور -->
                            <VCol cols="12">
                                <AppTextField v-model="form.title" label="عنوان المنشور"
                                    placeholder="أدخل عنوان المنشور" />
                            </VCol>

                            <!-- 👉 محتوى المنشور -->
                            <VCol cols="12">
                                <VTextarea v-model="form.body" label="المحتوى" placeholder="أدخل نص المنشور هنا"
                                    rows="4" auto-grow />
                            </VCol>

                            <!-- 👉 حالة النشر -->
                            <VCol cols="12">
                                <AppSelect v-model="form.is_published" :items="[
                                    { title: 'غير منشور', value: 0 },
                                    { title: 'منشور', value: 1 }
                                ]" label="حالة النشر" placeholder="اختر حالة النشر" />
                            </VCol>
                            <!-- 👉 تثبيت المنشور -->
                            <VCol cols="12">
                                <VSwitch v-model="form.is_pinned" label="تثبيت المنشور" :true-value="1"
                                    :false-value="0" />
                            </VCol>


                            <!-- 👉 صورة -->
                            <VCol cols="12">
                                <VFileInput label="صورة" @change="handleImageChange" />
                            </VCol>

                            <!-- 👉 فيديو -->
                            <VCol cols="12">
                                <VFileInput label="فيديو" @change="handleVideoChange" />
                            </VCol>

                            <!-- 👉 تصنيفات -->
                            <VCol cols="12">
                                <AppSelect v-model="form.categories" label="التصنيفات" multiple
                                    :items="categories.map(c => ({ title: c.name, value: c.id }))"
                                    placeholder="اختر تصنيفاً أو أكثر" />
                            </VCol>

                            <!-- 👉 الأزرار -->
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
