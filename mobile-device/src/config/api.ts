import axios from "axios";
import { Preferences } from "@capacitor/preferences";
import { refreshToken } from "@/services/authService";
import router from "@/router";


const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    "Accept": "application/json",
  }
})


apiClient.interceptors.request.use(
  async (config) => {
    const { value: token } = await Preferences.get({ key: 'access_token' })
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error)
  }
)

apiClient.interceptors.response.use(
  (response) => response,
  async (error) => {

    const originalRequest = error.config

    if (error.response.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true

      try {
        const { value: refresh_token } = await Preferences.get({ key: 'refresh_token' })

        const res = await refreshToken(refresh_token);
        console.log(res)

        if (res.status === 200) {

          await Preferences.set({
            key: 'access_token',
            value: res.data.access_token
          })

          await Preferences.set({
            key: 'refresh_token',
            value: res.data.refresh_token
          })

          originalRequest.headers.Authorization = `Bearer ${res.data.access_token}`

          return apiClient(originalRequest)
        }
      } catch (refreshError) {
        await Preferences.clear();
        router.replace('/login')
        return Promise.reject(refreshError)
      }
    }
    return Promise.reject(error)
  }
)

export default apiClient
