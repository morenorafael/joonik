import axios, { AxiosError } from 'axios'
import { env, assertEnv } from '../config/env'

assertEnv()

export const http = axios.create({
  // baseURL: env.apiBaseUrl || (import.meta.env.DEV ? '' : env.apiBaseUrl),
  baseURL: 'http://localhost:8000',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    // ...(env.apiKey && { 'x-api-key': env.apiKey }),
    'x-api-key': 'Cb61A53tJ8Ke6blKIIUo5vJ4t95Gicd31ydbYMOi04941e6c',
  },
  // Timeouts defensivos
  timeout: 15_000,
})

export type ApiErrorShape = {
  error?: {
    message?: string
    code?: string
  }
}

export function getErrorMessage(error: unknown): string {
  if (axios.isAxiosError(error)) {
    const axiosErr = error as AxiosError<ApiErrorShape>
    const apiMsg = axiosErr.response?.data?.error?.message
    if (apiMsg) return apiMsg
    return axiosErr.message
  }
  if (error instanceof Error) return error.message
  return 'Ocurrió un error inesperado'
}

http.interceptors.response.use(
  (response) => response,
  (error) => {
    // Normalizamos el error para consumo en UI
    return Promise.reject(error)
  },
)


