export default defineNuxtRouteMiddleware((to, from) => {
    const user = useState('user')
    if (!user.value && to.path !== '/login') {
      return navigateTo('/login')
    }
  })
  