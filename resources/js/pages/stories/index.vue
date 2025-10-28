<script setup>
import EditStoryDrawer from '@/views/stories/EditStoryDrawer.vue'


const selectedStory = ref(null)
const route = useRoute('stories')

const errors = reactive({})
const errorMessage = ref()
const isEditStoryDrawerVisible = ref(false)
const {
    data: StoryData,
    execute: fetchStoryData,
} = await useApi(createUrl(`/admin/stories`, {
}))

const stories = computed(() => StoryData.value)
const storyId = ref()
const openEditDrawer = (storyDetails) => {
    selectedStory.value = storyDetails
    isEditStoryDrawerVisible.value = true
}

const addOrUpdateStory = async StoryDetailData => {
    try {
        if (selectedStory.value) {
            StoryDetailData.append('_method', 'put')
            await $api(`/admin/stories/${selectedStory.value.id}`, { method: 'POST', body: StoryDetailData })
        } else {
            await $api(`/admin/stories`, { method: 'POST', body: StoryDetailData })
        }
        errorMessage.value = ''
        errors.value = {}
        selectedStory.value = null
        fetchStoryData()
        isEditStoryDrawerVisible.value = false
    } catch (error) {
        errorMessage.value = error.response._data.message
        errors.value = error.response._data.errors
    }
}
</script>

<template>
    <VCard title='القصص'>
        <VCardItem>
            <VRow>
                <VCol>
                    <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
                        <VBtn prepend-icon="tabler-plus" @click="isEditStoryDrawerVisible = true">
                            إضافة عنصر جديد
                        </VBtn>
                    </div>
                </VCol>
            </VRow>

            <VRow v-if="errorMessage">
                <VAlert color="error" variant="tonal" class="mb-4">
                    {{ errorMessage }}
                </VAlert>
            </VRow>

            <VRow class="match-height">
                <VCol lg="4" sm="6" cols="12" v-for="story in stories" :key="story.id">
                    <VCard class="d-flex flex-column">
                        <VImg :src="story.image_path" />
                        <VCardItem>
                            <VCardTitle>{{ story.title }}</VCardTitle>
                        </VCardItem>

                        <VCardText>
                            <p class="mb-2">
                                {{ story.content }}
                            </p>
                            <audio v-if="story.audio_path" controls>
                                <source :src="story.audio_path" type="audio/mpeg">
                                متصفحك لا يدعم تشغيل الصوت
                            </audio>
                        </VCardText>
                        <VBtn block @click="openEditDrawer(story)">
                            تعديل
                        </VBtn>
                    </VCard>
                </VCol>
            </VRow>
        </VCardItem>
        <EditStoryDrawer v-model:isDrawerOpen="isEditStoryDrawerVisible" @story-detail-data="addOrUpdateStory"
            :storyId="storyId" :errors="errors" :storyDetail="selectedStory" />
    </VCard>
</template>
