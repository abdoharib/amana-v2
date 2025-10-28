<script setup>
import EditEducationStageDrawer from '@/views/education-stage/EditEducationStageDrawer.vue'
import { reactive } from 'vue'

const selectedEducationStage = ref(null)
const route = useRoute('education-stages')

const errors = reactive({});
const errorMessage = ref();
const isEditEducationStageDrawerVisible = ref(false)

const {
    data: EducationStageData,
    execute: fetchEducationStages,
} = await useApi(createUrl(`/education-stages`))

const educationStages = computed(() => EducationStageData.value.data)

const openEditDrawer = (stage) => {
    selectedEducationStage.value = stage;
    isEditEducationStageDrawerVisible.value = true;
};



const updateEducationStage = async data => {
    try {
        // If editing an existing user, send a PUT request
        await $api(`/admin/education-stages/${selectedEducationStage.value.id}`, {
            method: 'POST',
            body: data,
        })


        errorMessage.value = ''
        fetchEducationStages() // Refresh user list
        isEditEducationStageDrawerVisible.value = false
    } catch (error) {
        errorMessage.value = error.response._data.message
        errors.value = error.response._data.errors
    }
}
</script>

<template>
    <section>
        <VRow class="match-height">
            <!-- 👉 Widgets -->
            <VCol lg="4" sm="6" cols="12" v-for="stage in educationStages">
                <VCard class="d-flex flex-column ">
                    <VImg :src="stage.image_path" />

                    <VCardItem>
                        <VCardTitle>{{ stage.title }}</VCardTitle>
                    </VCardItem>

                    <VCardText>

                        <p class="mb-0">
                            {{ stage.description }}
                        </p>
                    </VCardText>

                    <VBtn block class="rounded-t-0" @click="openEditDrawer(stage)">
                        تعديل المرحلة
                    </VBtn>
                </VCard>
            </VCol>
        </VRow>
        <!-- 👉 Add New User -->
        <EditEducationStageDrawer v-model:isDrawerOpen="isEditEducationStageDrawerVisible"
            @stage-data="updateEducationStage" :errors="errors" :stage="selectedEducationStage" />
    </section>
</template>
