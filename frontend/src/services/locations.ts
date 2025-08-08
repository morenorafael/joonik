import { http } from '../api/axios'
import type {
  CreateLocationInput,
  Location,
  LocationsQuery,
  PaginatedResponse,
} from '../types/location'

export async function fetchLocations(
  query: LocationsQuery,
): Promise<PaginatedResponse<Location>> {
  const params = new URLSearchParams()
  if (query.page) params.set('page', String(query.page))
  if (query.perPage) params.set('per_page', String(query.perPage))
  if (query.name) params.set('name', query.name.trim())
  if (query.code) params.set('code', query.code.trim())

  const { data } = await http.get<PaginatedResponse<Location>>(
    `/api/v1/locations?${params.toString()}`,
  )
  return data
}

export async function createLocation(
  body: CreateLocationInput,
): Promise<Location> {
  const { data } = await http.post<Location>(`/api/v1/locations`, body)
  return data
}


