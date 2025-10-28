<script setup>
import EditPrayerDrawer from '@/views/prayers/EditPrayerDrawer.vue'
import { VRow } from 'vuetify/lib/components/index.mjs';


const selectedPrayer = ref(null)
const route = useRoute('prayers')

const errors = reactive({});
const errorMessage = ref();
const isEditPrayerDrawerVisible = ref(false)

const {
    data: PrayersData,
    execute: fetchPrayers,
} = await useApi(createUrl(`/prayers`))

const prayers = computed(() => PrayersData.value)

const openEditDrawer = (prayer) => {
    selectedPrayer.value = prayer;
    isEditPrayerDrawerVisible.value = true;
};

const addOrUpdatePrayer = async prayerData => {
    try {
        if (selectedPrayer.value) {
            prayerData.append('_method', 'put')
            await $api(`/admin/prayers/${selectedPrayer.value.id}`, { method: 'POST', body: prayerData })
        } else {
            await $api(`/admin/prayers`, { method: 'POST', body: prayerData, })
        }
        errorMessage.value = ''
        errors.value = {}
        selectedPrayer.value = null
        fetchPrayers()
        isEditPrayerDrawerVisible.value = false
    } catch (error) {
        errorMessage.value = error.response._data.message
        errors.value = error.response._data.errors
    }
}
</script>

<template>
    <section>
        <VRow>
            <VCol>
                <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
                    <VBtn prepend-icon="tabler-plus" @click="isEditPrayerDrawerVisible = true">
                        إضافة ذكر جديد
                    </VBtn>
                </div>
            </VCol>
        </VRow>
        <VRow v-if="errorMessage">
            <VAlert color="error" variant="tonal" class="mb-4" >
                {{ errorMessage }}
            </VAlert>
        </VRow>
        <VRow class="match-height">
            <VCol lg="3" sm="6" cols="12" v-for="prayer in prayers" :key="prayer.id">
                <VCard class="d-flex flex-column">
                    <VImg :src="prayer.image_path" />

                    <VCardItem>
                        <VCardTitle>{{ prayer.title }}</VCardTitle>
                    </VCardItem>

                    <VRow class="justify-center">
                        <VCol cols="6" class="buttons">
                            <VBtn block @click="openEditDrawer(prayer)">
                                تعديل
                            </VBtn>
                        </VCol>
                        <VCol cols="6" class="buttons">
                            <VBtn block variant="tonal"
                                @click="$router.push({ name: 'prayers-show-id', params: { id: prayer.id } })">
                                عرض الاذكار
                            </VBtn>
                        </VCol>
                    </VRow>

                </VCard>
            </VCol>
        </VRow>

        <EditPrayerDrawer v-model:isDrawerOpen="isEditPrayerDrawerVisible" @prayer-data="addOrUpdatePrayer"
            :errors="errors" :prayer="selectedPrayer" />
    </section>
</template>
