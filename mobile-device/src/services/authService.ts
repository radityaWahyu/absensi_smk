import axios from "axios";
import apiClient from "@/config/api";

const baseUrl = import.meta.env.VITE_API_URL
const authUrl = '/auth'

export function login(username: string, password: string, deviceId: string, deviceInfo: string) {
  return apiClient.post(`${authUrl}/login`, {
    username,
    password,
    device_id: deviceId,
    device_info: deviceInfo
  })
}

export function logout() {
  return apiClient.post(`${authUrl}/logout`)
}

export function refreshToken(refreshToken: string | null) {
  return axios.post(`${baseUrl}/auth/refresh`, {}, {
    headers: {
      Authorization: `Bearer ${refreshToken}`,
      Accept: 'application/json'
    }
  })
}




