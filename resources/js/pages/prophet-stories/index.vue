<script setup>

const {
    data: storiesData,
    execute: fetchStories,
} = await useApi(createUrl(`/prophet-stories`))

const stories = computed(() => storiesData.value.data)

// Headers
const headers = [
    { title: 'المعرف', key: 'id' },
    {
        title: 'الإسم',
        key: 'title',
    },
    {
        title: 'الصورة',
        key: 'image',
    },
    {
        title: 'تم الانشاء في',
        key: 'created_at',
    },
    {
        title: 'العمليات',
        key: 'actions',
        sortable: false,
    },
]
</script>

<template>
    <VCard>
        <VCardText>

            <VCardText class="d-flex flex-wrap gap-4">
                <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
                    <!-- 👉 Add user button -->
                    <VBtn prepend-icon="tabler-plus" @click="$router.push({ name: 'prophet-stories-create' });">
                        إضافة قصة جديدة
                    </VBtn>
                </div>
            </VCardText>

            <!-- SECTION datatable -->
            <VDataTableServer :itemsLength="10" :items="stories" :headers="headers" class="text-no-wrap" show-select>
                <!-- User -->
                <template #item.title="{ item }">
                    <div class="d-flex align-center gap-x-4">
                        {{ item.title }}
                    </div>
                </template>
                <template #item.image="{ item }">
                    <div class="d-flex align-center gap-x-4">
                        <img height="100" :src="item.image_path">
                    </div>
                </template>

                <!-- Status -->

                <template #item.created_at="{ item }">

                    {{ formatDate(item.created_at) }}

                </template>

                <!-- Actions -->
                <template #item.actions="{ item }">
                    <IconBtn @click="confirmDeleteUser(item.id)">
                        <VIcon icon="tabler-trash" />
                    </IconBtn>
                    <IconBtn @click="$router.push({ name: 'prophet-stories-show-id', params: { id: item.id } })">
                        <VIcon icon="tabler-eye" />
                    </IconBtn>

                    <IconBtn @click="$router.push({ name: 'prophet-stories-update-id', params: { id: item.id } })">
                        <VIcon icon="tabler-pencil" />
                    </IconBtn>
                </template>

                <!-- pagination -->
            </VDataTableServer>
        </VCardText>
    </VCard>
</template>
