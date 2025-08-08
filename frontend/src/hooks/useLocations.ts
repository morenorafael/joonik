import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { createLocation, fetchLocations } from '../services/locations'
import type {
  CreateLocationInput,
  Location,
  LocationsQuery,
  PaginatedResponse,
} from '../types/location'

const queryKeys = {
  locations: (q: LocationsQuery) => [
    'locations',
    q.page ?? 1,
    q.perPage ?? 10,
    q.name ?? '',
    q.code ?? '',
  ],
}

export function useLocations(q: LocationsQuery) {
  return useQuery<PaginatedResponse<Location>, Error, PaginatedResponse<Location>>({
    queryKey: queryKeys.locations(q),
    queryFn: () => fetchLocations(q),
    staleTime: 30_000,
    placeholderData: (previousData) => previousData,
  })
}

export function useCreateLocation() {
  const client = useQueryClient()
  return useMutation({
    mutationFn: (input: CreateLocationInput) => createLocation(input),
    onSuccess: () => {
      client.invalidateQueries({ queryKey: ['locations'] })
    },
  })
}


