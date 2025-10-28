<script setup>
import EditPrayerContentDrawer from '@/views/prayers/EditPrayerContentDrawer.vue'


const selectedPrayerContent = ref(null)
const route = useRoute('prayer_contents')

const errors = reactive({})
const errorMessage = ref()
const isEditPrayerContentDrawerVisible = ref(false)
const prayerId = route.params.id;
const {
    data: PrayerContentsData,
    execute: fetchPrayerContents,
} = await useApi(createUrl(`/admin/prayers-content`, {
    query: {
        'exact[prayer_id]': prayerId,
    },
}))

const prayerContents = computed(() => PrayerContentsData.value)

const openEditDrawer = (prayerContent) => {
    console.log(prayerContent);

    selectedPrayerContent.value = prayerContent
    isEditPrayerContentDrawerVisible.value = true
}

const addOrUpdatePrayerContent = async prayerContentData => {
    try {
        if (selectedPrayerContent.value) {
            prayerContentData.append('_method', 'put')
            await $api(`/admin/prayers-content/${selectedPrayerContent.value.id}`, { method: 'POST', body: prayerContentData })
        } else {
            await $api(`/admin/prayers-content`, { method: 'POST', body: prayerContentData })
        }
        errorMessage.value = ''
        errors.value = {}
        selectedPrayerContent.value = null
        fetchPrayerContents()
        isEditPrayerContentDrawerVisible.value = false
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
                    <VBtn prepend-icon="tabler-plus" @click="isEditPrayerContentDrawerVisible = true">
                        إضافة محتوى جديد
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
            <VCol lg="4" sm="6" cols="12" v-for="prayerContent in prayerContents" :key="prayerContent.id">
                <VCard class="d-flex flex-column">
                    <VCardItem>
                        <VCardTitle>ذكر متعلق بالصلاة #{{ prayerContent.prayer_id }}</VCardTitle>
                    </VCardItem>

                    <VCardText>
                        <p class="mb-2">
                            {{ prayerContent.content }}
                        </p>
                        <audio v-if="prayerContent.audio_file" controls>
                            <source :src="prayerContent.audio_file" type="audio/mpeg">
                            متصفحك لا يدعم تشغيل الصوت
                        </audio>
                    </VCardText>
                    <VBtn block @click="openEditDrawer(prayerContent)">
                        تعديل
                    </VBtn>
                </VCard>
            </VCol>
        </VRow>

        <EditPrayerContentDrawer v-model:isDrawerOpen="isEditPrayerContentDrawerVisible"
            @prayer-content-data="addOrUpdatePrayerContent" :prayerId="prayerId" :errors="errors"
            :prayerContent="selectedPrayerContent" />
    </section>
</template>
