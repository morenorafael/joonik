import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig({
  server: {
    host: '0.0.0.0', // Permite acceder desde el contenedor Docker
    port: 5173
  },
  plugins: [react()],
  envDir: '.', // Busca el .env en la carpeta frontend (donde ya está)
})
