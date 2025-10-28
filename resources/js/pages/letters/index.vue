<script setup>
import EditLetterDrawer from '@/views/letters/EditLetterDrawer.vue'
import { VRow } from 'vuetify/lib/components/index.mjs';

const selectedLetter = ref(null)
const route = useRoute('letters')

const errors = reactive({});
const errorMessage = ref();
const isEditLetterDrawerVisible = ref(false)

const {
    data: LettersData,
    execute: fetchLetters,
} = await useApi(createUrl(`/letters`))

const letters = computed(() => LettersData.value.data)

const openEditDrawer = (letter) => {
    selectedLetter.value = letter;
    isEditLetterDrawerVisible.value = true;
};

const addOrUpdateLetter = async letterData => {
    try {
        if (selectedLetter.value) {
            letterData.append('_method', 'put')
            await $api(`/admin/letters/${selectedLetter.value.id}`, { method: 'POST', body: letterData })
        } else {
            await $api(`/admin/letters`, { method: 'POST', body: letterData })
        }
        errorMessage.value = ''
        errors.value = {}
        selectedLetter.value = null
        fetchLetters()
        isEditLetterDrawerVisible.value = false
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
                    <VBtn prepend-icon="tabler-plus" @click="isEditLetterDrawerVisible = true">
                        إضافة حرف جديد
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
            <VCol lg="3" sm="6" cols="12" v-for="letter in letters" :key="letter.id">
                <VCard class="d-flex flex-column">
                    <VImg :src="letter.image_path" height="200px" />

                    <VCardItem>
                        <VCardTitle>{{ letter.letter }}</VCardTitle>
                        <VCardTitle>{{ letter.word }}</VCardTitle>
                    </VCardItem>

                    <audio v-if="letter.audio_path" controls>
                        <source :src="letter.audio_path" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                    <audio v-if="letter.word_audio" controls>
                        <source :src="letter.word_audio" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                    <VCol>
                        <VBtn block @click="openEditDrawer(letter)">
                            تعديل
                        </VBtn>
                    </VCol>
                </VCard>
            </VCol>
        </VRow>

        <EditLetterDrawer v-model:isDrawerOpen="isEditLetterDrawerVisible" @letter-data="addOrUpdateLetter"
            :errors="errors" :letter="selectedLetter" />
    </section>
</template>
