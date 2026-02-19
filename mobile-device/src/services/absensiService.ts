import apiClient from "@/config/api";
import type { ICoordinate } from "@/type/global";

export function absenDatang(payload: ICoordinate) {
  return apiClient.post('/absensi/datang', {
    latitude: payload.latitude,
    longitude: payload.longitude
  })
}

export function absenPulang(payload: ICoordinate) {
  return apiClient.post('/absensi/pulang', {
    latitude: payload.latitude,
    longitude: payload.longitude
  })
}

export function statusAbsen() {
  return apiClient.get(`/absensi`)
}


