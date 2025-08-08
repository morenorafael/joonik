export type Location = {
  id: number
  code: string
  name: string
  image: string | null
  created_at: string
  updated_at: string
}

export type PaginatedResponse<T> = {
  data: T[]
  meta: {
    current_page: number
    per_page: number
    total: number
    last_page: number
  }
}

export type LocationsQuery = {
  page?: number
  perPage?: number
  name?: string
  code?: string
}

export type CreateLocationInput = {
  code: string
  name: string
  image?: string | null
}


