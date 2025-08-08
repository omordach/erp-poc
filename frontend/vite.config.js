import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// Proxy API to Laravel dev server on 8000
export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5173,
    proxy: {
      '/api': {
        target: 'http://127.0.0.1:8000',
        changeOrigin: true
      }
    }
  }
})
