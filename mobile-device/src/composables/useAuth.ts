import { reactive, ref } from 'vue';
import { Device } from '@capacitor/device';
import { isAxiosError } from 'axios';
import { login, logout } from '@/services/authService';
import { Preferences } from '@capacitor/preferences';
import { useRouter } from 'vue-router';
import type { IResponseLogin, IUser } from "@/type/global"

export function useAuth() {
  const user = ref<IResponseLogin | null>(null)
  const error = reactive<{
    status?: number | null,
    message: string
  }>({
    status: null,
    message: ''
  })

  const loading = ref<boolean>(false)
  const router = useRouter()

  const doLogin = async (username: string, password: string) => {
    try {
      loading.value = true

      const deviceId = await Device.getId()

      const deviceInfo = await Device.getInfo()

      const response = await login(
        username,
        password,
        deviceId.identifier,
        deviceInfo.name ?? 'not detect'
      )
      error.status = null
      error.message = ''
      user.value = response.data
      await setPreferences(response.data.data)

      // console.log(response.data)
    } catch (err) {

      if (isAxiosError(err)) {
        error.status = err.response?.status
        error.message = err.response?.data.message
      }

      user.value = null

    } finally {
      loading.value = false
    }
  }

  const doLogout = async () => {

    try {
      loading.value = true;
      await logout();

      await Preferences.clear();

      router.replace({ name: 'login' })


    } catch (err) {
      if (isAxiosError(err)) {
        error.status = err.response?.status
        error.message = err.response?.data.message
      }
    } finally {
      loading.value = false
    }
  }


  const setPreferences = async (user: IUser) => {
    await Preferences.set({
      key: 'nama',
      value: user.nama
    })
    await Preferences.set({
      key: 'jenis_user',
      value: user.jenis_user
    })

    await Preferences.set({
      key: 'access_token',
      value: user.access_token
    })

    await Preferences.set({
      key: 'refresh_token',
      value: user.refresh_token
    })
  }


  return {
    loading,
    user,
    error,
    doLogin,
    doLogout
  }
}
