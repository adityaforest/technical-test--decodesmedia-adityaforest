// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  srcDir: 'src/',
  devtools: { enabled: true },
  css: [
    '~/assets/css/main.css',
  ],
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {}
    }
  },
  imports: {
    dirs: ['stores', 'stores/**']
  },
  modules: [
    '@vueuse/nuxt',
    '@pinia/nuxt',
  ],
  pinia: {
    autoImports: [
      'defineStore',
      'storeToRefs'
    ]
  },
  build: {    
  },
  ssr: false
})
