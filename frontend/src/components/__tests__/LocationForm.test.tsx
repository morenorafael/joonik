import { render, screen, fireEvent, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { LocationForm } from '../LocationForm'

// Mock the hooks
jest.mock('../../hooks/useLocations', () => ({
  useCreateLocation: () => ({
    mutate: jest.fn(),
    isPending: false,
    isError: false,
    error: null,
  }),
}))

describe('LocationForm', () => {
  const user = userEvent.setup()

  beforeEach(() => {
    jest.clearAllMocks()
  })

  it('renders form fields correctly', () => {
    render(<LocationForm />)
    
    expect(screen.getByLabelText(/código/i)).toBeDefined()
    expect(screen.getByLabelText(/nombre/i)).toBeDefined()
    expect(screen.getByLabelText(/url de imagen/i)).toBeDefined()
    expect(screen.getByRole('button', { name: /crear sede/i })).toBeDefined()
  })

  it('shows validation errors for empty required fields', async () => {
    render(<LocationForm />)
    
    const submitButton = screen.getByRole('button', { name: /crear sede/i })
    await user.click(submitButton)
    
    await waitFor(() => {
      expect(screen.getByText(/requerido/i)).toBeDefined()
    })
  })

  it('validates code format', async () => {
    render(<LocationForm />)
    
    const codeInput = screen.getByLabelText(/código/i)
    await user.type(codeInput, 'invalid@code')
    
    fireEvent.blur(codeInput)
    
    await waitFor(() => {
      expect(screen.getByText(/sólo letras, números, guión y guión bajo/i)).toBeDefined()
    })
  })

  it('validates name length', async () => {
    render(<LocationForm />)
    
    const nameInput = screen.getByLabelText(/nombre/i)
    const longName = 'a'.repeat(121)
    await user.type(nameInput, longName)
    
    fireEvent.blur(nameInput)
    
    await waitFor(() => {
      expect(screen.getByText(/máximo 120 caracteres/i)).toBeDefined()
    })
  })

  it('validates image URL format', async () => {
    render(<LocationForm />)
    
    const imageInput = screen.getByLabelText(/url de imagen/i)
    await user.type(imageInput, 'invalid-url')
    
    fireEvent.blur(imageInput)
    
    await waitFor(() => {
      expect(screen.getByText(/debe ser una url válida/i)).toBeDefined()
    })
  })

  it('submits form with valid data', async () => {
    const mockOnCreated = jest.fn()
    render(<LocationForm onCreated={mockOnCreated} />)
    
    const codeInput = screen.getByLabelText(/código/i)
    const nameInput = screen.getByLabelText(/nombre/i)
    const imageInput = screen.getByLabelText(/url de imagen/i)
    const submitButton = screen.getByRole('button', { name: /crear sede/i })
    
    await user.type(codeInput, 'TEST-001')
    await user.type(nameInput, 'Test Location')
    await user.type(imageInput, 'https://example.com/image.jpg')
    await user.click(submitButton)
    
    await waitFor(() => {
      expect(codeInput).toHaveValue('')
      expect(nameInput).toHaveValue('')
      expect(imageInput).toHaveValue('')
    })
  })

  it('allows empty image URL', async () => {
    const mockOnCreated = jest.fn()
    render(<LocationForm onCreated={mockOnCreated} />)
    
    const codeInput = screen.getByLabelText(/código/i)
    const nameInput = screen.getByLabelText(/nombre/i)
    const submitButton = screen.getByRole('button', { name: /crear sede/i })
    
    await user.type(codeInput, 'TEST-002')
    await user.type(nameInput, 'Test Location 2')
    await user.click(submitButton)
    
    await waitFor(() => {
      expect(codeInput).toHaveValue('')
      expect(nameInput).toHaveValue('')
    })
  })
})
