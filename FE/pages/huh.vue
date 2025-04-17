<template>
    <div>
      <h1 class="text-2xl font-bold mb-4">Posts</h1>
  
      <div v-if="error" class="text-red-500">Error: {{ error.message }}</div>
      <div v-else-if="pending">Loading...</div>
      <div v-else>
        <ul class="space-y-2">
          <li v-for="post in posts" :key="post.id" class="border p-4 rounded shadow">
            <h2 class="text-lg font-semibold">{{ post.title }}</h2>
            <p>{{ post.body }}</p>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  const { data: posts, pending, error } = await useFetch('https://jsonplaceholder.typicode.com/posts')
  </script>
  