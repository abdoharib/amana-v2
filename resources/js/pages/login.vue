<script setup>
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import authV2LoginIllustrationBorderedDark from '@images/pages/auth-v2-login-illustration-bordered-dark.png'
import authV2LoginIllustrationBorderedLight from '@images/pages/auth-v2-login-illustration-bordered-light.png'
import authV2LoginIllustrationDark from '@images/pages/auth-v2-login-illustration-dark.png'
import authV2LoginIllustrationLight from '@images/pages/auth-v2-login-illustration-light.png'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'

definePage({ meta: { layout: 'blank' ,unauthenticatedOnly: true} })

const form = ref({
  phone_number: '',
  password: '',
})
const errorMessage = ref();
const isPasswordVisible = ref(false)
const refVForm = ref();
const route = useRoute()
const router = useRouter()
const authThemeImg = useGenerateImageVariant(authV2LoginIllustrationLight, authV2LoginIllustrationDark, authV2LoginIllustrationBorderedLight, authV2LoginIllustrationBorderedDark, true)
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)


const errors = ref({
  phone_number: undefined,
  password: undefined,
})

const login = async () => {
  try {
    const res = await $api('/auth/admin/login', {
      method: 'POST',
      body: {
        phone_number: form.value.phone_number,
        password: form.value.password,
        fcm_token:'test'
      },
      onResponseError({ response }) {
        
        errors.value = response._data.errors
        errorMessage.value = response._data.message
      },
    })

    const { token, user } = res
    
    useCookie('userData').value = user
    useCookie('accessToken').value = token
    
    await nextTick(() => {
      router.replace(route.query.to ? String(route.query.to) : '/')
    })
  } catch (err) {
    console.error(err)
  }
}

const onSubmit = () => {
  refVForm.value?.validate().then(({ valid: isValid }) => {
    if (isValid)
      login()
  })
}
</script>

<template>
  <RouterLink to="/">
    <div class="auth-logo d-flex align-center gap-x-3">
      <VNodeRenderer :nodes="themeConfig.app.logo" />
      <h1 class="auth-title">
        {{ themeConfig.app.title }}
      </h1>
    </div>
  </RouterLink>

  <VRow no-gutters class="auth-wrapper bg-surface">
    <VCol md="8" class="d-none d-md-flex">
      <div class="position-relative bg-background w-100 me-0">
        <div class="d-flex align-center justify-center w-100 h-100" style="padding-inline: 6.25rem;">
          <VImg max-width="613" :src="authThemeImg" class="auth-illustration mt-16 mb-2" />
        </div>

        <img class="auth-footer-mask" :src="authThemeMask" alt="auth-footer-mask" height="280" width="100">
      </div>
    </VCol>

    <VCol cols="12" md="4" class="auth-card-v2 d-flex align-center justify-center">
      <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-4">
        <VCardText>
          <h4 class="text-h4 mb-1">
            مرحباً بك في <span class="text-capitalize"> {{ themeConfig.app.title }} </span>! 👋🏻
          </h4>
          <p class="mb-0">
            الرجاء تسجيل الدخول لتتمكن من أدارة التطبيق
          </p>
        </VCardText>
        <VCardText>
          <VAlert color="error" border="top" variant="tonal" v-if="errorMessage != undefined">
            {{ errorMessage }}
          </VAlert>
        </VCardText>
        <VCardText>
          <VForm  ref="refVForm" @submit.prevent="onSubmit">
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <AppTextField v-model="form.phone_number" autofocus label="رقم الهاتف" type="text"
                  placeholder="09XXXXXXXX"  :error-messages="errors.phone_number" />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <AppTextField v-model="form.password" label="كلمة المرور" placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"  :error-messages="errors.password" />

                <div class="d-flex align-center flex-wrap justify-space-between mt-2 mb-4">
                  <VCheckbox v-model="form.remember" label="تذكرني" />
                  <a class="text-primary ms-2 mb-1" href="#">
                    نسيت كلمة المرور؟
                  </a>
                </div>

                <VBtn block type="submit">
                  تسجيل الدخول
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>
