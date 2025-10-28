import { createFetch } from '@vueuse/core'
import { destr } from 'destr'

export const useApi = createFetch({
  baseUrl: import.meta.env.VITE_API_BASE_URL || '/api',
  fetchOptions: {
    headers: {
      Accept: 'application/json',
    },
  },
  options: {
    refetch: true,
    async beforeFetch({ options }) {
      const accessToken = useCookie('accessToken').value
      
      if (accessToken) {
        options.headers = {
          ...options.headers,
          Authorization: `Bearer ${accessToken}`,
        }
      }
      
      return { options }
    },
    afterFetch(ctx) {
      const { data, response } = ctx

      // Parse data if it's JSON
      let parsedData = null
      try {
        parsedData = destr(data)
      } catch (error) {
        console.error('Error parsing response:', error)
      }
      
      return { data: parsedData, response }
    },
    onFetchError(ctx) {
      const { response } = ctx

      // Check if the token is invalid (401 Unauthorized)
      if (response?.status === 401) {
        console.warn('Token expired or invalid, logging out...')

        // Remove the token and user data
        const accessToken = useCookie('accessToken')
        const userData = useCookie('userData')

        accessToken.value = null
        userData.value = null

        // Redirect to login page
        window.location.href = '/login'
      }

      return ctx // Ensure the error is still available
    },
  },
})
