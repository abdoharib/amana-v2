<script setup>
import EditItemDetailDrawer from '@/views/items/EditItemDetailsDrawer.vue'


const selectedItem = ref(null)
const route = useRoute('items')

const errors = reactive({})
const errorMessage = ref()
const isEditItemDrawerVisible = ref(false)
const itemId = route.params.id;
const {
    data: ItemData,
    execute: fetchItemData,
} = await useApi(createUrl(`/items/${itemId}`, {
}))

const item = computed(() => ItemData.value.data)

const openEditDrawer = (itemDetails) => {
    
    selectedItem.value = itemDetails
    isEditItemDrawerVisible.value = true
}

const addOrUpdateItem = async ItemDetailData => {
    try {
        if (selectedItem.value) {
            ItemDetailData.append('_method', 'put')
            await $api(`/admin/item-details/${selectedItem.value.id}`, { method: 'POST', body: ItemDetailData })
        } else {
            await $api(`/admin/item-details`, { method: 'POST', body: ItemDetailData })
        }
        errorMessage.value = ''
        errors.value = {}
        selectedItem.value = null
        fetchItemData()
        isEditItemDrawerVisible.value = false
    } catch (error) {
        errorMessage.value = error.response._data.message
        errors.value = error.response._data.errors
    }
}
</script>

<template>
    <VCard :title="item.title" :subtitle="item.category">
        <VCardItem>
            <VRow>
                <VCol>
                    <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
                        <VBtn prepend-icon="tabler-plus" @click="isEditItemDrawerVisible = true">
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
                <VCol lg="4" sm="6" cols="12" v-for="detail in item.details" :key="detail.id">
                    <VCard class="d-flex flex-column">
                        <VImg :src="detail.image_path" />
                        <VCardItem>
                            <VCardTitle>{{ detail.title }}</VCardTitle>
                        </VCardItem>

                        <VCardText>
                            <p class="mb-2">
                                {{ detail.description }}
                            </p>
                            <audio v-if="detail.audio_path" controls>
                                <source :src="detail.audio_path" type="audio/mpeg">
                                متصفحك لا يدعم تشغيل الصوت
                            </audio>
                        </VCardText>
                        <VBtn block @click="openEditDrawer(detail)">
                            تعديل
                        </VBtn>
                    </VCard>
                </VCol>
            </VRow>
        </VCardItem>
        <EditItemDetailDrawer v-model:isDrawerOpen="isEditItemDrawerVisible" @item-detail-data="addOrUpdateItem"
            :itemId="itemId" :errors="errors" :itemDetail="selectedItem" />
    </VCard>
</template>
