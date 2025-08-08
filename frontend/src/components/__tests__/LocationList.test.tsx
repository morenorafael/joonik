import { render, screen, fireEvent } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { LocationList } from '../LocationList'

// Mock the hooks
jest.mock('../../hooks/useLocations', () => ({
  useLocations: () => ({
    data: null,
    isLoading: false,
    isError: false,
    error: null,
    isFetching: false,
  }),
}))

describe('LocationList', () => {
  const user = userEvent.setup()

  beforeEach(() => {
    jest.clearAllMocks()
  })

  it('renders title and filter inputs', () => {
    render(<LocationList />)
    
    expect(screen.getByText('Sedes')).toBeDefined()
    expect(screen.getByLabelText(/filtrar por nombre/i)).toBeDefined()
    expect(screen.getByLabelText(/filtrar por código/i)).toBeDefined()
  })

  it('displays mock locations when data is available', () => {
    render(<LocationList />)
    
    expect(screen.getByText('Sede Principal')).toBeInTheDocument()
    expect(screen.getByText('Sucursal Norte')).toBeInTheDocument()
    expect(screen.getByText('Sucursal Sur')).toBeInTheDocument()
    expect(screen.getByText('Centro Comercial')).toBeInTheDocument()
  })

  it('shows loading state', () => {
    jest.doMock('../../hooks/useLocations', () => ({
      useLocations: () => ({
        data: null,
        isLoading: true,
        isError: false,
        error: null,
        isFetching: false,
      }),
    }))
    
    render(<LocationList />)
    expect(screen.getByRole('progressbar')).toBeInTheDocument()
  })

  it('shows error state', () => {
    jest.doMock('../../hooks/useLocations', () => ({
      useLocations: () => ({
        data: null,
        isLoading: false,
        isError: true,
        error: new Error('Test error'),
        isFetching: false,
      }),
    }))
    
    render(<LocationList />)
    expect(screen.getByText(/error al cargar las sedes/i)).toBeInTheDocument()
  })

  it('shows empty state when no locations', () => {
    // Mock empty data
    const mockEmptyData = {
      data: [],
      meta: {
        current_page: 1,
        per_page: 8,
        total: 0,
        last_page: 1,
      },
    }
    
    jest.doMock('../../hooks/useLocations', () => ({
      useLocations: () => ({
        data: mockEmptyData,
        isLoading: false,
        isError: false,
        error: null,
        isFetching: false,
      }),
    }))
    
    render(<LocationList />)
    expect(screen.getByText(/no se encontraron sedes/i)).toBeInTheDocument()
  })

  it('updates filters when typing', async () => {
    render(<LocationList />)
    
    const nameFilter = screen.getByLabelText(/filtrar por nombre/i)
    const codeFilter = screen.getByLabelText(/filtrar por código/i)
    
    await user.type(nameFilter, 'test')
    await user.type(codeFilter, 'TEST')
    
    expect(nameFilter).toHaveValue('test')
    expect(codeFilter).toHaveValue('TEST')
  })

  it('displays pagination when multiple pages', () => {
    render(<LocationList />)
    
    // Mock data with multiple pages
    const mockPaginatedData = {
      data: [
        {
          id: 1,
          code: 'HQ-001',
          name: 'Sede Principal',
          image: 'https://picsum.photos/300/200?random=1',
          created_at: '2024-01-15T10:00:00Z',
          updated_at: '2024-01-15T10:00:00Z',
        },
      ],
      meta: {
        current_page: 1,
        per_page: 8,
        total: 20,
        last_page: 3,
      },
    }
    
    jest.doMock('../../hooks/useLocations', () => ({
      useLocations: () => ({
        data: mockPaginatedData,
        isLoading: false,
        isError: false,
        error: null,
        isFetching: false,
      }),
    }))
    
    render(<LocationList />)
    expect(screen.getByRole('navigation')).toBeInTheDocument()
  })

  it('displays location cards in grid layout', () => {
    render(<LocationList />)
    
    const locationCards = screen.getAllByText(/sede|sucursal|centro/i)
    expect(locationCards.length).toBeGreaterThan(0)
  })
})
