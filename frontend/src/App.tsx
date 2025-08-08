import { Container, Divider, Paper, Stack, Typography } from '@mui/material'
import LocationList from './components/LocationList'
import LocationForm from './components/LocationForm'

function App() {
  return (
    <Container maxWidth="lg" sx={{ py: 4 }}>
      <Stack spacing={4}>
        <Typography variant="h4" component="h1">
          Gestión de Sedes
        </Typography>

        <Paper variant="outlined" sx={{ p: 2 }}>
          <Typography variant="h6" gutterBottom>
            Crear nueva sede
          </Typography>
          <LocationForm />
        </Paper>

        <Divider />

        <Paper variant="outlined" sx={{ p: 2 }}>
          <LocationList />
        </Paper>
      </Stack>
    </Container>
  )
}

export default App
