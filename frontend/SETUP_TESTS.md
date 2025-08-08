# Configuración de Tests

## Dependencias necesarias

Cuando tengas npm funcionando, ejecuta:

```bash
npm install --save-dev ts-jest @types/jest
```

## Archivos de test creados

- `src/components/__tests__/LocationCard.test.tsx` - Tests para LocationCard
- `src/components/__tests__/LocationForm.test.tsx` - Tests para LocationForm  
- `src/components/__tests__/LocationList.test.tsx` - Tests para LocationList
- `jest.config.js` - Configuración de Jest
- `src/setupTests.ts` - Setup para tests

## Scripts disponibles

```bash
npm test              # Ejecutar tests
npm run test:watch    # Modo watch
npm run test:coverage # Con cobertura
```

## Cobertura configurada

- Branches: 80%
- Functions: 80% 
- Lines: 80%
- Statements: 80%

## Tests implementados

### LocationCard
- Renderizado correcto de información
- Mostrar imagen cuando está disponible
- No mostrar imagen cuando es null
- Renderizar todos los datos requeridos

### LocationForm
- Renderizado de campos del formulario
- Validaciones de campos requeridos
- Validación de formato de código
- Validación de longitud de nombre
- Validación de URL de imagen
- Envío con datos válidos
- Permitir URL de imagen vacía

### LocationList
- Renderizado de título y filtros
- Mostrar ubicaciones mock
- Estados de carga
- Estados de error
- Estado vacío
- Actualización de filtros
- Paginación
- Layout en grid

## Notas

- Los tests usan mocks para hooks y dependencias externas
- Se usa @testing-library/react para testing de componentes
- Se usa user-event para simular interacciones de usuario
- Los tests están preparados para funcionar con datos mock
