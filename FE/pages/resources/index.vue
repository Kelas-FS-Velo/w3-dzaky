<template>
  <main class="mt-10">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <!-- Header and Add Book Button -->
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">Resources:</h2>
        <NuxtLink to="/Resources/create" class="bg-gray-900 text-white rounded-md px-3 py-2 text-sm font-medium">
          Add Book
        </NuxtLink>
      </div>

      <!-- Loading State -->
      <div v-if="pending" class="text-center mt-6">
        <p>Loading...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center mt-6 text-red-500">
        <p>{{ error }}</p>
      </div>

      <!-- Books Table -->
      <table v-else class="table-auto w-full border-collapse border border-gray-200 mt-6">
        <thead>
          <tr>
            <th class="border border-gray-300 px-4 py-2">ID</th>
            <th class="border border-gray-300 px-4 py-2">Book</th>
            <th class="border border-gray-300 px-4 py-2">Is available</th>
            <th class="border border-gray-300 px-4 py-2">Genres</th>
            <th class="border border-gray-300 px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="book in books.data" :key="book.id">
            <td class="border px-4 py-2">{{ book.id }}</td>
            <td class="border px-4 py-2">{{ book.title }}</td>
            <td class="border px-4 py-2">{{ book.available ? 'Yes' : 'No' }}</td>
            <td class="border px-4 py-2">
              <ul>
                <li v-for="(genre, index) in book.genres" :key="index">{{ genre || 'No Genres Available' }}</li>
              </ul>
            </td>
            <td class="border px-4 py-2">
              <NuxtLink :to="`/resources/${book.id}/update`" class="text-blue-500 inline-flex items-center">✏️</NuxtLink>
              <button @click="openModal(book)" class="modalDetailBtn text-gray-800 ml-2">👁️</button>
              <button @click="deleteBook(book.id)" class="text-red-500 inline-flex items-center ml-2">🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="books.meta" class="mt-6 flex justify-center">
        <nav aria-label="Page navigation">
          <ul class="inline-flex items-center space-x-2">
            <!-- Previous Button (Manually handled) -->
            <li>
              <button
                @click="fetchBooks(books.meta.links[0].url)"
                :disabled="!books.meta.links[0].url"
                class="px-3 py-1.5 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
              >
                &laquo; Previous
              </button>
            </li>

            <!-- Loop through page numbers, excluding the first and last link (Previous & Next) -->
            <li v-for="link in books.meta.links.slice(1, -1)" :key="link.label">
              <button
                @click="fetchBooks(link.url)"
                :disabled="link.active"
                :aria-current="link.active ? 'page' : null"
                class="px-3 py-1.5 text-sm font-medium"
                :class="{
                  'text-blue-600 bg-blue-100 border-blue-300': link.active,
                  'text-gray-500 bg-white border-gray-300 hover:bg-gray-100': !link.active
                }"
              >
                {{ link.label }}
              </button>
            </li>

            <!-- Next Button (Manually handled) -->
            <li>
              <button
                @click="fetchBooks(books.meta.links[6].url)"
                :disabled="!books.meta.links[6].url"
                class="px-3 py-1.5 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
              >
                Next &raquo;
              </button>
            </li>
          </ul>
        </nav>
      </div>


      <!-- Book detail modal -->
      <div v-if="showModal" class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-md w-80">
          <h3 class="font-semibold text-xl mb-4">Book Details</h3>
          <p><strong>Title:</strong> {{ modalData.title }}</p>
          <p><strong>Author:</strong> {{ modalData.author }}</p>
          <p><strong>Available:</strong> {{ modalData.available ? 'Yes' : 'No' }}</p>
          <p><strong>Genres:</strong></p>
          <ul class="list-disc list-inside">
            <li v-for="(genre, index) in modalData.genres" :key="index">{{ genre || 'No Genres Available' }}</li>
          </ul>

          <button @click="closeModal" class="bg-gray-900 text-white rounded-md px-3 py-2 text-sm font-medium mt-4">Close</button>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const books = ref([]);
const pending = ref(false);
const error = ref(null);

async function fetchBooks(url = 'http://127.0.0.1:8000/api/book?page=1') {
  pending.value = true;
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error('Error fetching books');
    }
    const data = await response.json();
    books.value = data;
  } catch (err) {
    error.value = err.message;
  } finally {
    pending.value = false;
  }
}
const showModal = ref(false);
const modalData = ref({
  title: '',
  author: '',
  available: false,
  genres: [{}],
});

function openModal(book) {
  modalData.value = {...book};
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

async function deleteBook(bookId) {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/book/delete/${bookId}`, {
      method: 'DELETE',
    });

    const responseData = await response.json();

    if (response.ok) {
      // After deletion, re-fetch the book list to ensure it is updated
      await fetchBooks(books.value.meta.current_page); // This will refresh the book list
    } else {
      alert(responseData.message || 'Failed to delete the book. Please try again.');
    }
  } catch (error) {
    console.error('Error deleting the book:', error);
    alert('An error occurred while trying to delete the book.');
  }
}

// Fetch books data when the component is mounted
onMounted(() => {
  fetchBooks();
});
</script>

<style scoped>
.modalDetailBtn {
  cursor: pointer;
}
.bg-gray-900 {
  background-color: #1a202c;
}
.bg-opacity-50 {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>
