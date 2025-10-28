<script setup>
import ManagePostDrawer from '@/views/posts/ManagePostDrawer.vue'

import { reactive } from 'vue'

// 👉 Store
const searchQuery = ref('')
const selectedStatus = ref()
const selectedPost = ref(null)
const selectedCategory = ref()
const isDeleteDialogVisible = ref(false)
const postToDelete = ref(null)
// Data table options
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const route = useRoute('posts')

const errors = reactive({});
const errorMessage = ref();

const updateOptions = options => {
    sortBy.value = options.sortBy[0]?.key
    orderBy.value = options.sortBy[0]?.order
}

// Headers
const headers = [
    { title: 'المعرف', key: 'id' },

    {
        title: 'الصور',
        key: 'media',
    },
    {
        title: 'العنوان',
        key: 'title',
    },
    {
        title: 'التصنيف',
        key: 'category',
    },
    {
        title: 'إسم الكاتب',
        key: 'author',
    },
    {
        title: 'الحالة',
        key: 'is_published',
    },
    {
        title: 'مثّبت',
        key: 'is_pinned',
    },
    {
        title: 'تم الكتابة في',
        key: 'created_at',
    },
    {
        title: 'تم النشر في',
        key: 'published_at',
    },
    {
        title: 'العمليات',
        key: 'actions',
        sortable: false,
    },
]
const {
    data: categoryData
} = await useApi(createUrl(`/posts/categories`))

const categories = computed(() => categoryData.value)

const {
    data: postsData,
    execute: fetchPosts,
} = await useApi(createUrl(`/posts`, {
    query: {
        'exact[is_published]': selectedStatus,
        'exact[categories.post_category_id]': selectedCategory,
        itemsPerPage,
        page,
        sortBy,
        orderBy,
    },
}))

const posts = computed(() => postsData.value.data)
const totalPosts = computed(() => postsData.value.meta.total)


const status = [
    {
        title: 'تم النشر',
        value: 1,
    },
    {
        title: 'غير منشور',
        value: 0,
    },
]

const resolvePostStatusVariant = stat => {
    if (stat === 1)
        return 'success'
    if (stat === 0)
        return 'secondary'

    return 'primary'
}

const isPostDrawerVisible = ref(false)

const addOrUpdatePost = async postData => {
    
    try {
        if (selectedPost.value) {
            // If editing an existing post, send a PUT request
            await $api(`/posts/${selectedPost.value.id}`, {
                method: 'POST',
                body: postData,
            })
        } else {
            // If adding a new post, send a POST request
            await $api(`/posts`, {
                method: 'POST',
                body: postData,
            })
        }

        errorMessage.value = ''
        errors.value = {}
        fetchPosts() // Refresh post list
        isPostDrawerVisible.value = false
    } catch (error) {
        errorMessage.value = error.response._data.message
        errors.value = error.response._data.errors
    }
}

const confirmDeletePost = (id) => {
    postToDelete.value = id;
    isDeleteDialogVisible.value = true;
}

const deletePost = async () => {
    if (!postToDelete.value) return;

    try {
        await $api(`/posts/${postToDelete.value}`, { method: 'DELETE' });
        fetchPosts(); // Refresh post list
        isDeleteDialogVisible.value = false; // Close the dialog
    } catch (error) {
        console.error("Error deleting post:", error);
    } finally {
        postToDelete.value = null; // Reset after deletion
    }
};

const openPostDrawer = post => {
    selectedPost.value = post
    isPostDrawerVisible.value = true
}
const handleDrawerStateChange = val => {
    console.log(val);
    
  isPostDrawerVisible.value = val

  // reset post when drawer closes
  if (!val) {
    selectedPost.value = null
  }
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
                    <!-- 👉 Select Category -->
                    <VCol cols="12" sm="4">
                        <AppSelect v-model="selectedCategory" placeholder="إختر التصنيف" :items="categories"
                            item-value="id" item-title="name" clearable clear-icon="tabler-x" />
                    </VCol>
                    <!-- 👉 Search -->
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

                    <!-- 👉 Add post button -->
                    <VBtn prepend-icon="tabler-plus" @click="isPostDrawerVisible = true">
                        إضافة منشور جديد
                    </VBtn>
                </div>
            </VCardText>

            <VDivider />

            <!-- SECTION datatable -->
            <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:page="page" :items="posts"
                :items-length="totalPosts" :headers="headers" class="text-no-wrap" show-select
                @update:options="updateOptions">
               
                <template #item.media="{ item }">
                    <div class="d-flex align-center gap-x-4">
                        <VImg v-if="item.media" :src="item.media?.image" />
                        <VVideo v-else-if="item.media?.video" :src="item.media.video" />
                        <VIcon v-else icon="tabler-image-off" />
                    </div>
                </template>

                <!-- Status -->
                <template #item.is_published="{ item }">
                    <VChip :color="resolvePostStatusVariant(item.is_published)" size="small" label
                        class="text-capitalize">
                        {{ item.is_published ? 'منشور' : 'غير منشور' }}
                    </VChip>
                </template>
                <template #item.category="{ item }">
                    <div v-if="item.categories && item.categories.length">
                        <VChip v-for="category in item.categories" class="me-1" size="small">
                            {{ category.name }}
                        </VChip>
                    </div>
                    <div v-else>
                        <VChip color="grey" size="small">غير محدد</VChip>
                    </div>
                </template>

                <template #item.author="{ item }">
                    {{ item.user ? item.user.name : 'غير محدد' }}
                </template>

                <template #item.created_at="{ item }">

                    {{ formatDate(item.created_at) }}

                </template>
                <template #item.published_at="{ item }">

                    {{ formatDate(item.published_at) || 'غير منشور' }}

                </template>

                <!-- Actions -->
                <template #item.actions="{ item }">
                    <IconBtn @click="confirmDeletePost(item.id)">
                        <VIcon icon="tabler-trash" />
                    </IconBtn>

                    <IconBtn @click="openPostDrawer(item)">
                        <VIcon icon="tabler-pencil" />
                    </IconBtn>
                </template>

                <!-- pagination -->
                <template #bottom>
                    <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalPosts" />
                </template>
            </VDataTableServer>
            <!-- SECTION -->
        </VCard>
        <VDialog v-model="isDeleteDialogVisible" max-width="400">
            <VCard>
                <VCardTitle>تأكيد الحذف</VCardTitle>
                <VCardText>هل أنت متأكد أنك تريد حذف هذا المنشور؟ لا يمكن التراجع عن هذا الإجراء.</VCardText>
                <VCardActions>
                    <VSpacer />
                    <VBtn color="secondary" @click="isDeleteDialogVisible = false">إلغاء</VBtn>
                    <VBtn color="error" @click="deletePost">حذف</VBtn>
                </VCardActions>
            </VCard>
        </VDialog>
        <!-- 👉 Add New post -->
        <ManagePostDrawer v-model:isDrawerOpen="isPostDrawerVisible"  @update:isDrawerOpen="handleDrawerStateChange" @post-data="addOrUpdatePost" :errors="errors"
            :post="selectedPost" :categories="categories" />
    </section>
</template>
