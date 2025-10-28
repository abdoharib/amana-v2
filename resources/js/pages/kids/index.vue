<script setup>
import AddNewKidDrawer from '@/views/kids/AddNewKidDrawer.vue' // Updated to kids drawer
import { reactive, computed, ref } from 'vue'

// 👉 Store
const searchQuery = ref('')
const selectedGuardian = ref()
const selectedUser = ref(null)
const isDeleteDialogVisible = ref(false)
const userToDelete = ref(null)

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

// Headers for Kids Table
const headers = [
  { title: 'المعرف', key: 'id' },
  { title: 'الإسم الأول', key: 'first_name' },
  { title: 'الإسم الأخير', key: 'last_name' },
  { title: 'تاريخ الميلاد', key: 'date_of_birth' },
  { title: 'الجنس', key: 'sex' },
  { title: 'الوزن (كغ)', key: 'weight' },
  { title: 'الطول (سم)', key: 'height' },
  { title: 'معرف الأب', key: 'guardian_id' },
  { title: 'تم الإنشاء في', key: 'created_at' },
  { title: 'العمليات', key: 'actions', sortable: false },
]

// Fetch Kids Data
const {
  data: kidsData,
  execute: fetchKids,
} = await useApi(createUrl(`/kids`, {
  query: {
    'starts_with[first_name]': searchQuery,
    'exact[guardian_id]': selectedGuardian,
    itemsPerPage,
    page,
    sortBy,
    orderBy,
  },
}))

const kids = computed(() => kidsData.value.data)
const totalKids = computed(() => kidsData.value.meta.total)


const {
  data: GuardiansData,
} = await useApi(createUrl(`/admin/guardians/all`, {
}))
const guardians = computed(() => GuardiansData.value)



// Add or Update Kid
const addOrUpdateKid = async kidData => {
  try {
    if (selectedUser.value) {
      // Editing existing kid
      await $api(`/kids/${selectedUser.value.id}`, {
        method: 'PUT',
        body: kidData,
      })
    } else {
      // Adding a new kid
      await $api(`/kids`, {
        method: 'POST',
        body: kidData,
      })
    }

    errorMessage.value = ''
    errors.value = {}
    selectedUser.value = null
    fetchKids() // Refresh kids list
    isAddNewKidDrawerVisible.value = false
  } catch (error) {
    errorMessage.value = error.response._data.message
    errors.value = error.response._data.errors
  }
}

// Confirm Delete
const confirmDeleteKid = (id) => {
  userToDelete.value = id;
  isDeleteDialogVisible.value = true;
}

// Delete Kid
const deleteKid = async () => {
  if (!userToDelete.value) return;

  try {
    await $api(`/kids/${userToDelete.value}`, { method: 'DELETE' });
    fetchKids(); // Refresh kids list
    isDeleteDialogVisible.value = false;
  } catch (error) {
    console.error("Error deleting kid:", error);
  } finally {
    userToDelete.value = null;
  }
}

// Open Drawer for Adding or Editing Kid
const isAddNewKidDrawerVisible = ref(false)
const openUserDrawer = user => {
  selectedUser.value = user
  isAddNewKidDrawerVisible.value = true
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
            <AppTextField v-model="searchQuery" placeholder="إبحث بالإسم الأول" />
          </VCol>
          <VCol cols="12" sm="4">
            <AppSelect v-model="selectedGuardian" placeholder="إختر الاب" item-title="name" item-value="id" :items="guardians" clearable
              clear-icon="tabler-x" />
          </VCol>
        </VRow>
      </VCardText>

      <VDivider />
      <VCardItem class="pb-4" v-if="errorMessage">
        <VAlert color="error" variant="tonal" class="mb-4">
          {{ errorMessage }}
        </VAlert>
      </VCardItem>
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
          <VBtn prepend-icon="tabler-plus" @click="isAddNewKidDrawerVisible = true">
            إضافة طفل جديد
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <!-- Kids Table -->
      <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page"
        :items="kids" :items-length="totalKids" :headers="headers"
        class="text-no-wrap" show-select @update:options="updateOptions">
        
        <template #item.first_name="{ item }">
          {{ item.first_name }}
        </template>

        <template #item.last_name="{ item }">
          {{ item.last_name }}
        </template>

        <template #item.date_of_birth="{ item }">
          {{ formatDate(item.date_of_birth) }}
        </template>

        <template #item.sex="{ item }">
          <VChip :color="item.sex === 'male' ? 'blue' : 'pink'" size="small" label>
            {{ item.sex === 'male' ? 'ذكر' : 'أنثى' }}
          </VChip>
        </template>

        <template #item.weight="{ item }">
          {{ item.weight || '-' }} كغ
        </template>

        <template #item.height="{ item }">
          {{ item.height || '-' }} سم
        </template>

        <template #item.guardian_id="{ item }">
          {{ item.guardian_id }}
        </template>
        <template #item.created_at="{ item }">
          {{ formatDate(item.created_at) }}
        </template>

        <template #item.actions="{ item }">
          <IconBtn @click="confirmDeleteKid(item.id)">
            <VIcon icon="tabler-trash" />
          </IconBtn>

          <IconBtn @click="openUserDrawer(item)">
            <VIcon icon="tabler-pencil" />
          </IconBtn>
        </template>

        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalKids" />
        </template>
      </VDataTableServer>
    </VCard>

    <!-- Delete Confirmation Dialog -->
    <VDialog v-model="isDeleteDialogVisible" max-width="400">
      <VCard>
        <VCardTitle>تأكيد الحذف</VCardTitle>
        <VCardText>هل أنت متأكد أنك تريد حذف هذا الطفل؟ لا يمكن التراجع عن هذا الإجراء.</VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn color="secondary" @click="isDeleteDialogVisible = false">إلغاء</VBtn>
          <VBtn color="error" @click="deleteKid">حذف</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <!-- Add/Edit Kid Drawer -->
    <AddNewKidDrawer v-model:isDrawerOpen="isAddNewKidDrawerVisible" @user-data="addOrUpdateKid"
      :errors="errors" :user="selectedUser" :guardians="guardians" />
  </section>
</template>
