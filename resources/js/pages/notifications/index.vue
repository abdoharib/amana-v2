<script setup>
import { VCol } from 'vuetify/lib/components/index.mjs';

const route = useRoute('moral-values')

const {
  data: GuardiansData,
} = await useApi(createUrl(`/admin/guardians/all`, {
}))
const guardians = computed(() => GuardiansData.value)

const notification = reactive({
    title: '',
    body: '',
    user_ids:''
});
const errorMessage = ref('');
const successMessage = ref('');
const sendNotification = async () => {
    errorMessage.value = '';
    successMessage.value = '';


    $api('/admin/notifications/send', {
        method: 'POST',
        body: {
            title: notification.title,
            body: notification.body,
            user_ids: JSON.stringify(notification.user_ids) // Assuming this is a comma-separated string of user IDs
        },
        headers: {
            'Accept': 'application/json' // No need for 'Content-Type', browser sets it automatically
        }
    }).then(response => {
        successMessage.value = 'تم إرسال الإشعار بنجاح!';
        notification.title = '';
        notification.body = '';
        notification.user_ids = '';

    }).catch(error => {
        errorMessage.value = 'حدث خطأ أثناء إرسال الإشعار. يرجى المحاولة مرة أخرى.';
        console.error(error);
    });


};
</script>

<template>
    <VCard title='إرسال إشعار'>
        <VCardText>

            <p>هذه الصفحة مخصصة لإرسال إشعارات للمستخدمين.</p>
            <p>يمكنك استخدام النموذج أدناه لإرسال إشعار جديد.</p>
            <form @submit.prevent="sendNotification">
                <VRow>
                    <VCol cols="12">
                        <label for="title">العنوان:</label>
                        <VTextField type="text" id="title" v-model="notification.title" required>
                        </VTextField>
                    </VCol>
                    <VCol cols="12">
                        <label for="body">المحتوى:</label>
                        <VTextarea id="body" v-model="notification.body" required></VTextarea>
                    </VCol>
                    <VCol cols="12">
                        <label for="body">الى:</label>

                        <!-- Guardian Select Dropdown -->
                        <AppSelect multiple v-model="notification.user_ids" :items="guardians" item-title="name" item-value="id"
                            required />

                    </VCol>
                </VRow>
                <VBtn color="primary" class="ml-2 mt-3" type="submit">إرسال الإشعار</VBtn>
            </form>
            <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
            <div v-if="successMessage" class="success">{{ successMessage }}</div>
        </VCardText>
    </VCard>

</template>