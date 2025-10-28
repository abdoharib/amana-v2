<script setup>
const currentTab = ref(9)

const {
    data: EducationContentData,
    execute: fetchEducationContent,
} = await useApi(createUrl(`/admin/educational-contents`, {
    query: {
        'exact[education_stage_id]': 3,
    }
}))

const educationContent = computed(() => EducationContentData.value)

const itemsData = reactive(ref([]))

const fetchItems = async () => {
    const { data } = await useApi(createUrl(`/admin/items`, {
        query: {
            'exact[educational_content_id]': currentTab.value,
        }
    }))
    itemsData.value = data
}

watchEffect(() => {
    fetchItems()
})

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
        title: 'التصنيف',
        key: 'category',
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
        <VTabs v-model="currentTab" next-icon="tabler-arrow-right" prev-icon="tabler-arrow-left">
            <VTab v-for="content in educationContent" :key="content.title" :value="content.id">

                {{ content.title }}
            </VTab>
        </VTabs>

        <VCardText>
            <VWindow v-model="currentTab">
                <VWindowItem v-for="content in educationContent" :key="content.title" :value="content.id">
                    <VCardText class="d-flex flex-wrap gap-4">
                        <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
                            <!-- 👉 Add user button -->
                            <VBtn prepend-icon="tabler-plus" @click="$router.push({ name: 'items-create' });">
                                إضافة عنصر جديد
                            </VBtn>
                        </div>
                    </VCardText>

                    <!-- SECTION datatable -->
                    <VDataTableServer :itemsLength="10" :items="itemsData" :headers="headers" class="text-no-wrap" show-select>
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
                        <template #item.category="{ item }">
                            <div class="d-flex align-center gap-x-4">
                                {{ item.category.title }}
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
                            <IconBtn  :to="{ name: 'items-show-id' ,params:{id:item.id} }">
                                <VIcon icon="tabler-eye" />
                            </IconBtn>

                            <IconBtn @click="$router.push({ name: 'items-update-id', params: { id: item.id } })">
                                <VIcon icon="tabler-pencil" />
                            </IconBtn>
                        </template>

                        <!-- pagination -->
                    </VDataTableServer>
                </VWindowItem>
            </VWindow>
        </VCardText>
    </VCard>
</template>
