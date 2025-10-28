<script setup>
import EditEducationContentDrawer from '@/views/educational-content/EditEducationContentDrawer.vue'
const currentTab = ref(1)

const {
  data: EducationStageData,
  execute: fetchEducationStages,
} = await useApi(createUrl(`/education-stages`))

const educationStages = computed(() => EducationStageData.value?.data)

const errors = reactive({});
const errorMessage = ref();
const EducationContentData = reactive(ref([]))
const selectedEducationContent = ref(null)
const isEditEducationContentDrawerVisible = ref(false)

const fetchEducationContent = async () => {
  const { data } = await useApi(createUrl(`/admin/educational-contents`, {
    query: {
      'exact[education_stage_id]': currentTab.value,
    }
  }))
  EducationContentData.value = data
}


watchEffect(() => {
  fetchEducationContent()
})


const openEditDrawer = (content) => {
  selectedEducationContent.value = content;
  isEditEducationContentDrawerVisible.value = true;
};


const updateEducationContent = async data => {
  try {
    // If editing an existing user, send a PUT request
    let res = await $api(`/admin/educational-contents/${selectedEducationContent.value.id}`, {
      method: 'POST',
      body: data,
    })
    errorMessage.value = ''
    fetchEducationContent() // Refresh user list
    isEditEducationContentDrawerVisible.value = false
  } catch (error) {
    errorMessage.value = error.response._data.message
    errors.value = error.response._data.errors
  }
}

</script>

<template>
  <VCard>
    <div class="d-flex">
      <div>
        <VTabs v-model="currentTab" direction="vertical">
          <VTab v-for="stage in educationStages" :key="stage.title" :value="stage.id">
            <VIcon start icon="tabler-user" />
            {{ stage.title }}
          </VTab>
        </VTabs>
      </div>

      <VCardText v-if="EducationContentData.length">
        <VWindow v-model="currentTab" class="ms-3">
          <VWindowItem v-for="stage in educationStages" :key="stage.title" :value="stage.id">



            <VRow class="match-height">
              <!-- 👉 Widgets -->
              <VCol lg="4" sm="6" cols="12" v-for="content in EducationContentData">
                <VCard class="d-flex flex-column ">
                  <VImg :src="content.image_path" />

                  <VCardItem>
                    <VCardTitle>{{ content.title }}</VCardTitle>
                  </VCardItem>

                  <VCardText>

                    <p class="mb-0">
                      {{ content.description }}
                    </p>
                  </VCardText>

                  <VBtn block class="rounded-t-0" @click="openEditDrawer(content)">
                    تعديل المرحلة
                  </VBtn>
                </VCard>
              </VCol>
            </VRow>
          </VWindowItem>


        </VWindow>
      </VCardText>
      <EditEducationContentDrawer v-model:isDrawerOpen="isEditEducationContentDrawerVisible"
        @content-data="updateEducationContent" :errors="errors" :content="selectedEducationContent" />
    </div>
  </VCard>
</template>
