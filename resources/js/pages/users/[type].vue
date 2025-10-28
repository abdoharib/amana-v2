<script setup>
import AddNewUserDrawer from '@/views/users/AddNewUserDrawer.vue'
import { reactive } from 'vue'

// 👉 Store
const searchQuery = ref('')
const selectedStatus = ref()
const selectedUser = ref(null)
const isDeleteDialogVisible = ref(false)
const userToDelete = ref(null)
// Data table options
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const route = useRoute('users-type')

const errors = reactive({});
const errorMessage = ref();

const type = route.params.type

const updateOptions = options => {
  sortBy.value = options.sortBy[0]?.key
  orderBy.value = options.sortBy[0]?.order
}

// Headers
const headers = [
  { title: 'المعرف', key: 'id' },
  {
    title: 'الإسم',
    key: 'name',
  },
  {
    title: 'رقم الهاتف',
    key: 'phone_number',
  },
  {
    title: 'البريد الإلكتروني',
    key: 'email',
  },
  {
    title: 'تم التسجيل في',
    key: 'created_at',
  },
  {
    title: 'الحالة',
    key: 'is_activated',
  },
  {
    title: 'العمليات',
    key: 'actions',
    sortable: false,
  },
]

const {
  data: usersData,
  execute: fetchUsers,
} = await useApi(createUrl(`/admin/${type}`, {
  query: {
    'starts_with[phone_number]': searchQuery,
    'exact[is_activated]': selectedStatus,
    itemsPerPage,
    page,
    sortBy,
    orderBy,
  },
}))

const users = computed(() => usersData.value.data)
const totalUsers = computed(() => usersData.value.meta.total)


const status = [
  {
    title: 'نشط',
    value: 1,
  },
  {
    title: 'غير نشط',
    value: 0,
  },
]

const resolveUserStatusVariant = stat => {
  if (stat === 1)
    return 'success'
  if (stat === 0)
    return 'secondary'

  return 'primary'
}

const isAddNewUserDrawerVisible = ref(false)

const addOrUpdateUser = async userData => {
  try {
    if (selectedUser.value) {
      // If editing an existing user, send a PUT request
      await $api(`/admin/${type}/${selectedUser.value.id}`, {
        method: 'PUT',
        body: userData,
      })
    } else {
      // If adding a new user, send a POST request
      await $api(`/admin/${type}`, {
        method: 'POST',
        body: userData,
      })
    }

    errorMessage.value = ''
    errors.value = {}
    fetchUsers() // Refresh user list
    isAddNewUserDrawerVisible.value = false
  } catch (error) {
    errorMessage.value = error.response._data.message
    errors.value = error.response._data.errors
  }
}

const confirmDeleteUser = (id) => {
  userToDelete.value = id;
  isDeleteDialogVisible.value = true;
}

const deleteUser = async () => {
  if (!userToDelete.value) return;

  try {
    await $api(`/admin/${type}/${userToDelete.value}`, { method: 'DELETE' });
    fetchUsers(); // Refresh user list
    isDeleteDialogVisible.value = false; // Close the dialog
  } catch (error) {
    console.error("Error deleting user:", error);
  } finally {
    userToDelete.value = null; // Reset after deletion
  }
};

const openUserDrawer = user => {
  selectedUser.value = user
  isAddNewUserDrawerVisible.value = true
}

</script>

<template>
  <section>
    <!-- 👉 Widgets -->
  
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>فلترة</VCardTitle>
      </VCardItem>


      <VCardText>
        <VRow>
          <!-- 👉 Select Status -->
          <VCol cols="12" sm="4">
            <AppSelect v-model="selectedStatus" placeholder="إختر الحالة" :items="status" clearable
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
          <!-- 👉 Search  -->
          <div style="inline-size: 15.625rem;">
            <AppTextField v-model="searchQuery" placeholder="إبحث برقم الهاتف" />
          </div>

          <!-- 👉 Add user button -->
          <VBtn prepend-icon="tabler-plus" @click="isAddNewUserDrawerVisible = true">
            إضافة مستخدم جديد
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <!-- SECTION datatable -->
      <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :items="users"
        :items-length="totalUsers" :headers="headers" class="text-no-wrap" show-select @update:options="updateOptions">
        <!-- User -->
        <template #item.name="{ item }">
          <div class="d-flex align-center gap-x-4">
            <VAvatar size="34" :variant="!item.image ? 'tonal' : undefined" color="primary">
              <VImg v-if="item.image" :src="item.image" />
              <span v-else>{{ avatarText(item.name) }}</span>
            </VAvatar>
            <div class="d-flex flex-column">
              <h6 class="text-base">

                {{ item.name }}

              </h6>
              <div class="text-sm">
                {{ item.email }}
              </div>
            </div>
          </div>
        </template>

        <!-- Status -->
        <template #item.is_activated="{ item }">
          <VChip :color="resolveUserStatusVariant(item.is_activated)" size="small" label class="text-capitalize">
            {{ item.is_activated ? 'نشط' : 'غير نشط' }}
          </VChip>
        </template>
        <template #item.created_at="{ item }">

          {{ formatDate(item.created_at) }}

        </template>

        <!-- Actions -->
        <template #item.actions="{ item }">
          <IconBtn @click="confirmDeleteUser(item.id)">
            <VIcon icon="tabler-trash" />
          </IconBtn>

          <IconBtn @click="openUserDrawer(item)">
            <VIcon icon="tabler-pencil" />
          </IconBtn>
        </template>

        <!-- pagination -->
        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalUsers" />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>
    <VDialog v-model="isDeleteDialogVisible" max-width="400">
      <VCard>
        <VCardTitle>تأكيد الحذف</VCardTitle>
        <VCardText>هل أنت متأكد أنك تريد حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء.</VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn color="secondary" @click="isDeleteDialogVisible = false">إلغاء</VBtn>
          <VBtn color="error" @click="deleteUser">حذف</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <!-- 👉 Add New User -->
    <AddNewUserDrawer v-model:isDrawerOpen="isAddNewUserDrawerVisible" @user-data="addOrUpdateUser" :errors="errors"
      :user="selectedUser" />
  </section>
</template>
