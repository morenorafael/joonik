import { zodResolver } from '@hookform/resolvers/zod'
import {
  Alert,
  Box,
  Button,
  CircularProgress,
  Stack,
  TextField,
} from '@mui/material'
import { useForm } from 'react-hook-form'
import { z } from 'zod'
import { useCreateLocation } from '../hooks/useLocations'

const schema = z.object({
  code: z
    .string()
    .min(1, 'Requerido')
    .max(20, 'Máximo 20 caracteres')
    .regex(/^[A-Za-z0-9-_]+$/, 'Sólo letras, números, guión y guión bajo'),
  name: z.string().min(1, 'Requerido').max(120, 'Máximo 120 caracteres'),
  image: z
    .string()
    .trim()
    .url('Debe ser una URL válida')
    .max(1024, 'URL muy larga')
    .optional()
    .or(z.literal('').transform(() => undefined)),
})

type FormValues = z.infer<typeof schema>

type Props = {
  onCreated?: () => void
}

export function LocationForm({ onCreated }: Props) {
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<FormValues>({
    resolver: zodResolver(schema),
    defaultValues: { code: '', name: '', image: '' },
    mode: 'onBlur',
  })

  const createMutation = useCreateLocation()

  const onSubmit = (values: FormValues) => {
    createMutation.mutate(
      { code: values.code.trim(), name: values.name.trim(), image: values.image },
      {
        onSuccess: () => {
          reset()
          onCreated?.()
        },
      },
    )
  }

  return (
    <Box component="form" onSubmit={handleSubmit(onSubmit)} noValidate>
      <Stack spacing={2}>
        <TextField
          label="Código"
          {...register('code')}
          error={!!errors.code}
          helperText={errors.code?.message}
        />
        <TextField
          label="Nombre"
          {...register('name')}
          error={!!errors.name}
          helperText={errors.name?.message}
        />
        <TextField
          label="URL de imagen (opcional)"
          {...register('image')}
          error={!!errors.image}
          helperText={errors.image?.message}
        />

        {createMutation.isError ? (
          <Alert severity="error">
            {(createMutation.error as Error).message}
            Error al crear la sede
          </Alert>
        ) : null}

        <Box display="flex" gap={2} alignItems="center">
          <Button type="submit" variant="contained" disabled={createMutation.isPending}>
            Crear sede
          </Button>
          {createMutation.isPending && <CircularProgress size={20} />}
        </Box>
      </Stack>
    </Box>
  )
}

export default LocationForm


