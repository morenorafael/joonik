import { http, HttpResponse } from 'msw'

const mockLocations = [
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
  {
    id: 5,
    code: 'BR-005',
    name: 'Plaza Mayor',
    image: 'https://picsum.photos/300/200?random=5',
    created_at: '2024-01-19T16:45:00Z',
    updated_at: '2024-01-19T16:45:00Z',
  },
  {
    id: 6,
    code: 'BR-006',
    name: 'Zona Industrial',
    image: null,
    created_at: '2024-01-20T08:30:00Z',
    updated_at: '2024-01-20T08:30:00Z',
  },
  {
    id: 7,
    code: 'BR-007',
    name: 'Centro Histórico',
    image: 'https://picsum.photos/300/200?random=7',
    created_at: '2024-01-21T12:00:00Z',
    updated_at: '2024-01-21T12:00:00Z',
  },
  {
    id: 8,
    code: 'BR-008',
    name: 'Parque Comercial',
    image: 'https://picsum.photos/300/200?random=8',
    created_at: '2024-01-22T15:30:00Z',
    updated_at: '2024-01-22T15:30:00Z',
  },
]

export const handlers = [
  http.get('/api/v1/locations', ({ request }) => {
    const url = new URL(request.url)
    const page = parseInt(url.searchParams.get('page') || '1')
    const perPage = parseInt(url.searchParams.get('per_page') || '8')
    const name = url.searchParams.get('name') || ''
    const code = url.searchParams.get('code') || ''

    let filteredLocations = mockLocations

    if (name) {
      filteredLocations = filteredLocations.filter(loc =>
        loc.name.toLowerCase().includes(name.toLowerCase())
      )
    }

    if (code) {
      filteredLocations = filteredLocations.filter(loc =>
        loc.code.toLowerCase().includes(code.toLowerCase())
      )
    }

    const total = filteredLocations.length
    const lastPage = Math.ceil(total / perPage)
    const start = (page - 1) * perPage
    const end = start + perPage
    const paginatedLocations = filteredLocations.slice(start, end)

    return HttpResponse.json({
      data: paginatedLocations,
      meta: {
        current_page: page,
        per_page: perPage,
        total,
        last_page: lastPage,
      },
    })
  }),

  http.post('/api/v1/locations', async ({ request }) => {
    const body = await request.json()
    
    // Simular validación
    if (!body.code || !body.name) {
      return HttpResponse.json(
        { error: { message: 'Código y nombre son requeridos', code: 'E_INVALID_PARAM' } },
        { status: 400 }
      )
    }

    if (mockLocations.some(loc => loc.code === body.code)) {
      return HttpResponse.json(
        { error: { message: 'El código ya existe', code: 'E_DUPLICATE_CODE' } },
        { status: 409 }
      )
    }

    const newLocation = {
      id: mockLocations.length + 1,
      code: body.code,
      name: body.name,
      image: body.image || null,
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
    }

    mockLocations.push(newLocation)

    return HttpResponse.json(newLocation, { status: 201 })
  }),
]
