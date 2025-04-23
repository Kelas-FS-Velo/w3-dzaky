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

        <div>
          <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
          <input
            type="password"
            name="confirm-password"
            v-model="form.confirmPassword"
            placeholder="••••••••"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
            required
          />
        </div>

        <div class="flex items-start">
          <div class="flex items-center h-5">
            <input
              v-model="form.terms"
              aria-describedby="terms"
              type="checkbox"
              class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600"
              required
            />
          </div>
          <div class="ml-3 text-sm">
            <label for="terms" class="font-light text-gray-500 dark:text-gray-300">
              I accept the
              <a href="#" class="font-medium text-primary-600 hover:underline dark:text-primary-500">
                Terms and Conditions
              </a>
            </label>
          </div>
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
  confirmPassword: '',
  terms: false,
})

const errorMessage = ref('')
const router = useRouter()  // <-- Add this to get the router instance

const submit = async () => {
  // Check if the passwords match
  if (form.value.password !== form.value.confirmPassword) {
    errorMessage.value = "Passwords do not match."
    return
  }

  // Check if the terms checkbox is checked
  if (!form.value.terms) {
    errorMessage.value = "You must accept the Terms and Conditions."
    return
  }

  // Prepare payload for registration
  const payload = {
    email: form.value.email,
    password: form.value.password,
    role: 'user',
    terms: form.value.terms,
  }

  try {
    // Send registration request to the API
    const { data, error } = await useFetch('http://127.0.0.1:8000/api/user/register', {
      method: 'POST',
      body: payload,
    })

    // Handle API errors
    if (error.value) {
      console.error(error.value)
      alert('Something went wrong.')
      return
    }

    // Access the token from the response
    const token = data?.access_token

    if (token) {
      // Store the token in localStorage
      localStorage.setItem('access_token', token)

      // Optionally set token in headers for future API calls
      // Example: useFetch.defaults.headers.common['Authorization'] = `Bearer ${token}`

      alert('Account created successfully!')

      // Redirect to /resources
      router.push('/resources')  // <-- Correct usage of router.push

      // Reset form fields
      form.value = {
        email: '',
        password: '',
        confirmPassword: '',
        terms: false,
      }
    } else {
      alert('Failed to retrieve token.')
    }
  } catch (err) {
    console.error(err)
    alert('Submission failed.')
  }
}

</script>

<style scoped>
</style>
