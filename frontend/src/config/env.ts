console.log(import.meta.env);

export const env = {
  apiBaseUrl: (import.meta as any).env?.VITE_API_BASE_URL ?? '',
  apiKey: (import.meta as any).env?.VITE_API_KEY ?? '',
}

export function assertEnv(): void {
  // En desarrollo, permitir valores por defecto para MSW
  if (import.meta.env.DEV) {
    if (!env.apiBaseUrl) {
      console.warn('VITE_API_BASE_URL no configurado, usando MSW para desarrollo')
    }
    if (!env.apiKey) {
      console.warn('VITE_API_KEY no configurado, usando MSW para desarrollo')
    }
    return
  }
  
  if (!env.apiBaseUrl) {
    throw new Error('VITE_API_BASE_URL no está configurado')
  }
  if (!env.apiKey) {
    throw new Error('VITE_API_KEY no está configurado')
  }
}
