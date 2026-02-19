import { ref } from 'vue'
import { Geolocation } from '@capacitor/geolocation'
import type { Position } from '@capacitor/geolocation'
import { App } from '@capacitor/app'
import { showDialog } from 'vant'

export default function useGeolocation() {
  const latitude = ref<number>(0)
  const longitude = ref<number>(0)
  const coords = ref<Position['coords'] | null>(null)

  const getLocationRequest = async () => {
    try {
      // 1. Check current permission status
      let permStatus = await Geolocation.checkPermissions()

      // 2. If not granted, request permission
      if (permStatus.location !== 'granted') {
        permStatus = await Geolocation.requestPermissions({ permissions: ['location'] })
      }

      // 3. If granted, get position
      if (permStatus.location === 'granted') {
        const position = await Geolocation.getCurrentPosition({
          enableHighAccuracy: true,
          timeout: 10000,
        })

        latitude.value = position.coords.latitude
        longitude.value = position.coords.longitude
        coords.value = position.coords
      } else {
        console.log('Fitur GPS tidak diaktifkan')
        showDialog({
          title: 'Peringatan',
          message: 'Silahkan aktifkan fitur GPS pada device anda.',
          theme: 'round-button',
          confirmButtonText: 'Mengerti'
        }).then(() => {
          App.exitApp()
        })
      }
    } catch (e) {
      console.log('Error getting location: ' + e)
      showDialog({
        title: 'Peringatan',
        message: 'Silahkan aktifkan fitur GPS pada device anda.',
        theme: 'round-button',
        confirmButtonText: 'Mengerti'
      }).then(() => {
        App.exitApp()
      })
    }
  }

  return {
    getLocationRequest,
    latitude,
    longitude
  }
}
