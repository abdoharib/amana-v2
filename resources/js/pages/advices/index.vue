<script setup>
import AddNewAdviceDrawer from '@/views/advices/AddNewAdviceDrawer.vue'
import { reactive, computed, ref } from 'vue'

// Store
const searchQuery = ref('')
const selectedGuardian = ref()
const selectedKid = ref()
const selectedAdvice = ref()
const isDeleteDialogVisible = ref(false)
const adviceToDelete = ref(null)

// Data table options
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()

const errors = reactive({})
const errorMessage = ref()

const updateOptions = options => {
  sortBy.value = options.sortBy[0]?.key
  orderBy.value = options.sortBy[0]?.order
}

// Headers for Advice Table
const headers = [
  { title: 'المعرف', key: 'id' },
  { title: 'العنوان', key: 'title' },
  { title: 'الوصف', key: 'description' },
  { title: 'الصورة', key: 'image' },
  { title: 'معرف الأب', key: 'guardian_id' },
  { title: 'معرف الطفل', key: 'kid_id' },
  { title: 'تم الإنشاء في', key: 'created_at' },
  { title: 'العمليات', key: 'actions', sortable: false },
]

// Fetch Advice Data
const {
  data: adviceData,
  execute: fetchAdvice,
} = await useApi(createUrl(`/advice`, {
  query: {
    'starts_with[title]': searchQuery,
    'exact[guardian_id]': selectedGuardian,
    'exact[kid_id]': selectedKid,
    itemsPerPage,
    page,
    sortBy,
    orderBy,
  },
}))

const advice = computed(() => adviceData.value.data)
const totalAdvice = computed(() => adviceData.value.meta.total)

// Fetch Guardians & Kids
const { data: GuardiansData } = await useApi(createUrl(`/admin/guardians/all`, {}))

const guardians = computed(() => GuardiansData.value)


// Add or Update Advice
const addOrUpdateAdvice = async adviceData => {
  try {
    if (selectedAdvice.value) {
      await $api(`/advice/${selectedAdvice.value.id}`, { method: 'PUT', body: adviceData })
    } else {
      await $api(`/advice`, { method: 'POST', body: adviceData })
    }
    errorMessage.value = ''
    errors.value = {}
    selectedAdvice.value = null
    fetchAdvice()
    isAddNewAdviceDrawerVisible.value = false
  } catch (error) {
    errorMessage.value = error.response._data.message
    errors.value = error.response._data.errors
  }
}

// Delete Advice
const confirmDeleteAdvice = (id) => {
  adviceToDelete.value = id;
  isDeleteDialogVisible.value = true;
}
const deleteAdvice = async () => {
  if (!adviceToDelete.value) return;
  try {
    await $api(`/advice/${adviceToDelete.value}`, { method: 'DELETE' })
    fetchAdvice()
    isDeleteDialogVisible.value = false
  } catch (error) {
    console.error("Error deleting advice:", error)
  } finally {
    adviceToDelete.value = null
  }
}

// Open Drawer for Adding or Editing Advice
const isAddNewAdviceDrawerVisible = ref(false)
const openAdviceDrawer = advice => {

  selectedAdvice.value = advice
  isAddNewAdviceDrawerVisible.value = true
}
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>فلترة</VCardTitle>
      </VCardItem>

      <VCardText>
        <VRow>
          <VCol cols="12" sm="4">
            <AppTextField v-model="searchQuery" placeholder="إبحث بالعنوان" />
          </VCol>
          <VCol cols="12" sm="4">
            <AppSelect v-model="selectedGuardian" placeholder="إختر الأب" item-title="name" item-value="id" :items="guardians" clearable clear-icon="tabler-x" />
          </VCol>
        </VRow>
      </VCardText>

      <VDivider />

      <VCardText class="d-flex flex-wrap gap-4">
        <div class="me-3 d-flex gap-3">
          <AppSelect :model-value="itemsPerPage" :items="[
            { value: 10, title: '10' },
            { value: 25, title: '25' },
            { value: 50, title: '50' },
            { value: 100, title: '100' },
            { value: -1, title: 'All' },
          ]" style="inline-size: 6.25rem;" @update:model-value="itemsPerPage = parseInt($event, 10)" />
        </div>
        <VSpacer />

        <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
          <VBtn prepend-icon="tabler-plus" @click="isAddNewAdviceDrawerVisible = true">
            إضافة نصيحة جديد
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page"
        :items="advice" :items-length="totalAdvice" :headers="headers"
        class="text-no-wrap" show-select @update:options="updateOptions">

        <template #item.title="{ item }">{{ item.title }}</template>
        <template #item.description="{ item }">{{ item.description }}</template>
        <template #item.image="{ item }"><img :src="item.image" width="50" /></template>
        <template #item.guardian_id="{ item }">{{ item.guardian?.name }}</template>
        <template #item.kid_id="{ item }">{{ item.kid?.first_name }}</template>
        <template #item.created_at="{ item }">{{ formatDate(item.created_at) }}</template>

        <template #item.actions="{ item }">
          <IconBtn @click="confirmDeleteAdvice(item.id)"><VIcon icon="tabler-trash" /></IconBtn>
          <IconBtn @click="openAdviceDrawer(item)"><VIcon icon="tabler-pencil" /></IconBtn>
        </template>
        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalAdvice" />
        </template>
      </VDataTableServer>
    </VCard>

    <VDialog v-model="isDeleteDialogVisible" max-width="400">
      <VCard>
        <VCardTitle>تأكيد الحذف</VCardTitle>
        <VCardText>هل أنت متأكد أنك تريد حذف هذه النصيحة؟ لا يمكن التراجع عن هذا الإجراء.</VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn color="secondary" @click="isDeleteDialogVisible = false">إلغاء</VBtn>
          <VBtn color="error" @click="deleteAdvice">حذف</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
      <!-- Add/edit advice Drawer -->
      <AddNewAdviceDrawer v-model:isDrawerOpen="isAddNewAdviceDrawerVisible" @advice-data="addOrUpdateAdvice"
      :errors="errors" :advice="selectedAdvice" :guardians="guardians" />
  </section>
</template>
