<script setup>
import EditCharityWorkDrawer from '@/views/charity-work/EditCharityWorkDrawer.vue'


const selectedCharityWork = ref(null)
const route = useRoute('charity-works')

const errors = reactive({})
const errorMessage = ref()
const isEditCharityWorkDrawerVisible = ref(false)
const {
    data: CharityWorkData,
    execute: fetchCharityWorkData,
} = await useApi(createUrl(`/admin/charity-works`, {
}))

const charityWorks = computed(() => CharityWorkData.value)
const charityWorkId = ref()
const openEditDrawer = (charityWorkDetails) => {
    selectedCharityWork.value = charityWorkDetails
    isEditCharityWorkDrawerVisible.value = true
}

const addOrUpdateCharityWork = async CharityWorkDetailData => {
    try {
        if (selectedCharityWork.value) {
            CharityWorkDetailData.append('_method', 'put')
            await $api(`/admin/charity-works/${selectedCharityWork.value.id}`, { method: 'POST', body: CharityWorkDetailData })
        } else {
            await $api(`/admin/charity-works`, { method: 'POST', body: CharityWorkDetailData })
        }
        errorMessage.value = ''
        errors.value = {}
        selectedCharityWork.value = null
        fetchCharityWorkData()
        isEditCharityWorkDrawerVisible.value = false
    } catch (error) {
        errorMessage.value = error.response._data.message
        errors.value = error.response._data.errors
    }
}
</script>

<template>
    <VCard title='اعمال الصدقة'>
        <VCardItem>
            <VRow>
                <VCol>
                    <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
                        <VBtn prepend-icon="tabler-plus" @click="isEditCharityWorkDrawerVisible = true">
                            إضافة عمل جديد
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
                <VCol lg="4" sm="6" cols="12" v-for="charityWork in charityWorks" :key="charityWork.id">
                    <VCard class="d-flex flex-column">
                        <VImg :src="charityWork.image_path" />
                        <VCardItem>
                            <VCardTitle>{{ charityWork.title }}</VCardTitle>
                        </VCardItem>

                        <VCardText>
                            <p class="mb-2">
                                {{ charityWork.description }}
                            </p>
                        </VCardText>
                        <VBtn block @click="openEditDrawer(charityWork)">
                            تعديل
                        </VBtn>
                    </VCard>
                </VCol>
            </VRow>
        </VCardItem>
        <EditCharityWorkDrawer v-model:isDrawerOpen="isEditCharityWorkDrawerVisible" @charityWork-detail-data="addOrUpdateCharityWork"
            :charityWorkId="charityWorkId" :errors="errors" :charityWorkDetail="selectedCharityWork" />
    </VCard>
</template>
