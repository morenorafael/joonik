import {
  Alert,
  Box,
  CircularProgress,
  Pagination,
  Stack,
  TextField,
  Typography,
} from '@mui/material'
import { useMemo, useState } from 'react'
import { useLocations } from '../hooks/useLocations'
import { getErrorMessage } from '../api/axios'
import { LocationCard } from './LocationCard'
import type { PaginatedResponse, Location } from '../types/location'

const DEFAULT_PER_PAGE = 8

export function LocationList() {
  const [page, setPage] = useState(1)
  const [filters, setFilters] = useState({ name: '', code: '' })

  const queryParams = useMemo(
    () => ({ page, perPage: DEFAULT_PER_PAGE, name: filters.name, code: filters.code }),
    [page, filters.name, filters.code],
  )

  // Mock data para desarrollo mientras MSW no funciona
  const mockData: PaginatedResponse<Location> = {
    data: [
      {
        id: 1,
        code: 'HQ-001',
        name: 'Sede Principal',
        image: 'https://picsum.photos/300/200?random=1',
        created_at: '2024-01-15T10:00:00Z',
        updated_at: '2024-01-15T10:00:00Z',
      },
      {
        id: 2,
        code: 'BR-002',
        name: 'Sucursal Norte',
        image: 'https://picsum.photos/300/200?random=2',
        created_at: '2024-01-16T11:30:00Z',
        updated_at: '2024-01-16T11:30:00Z',
      },
      {
        id: 3,
        code: 'BR-003',
        name: 'Sucursal Sur',
        image: null,
        created_at: '2024-01-17T09:15:00Z',
        updated_at: '2024-01-17T09:15:00Z',
      },
      {
        id: 4,
        code: 'BR-004',
        name: 'Centro Comercial',
        image: 'https://picsum.photos/300/200?random=4',
        created_at: '2024-01-18T14:20:00Z',
        updated_at: '2024-01-18T14:20:00Z',
      },
    ],
    meta: {
      current_page: 1,
      per_page: 8,
      total: 4,
      last_page: 1,
    },
  }

  const { data, isLoading, isError, error, isFetching } = useLocations(queryParams)
  const locationsData = data || mockData

  const handleFilterChange = (key: 'name' | 'code') =>
    (e: React.ChangeEvent<HTMLInputElement>) => {
      setPage(1)
      setFilters((prev) => ({ ...prev, [key]: e.target.value }))
    }

  return (
    <Stack spacing={2}>
      <Typography variant="h5">Sedes</Typography>

      <Stack direction={{ xs: 'column', sm: 'row' }} spacing={2}>
        <TextField label="Filtrar por nombre" value={filters.name} onChange={handleFilterChange('name')} />
        <TextField label="Filtrar por código" value={filters.code} onChange={handleFilterChange('code')} />
      </Stack>

      {isLoading ? (
        <Box display="flex" justifyContent="center" py={4}>
          <CircularProgress />
        </Box>
      ) : isError ? (
        <Alert severity="error">
          {getErrorMessage(error)}
          Error al cargar las sedes
        </Alert>
      ) : locationsData.data.length > 0 ? (
        <>
          <Box sx={{ display: 'grid', gridTemplateColumns: { xs: '1fr', sm: 'repeat(2, 1fr)', md: 'repeat(3, 1fr)', lg: 'repeat(4, 1fr)' }, gap: 2 }}>
            {locationsData.data.map((loc) => (
              <Box key={loc.id}>
                <LocationCard location={loc} />
              </Box>
            ))}
          </Box>
          <Box display="flex" justifyContent="center" py={2}>
            <Pagination
              count={locationsData.meta.last_page}
              page={page}
              onChange={(_e, value) => setPage(value)}
              color="primary"
            />
          </Box>
          {isFetching && (
            <Box display="flex" justifyContent="center" py={1}>
              <CircularProgress size={18} />
            </Box>
          )}
        </>
      ) : (
        <Alert severity="info">No se encontraron sedes</Alert>
      )}
    </Stack>
  )
}

export default LocationList


