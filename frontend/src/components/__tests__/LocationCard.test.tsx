import { render, screen } from '@testing-library/react'
import { LocationCard } from '../LocationCard'
import type { Location } from '../../types/location'

const mockLocation: Location = {
  id: 1,
  code: 'HQ-001',
  name: 'Sede Principal',
  image: 'https://picsum.photos/300/200?random=1',
  created_at: '2024-01-15T10:00:00Z',
  updated_at: '2024-01-15T10:00:00Z',
}

const mockLocationWithoutImage: Location = {
  id: 2,
  code: 'BR-002',
  name: 'Sucursal Norte',
  image: null,
  created_at: '2024-01-16T11:30:00Z',
  updated_at: '2024-01-16T11:30:00Z',
}

describe('LocationCard', () => {
  it('renders location information correctly', () => {
    render(<LocationCard location={mockLocation} />)
    
    expect(screen.getByText('Sede Principal')).toBeDefined()
    expect(screen.getByText('Código: HQ-001')).toBeDefined()
    expect(screen.getByText('ID: 1')).toBeDefined()
  })

  it('displays image when available', () => {
    render(<LocationCard location={mockLocation} />)
    
    const image = screen.getByAltText('Sede Principal')
    expect(image).toBeDefined()
    expect(image).toHaveAttribute('src', 'https://picsum.photos/300/200?random=1')
  })

  it('does not display image when null', () => {
    render(<LocationCard location={mockLocationWithoutImage} />)
    
    expect(screen.queryByAltText('Sucursal Norte')).not.toBeDefined()
  })

  it('renders all required location data', () => {
    render(<LocationCard location={mockLocationWithoutImage} />)
    
    expect(screen.getByText('Sucursal Norte')).toBeDefined()
    expect(screen.getByText('Código: BR-002')).toBeDefined()
    expect(screen.getByText('ID: 2')).toBeDefined()
  })
})
