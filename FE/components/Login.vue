<template>
  <div class="w-full max-w-md bg-white rounded-2xl shadow-lg dark:border dark:bg-gray-800 dark:border-gray-700">
    <div class="p-6 sm:p-8 space-y-6">
      <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
        Create an account
      </h1>
      
      <form class="space-y-4" @submit.prevent="submit">
        <div>
          <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
          <input
            type="email"
            name="email"
            v-model="form.email"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
            placeholder="name@company.com"
            required
          />
        </div>

        <div>
          <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
          <input
            type="password"
            name="password"
            v-model="form.password"
            placeholder="••••••••"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
            required
          />
        </div>

        <button
          type="submit"
          class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        >
          Create an account
        </button>

        <p v-if="errorMessage" class="text-sm text-red-500">
          {{ errorMessage }}
        </p>

        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
          Already have an account?
          <a href="#" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Login here</a>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const form = ref({
  email: '',
  password: '',
})

const errorMessage = ref('')
const router = useRouter()  // <-- Add this to get the router instance

const submit = async () => {
  const payload = {
    email: form.value.email,
    password: form.value.password,
  }

  try {
    const { data, error } = await useFetch('http://127.0.0.1:8000/api/user/login', {
      method: 'POST',
      body: payload,
    })

    if (error.value) {
      console.error('API error:', error.value)
      errorMessage.value =
        error.value.data?.message || 'Invalid email or password.'
      return
    }

    const token = data.value?.access_token
    if (token) {
      localStorage.setItem('access_token', token)
      alert('Login successful!')
      router.push('/resources')  // <-- Correct usage of router.push
      form.value = { email: '', password: '' }
      errorMessage.value = ''
    } else {
      errorMessage.value = 'Login failed: token not received.'
    }
  } catch (err) {
    console.error('Network or logic error:', err)
    errorMessage.value = 'An error occurred during login.'
  }
}

</script>

<style scoped>
</style>
