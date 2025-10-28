<script setup>
import vector from '@images/avatars/Vector.png'
const router = useRouter();
const userData = useCookie('userData').value;
const logout = async () => {
  try {
    await $api(`/auth/logout`, { method: 'POST' });
    useCookie('userData').value = null;
    useCookie('accessToken').value = null;
    router.push({ name: "login" });
  } catch (error) {
    console.error("Error Logging out:", error);
  }
}
</script>

<template>
  <VBadge dot location="bottom right" offset-x="3" offset-y="3" bordered color="success">
    <VAvatar class="cursor-pointer" color="primary" variant="tonal">
      <VImg :src="vector" />

      <!-- SECTION Menu -->
      <VMenu activator="parent" width="230" location="bottom end" offset="14px">
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge dot location="bottom right" offset-x="3" offset-y="3" color="success">
                  <VAvatar color="primary" variant="tonal">
                    <VImg :src="vector" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
             {{ userData?.name }}
            </VListItemTitle>
            <VListItemSubtitle>{{ userData?.phone_number }}</VListItemSubtitle>
          </VListItem>

          <VDivider class="my-2" />

          <!-- Divider -->
          <VDivider class="my-2" />

          <!-- 👉 Logout -->
          <VListItem @click="logout">
            <template #prepend>
              <VIcon class="me-2" icon="tabler-logout" size="22" />
            </template>

            <VListItemTitle>تسجيل خروج</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>
</template>
