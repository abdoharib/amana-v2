<script setup>
import EditMoralValueDrawer from '@/views/moral-values/EditMoralValueDrawer.vue'


const selectedMoralValue = ref(null)
const route = useRoute('moral-values')

const errors = reactive({})
const errorMessage = ref()
const isEditMoralValueDrawerVisible = ref(false)
const {
    data: MoralValueData,
    execute: fetchMoralValueData,
} = await useApi(createUrl(`/admin/moral-values`, {
}))

const moralValues = computed(() => MoralValueData.value)
const moralValueId = ref()
const openEditDrawer = (moralValueDetails) => {
    selectedMoralValue.value = moralValueDetails
    isEditMoralValueDrawerVisible.value = true
}

const addOrUpdateMoralValue = async MoralValueDetailData => {
    try {
        if (selectedMoralValue.value) {
            MoralValueDetailData.append('_method', 'put')
            await $api(`/admin/moral-values/${selectedMoralValue.value.id}`, { method: 'POST', body: MoralValueDetailData })
        } else {
            await $api(`/admin/moral-values`, { method: 'POST', body: MoralValueDetailData })
        }
        errorMessage.value = ''
        errors.value = {}
        selectedMoralValue.value = null
        fetchMoralValueData()
        isEditMoralValueDrawerVisible.value = false
    } catch (error) {
        errorMessage.value = error.response._data.message
        errors.value = error.response._data.errors
    }
}
</script>

<template>
    <VCard title='القيم الاخلاقية'>
        <VCardItem>
            <VRow>
                <VCol>
                    <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
                        <VBtn prepend-icon="tabler-plus" @click="isEditMoralValueDrawerVisible = true">
                            إضافة قيمة جديد
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
                <VCol lg="4" sm="6" cols="12" v-for="moralValue in moralValues" :key="moralValue.id">
                    <VCard class="d-flex flex-column">
                        <VImg :src="moralValue.image_path" />
                        <VCardItem>
                            <VCardTitle>{{ moralValue.title }}</VCardTitle>
                        </VCardItem>

                        <VCardText>
                            <p class="mb-2">
                                {{ moralValue.description }}
                            </p>
                        </VCardText>
                        <VBtn block @click="openEditDrawer(moralValue)">
                            تعديل
                        </VBtn>
                    </VCard>
                </VCol>
            </VRow>
        </VCardItem>
        <EditMoralValueDrawer v-model:isDrawerOpen="isEditMoralValueDrawerVisible" @moralValue-detail-data="addOrUpdateMoralValue"
            :moralValueId="moralValueId" :errors="errors" :moralValueDetail="selectedMoralValue" />
    </VCard>
</template>
