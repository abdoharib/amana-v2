export const setupGuards = router => {
  router.beforeEach((to, from) => {
    // Allow navigation to public routes
    if (to.meta.public) return

    // Check if the user is logged in
    const isLoggedIn = !!(useCookie('userData').value && useCookie('accessToken').value)

    // Prevent redirect loop: Don't redirect if already on the login page
    if (!isLoggedIn && to.name !== 'login') {
      return {
        name: 'login',
        query: { redirect: to.fullPath },
      }
    }

    // Redirect logged-in users away from auth pages (e.g., login) to home
    if (isLoggedIn && to.meta.unauthenticatedOnly) {
      return { name: '' }
    }

    // Allow navigation
    return true
  })
}
