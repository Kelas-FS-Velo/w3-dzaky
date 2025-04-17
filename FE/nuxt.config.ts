// https://nuxt.com/docs/api/configuration/nuxt-config
import { resolve } from "path";
import tailwindcss from "@tailwindcss/vite";



export default defineNuxtConfig({
  
  runtimeConfig: {
    public: {
      apiBase: 'http://127.0.0.1:8000/api' // or your actual API URL
    }
  },
  alias: {
    '@': resolve(__dirname, '/'),
  },
  css: [
    "~/assets/main.css"
  ],
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },
  modules: [
    '@nuxt/icon', 
    '@nuxt/scripts', 
    '@nuxt/ui'
  ],
  vite: {
    plugins: [
      tailwindcss(),
    ],
  },
})

