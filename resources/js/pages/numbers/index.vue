<script setup>
import EditNumberDrawer from '@/views/numbers/EditNumberDrawer.vue'
import { VRow } from 'vuetify/lib/components/index.mjs';

const selectedNumber = ref(null)
const route = useRoute('numbers')

const errors = reactive({});
const errorMessage = ref();
const isEditNumberDrawerVisible = ref(false)

const {
    data: NumbersData,
    execute: fetchNumbers,
} = await useApi(createUrl(`/numbers`))

const numbers = computed(() => NumbersData.value.data)

const openEditDrawer = (number) => {
    selectedNumber.value = number;
    isEditNumberDrawerVisible.value = true;
};

const addOrUpdateNumber = async numberData => {
    try {
        if (selectedNumber.value) {
            numberData.append('_method', 'put')
            await $api(`/admin/numbers/${selectedNumber.value.id}`, { method: 'POST', body: numberData })
        } else {
            await $api(`/admin/numbers`, { method: 'POST', body: numberData })
        }
        errorMessage.value = ''
        errors.value = {}
        selectedNumber.value = null
        fetchNumbers()
        isEditNumberDrawerVisible.value = false
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
                    <VBtn prepend-icon="tabler-plus" @click="isEditNumberDrawerVisible = true">
                        إضافة رقم جديد
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
            <VCol lg="3" sm="6" cols="12" v-for="number in numbers" :key="number.id">
                <VCard class="d-flex flex-column">
                    <VImg :src="number.image_path" height="200px" />

                    <VCardItem>
                        <VCardTitle>{{ number.number }}</VCardTitle>
                    </VCardItem>

                    <audio v-if="number.audio_path" controls>
                        <source :src="number.audio_path" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                    <VCol>
                        <VBtn block @click="openEditDrawer(number)">
                            تعديل
                        </VBtn>
                    </VCol>
                </VCard>
            </VCol>
        </VRow>

        <EditNumberDrawer v-model:isDrawerOpen="isEditNumberDrawerVisible" @number-data="addOrUpdateNumber"
            :errors="errors" :number="selectedNumber" />
    </section>
</template>
