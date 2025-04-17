<template>
    <div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow">

      <NuxtLink to="/resources" class="inline-flex items-center text-blue-600 hover:underline mb-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Back
      </NuxtLink>

      <h1 class="text-xl font-bold mb-4">Add a New Book</h1>
  
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block font-medium">Book Title</label>
          <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" />
        </div>
  
        <div class="mb-4">
          <label class="block font-medium">Author</label>
          <input v-model="form.author" type="text" class="w-full border rounded px-3 py-2" />
        </div>
  
        <div class="mb-4">
          <label class="block font-medium mb-1">Genres</label>
          <div v-for="genre in genres" :key="genre.id" class="mb-1">
            <label class="inline-flex items-center">
              <input
                type="checkbox"
                :value="genre.id"
                v-model="form.genres"
                class="mr-2"
              />
              {{ genre.name }}
            </label>
          </div>
        </div>
  
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Submit
        </button>
      </form>
  
      <p v-if="message" class="mt-4 text-green-600">{{ message }}</p>
    </div>
  </template>
  
  <script setup lang="ts">
  const form = reactive({
    name: '',
    author: '',
    genres: [] as number[],
  })
  
  const message = ref('')
  const genres = ref<{ id: number; name: string }[]>([])
  
  onMounted(async () => {
    // Fetch genres from your Laravel API
    const { data } = await useFetch('http://127.0.0.1:8000/api/genre')
    genres.value = (data.value as any)?.data || []
  })
  
  const submit = async () => {
    try {
      const payload = {
        name: form.name,
        author: form.author,
        genres: form.genres.map(id => ({ genre_id: id })),
      }
  
      const { data, error } = await useFetch('http://127.0.0.1:8000/api/book/add', {
        method: 'POST',
        body: payload,
      })
  
      if (error.value) {
        console.error(error.value)
        alert('Something went wrong.')
        return
      }
  
      message.value = 'Book created successfully!'
      // Optionally reset form
      form.name = ''
      form.author = ''
      form.genres = []
    } catch (err) {
      console.error(err)
      alert('Submission failed.')
    }
  }
  </script>
  