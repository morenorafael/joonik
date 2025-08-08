import { Card, CardContent, CardHeader, CardMedia, Typography } from '@mui/material'
import type { Location } from '../types/location'

type Props = {
  location: Location
}

export function LocationCard({ location }: Props) {
  return (
    <Card variant="outlined" sx={{ height: '100%' }}>
      {location.image ? (
        <CardMedia
          component="img"
          height="140"
          image={location.image}
          alt={location.name}
          sx={{ objectFit: 'cover' }}
        />
      ) : null}
      <CardHeader title={location.name} subheader={`Código: ${location.code}`} />
      <CardContent>
        <Typography variant="body2" color="text.secondary">
          ID: {location.id}
        </Typography>
      </CardContent>
    </Card>
  )
}

export default LocationCard


