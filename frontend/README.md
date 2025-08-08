# Frontend de Gestión de Sedes (React + TS + Vite + MUI)

## Configuración

1) Crear archivo `.env` (o `.env.local`) con:

```
VITE_API_BASE_URL=http://localhost:8000
VITE_API_KEY=TU_API_KEY
```

2) Instalar dependencias y ejecutar:

```
npm ci
npm run dev
```

## Estructura

- `src/config/env.ts`: lectura de variables de entorno y validación.
- `src/api/axios.ts`: cliente Axios con encabezado `x-api-key` y manejo de errores.
- `src/services/locations.ts`: funciones para listar y crear sedes.
- `src/hooks/useLocations.ts`: hooks con React Query para datos y mutaciones.
- `src/components/LocationList.tsx`: listado paginado con filtros.
- `src/components/LocationForm.tsx`: formulario con validaciones Zod.
- `src/components/LocationCard.tsx`: tarjeta de una sede.

## Scripts

- `npm run dev`: entorno de desarrollo
- `npm run build`: build de producción
- `npm run preview`: preview del build
- `npm run lint`: linting
