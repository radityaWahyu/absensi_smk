import { reactive, ref } from 'vue'
import { absenDatang, absenPulang, statusAbsen } from "@/services/absensiService";
import type { IResponseAbsen, ICoordinate } from "@/type/global";
import { isAxiosError } from 'axios';

type TResponseAbsensi = {
  success: boolean,
  message: string
}


export function useAbsen() {

  const dataDatang = ref<IResponseAbsen | null>(null)
  const dataPulang = ref<IResponseAbsen | null>(null)
  const response = reactive<TResponseAbsensi>({ success: false, message: '' })
  const pengaturan = ref<ICoordinate | null>(null)

  const error = reactive<{
    status?: number | null,
    message: string
  }>({
    status: null,
    message: ''
  })

  const loading = ref<boolean>(false)

  const datang = async (payload: ICoordinate) => {
    try {
      loading.value = true
      const { data } = await absenDatang(payload)
      // console.log(response.data.data)
      response.success = data.success
      response.message = data.message
      dataDatang.value = data.data
      error.status = null
      error.message = ''

    } catch (err) {
      if (isAxiosError(err)) {
        error.status = err.response?.status
        error.message = err.response?.data.message
      }
    } finally {

      loading.value = false
    }
  }

  const pulang = async (payload: ICoordinate) => {
    try {
      loading.value = true
      const { data } = await absenPulang(payload)
      // console.log(response.data.data)
      response.success = data.success
      response.message = data.message
      dataPulang.value = data.data

      error.status = null
      error.message = ''

    } catch (err) {
      if (isAxiosError(err)) {
        error.status = err.response?.status
        error.message = err.response?.data.message
      }
    } finally {
      loading.value = false
    }
  }

  const check = async () => {
    try {
      loading.value = true
      const response = await statusAbsen()
      // console.log(response.data.data)
      dataDatang.value = {
        id_siswa: response.data.data.absensi.id_siswa,
        tgl_absen: response.data.data.absensi.tgl_absen,
        jam: response.data.data.absensi.jam_datang,
        jarak: response.data.data.absensi.jarak_datang,
        tipe_absen: response.data.data.absensi.tipe_absen
      }


      dataPulang.value = {
        id_siswa: response.data.data.absensi.id_siswa,
        tgl_absen: response.data.data.absensi.tgl_absen,
        jam: response.data.data.absensi.jam_pulang,
        jarak: response.data.data.absensi.jarak_pulang,
        tipe_absen: response.data.data.absensi.tipe_absen
      }

      pengaturan.value = response.data.data.pengaturan
      error.status = null
      error.message = ''

    } catch (err) {
      if (isAxiosError(err)) {
        error.status = err.response?.status
        error.message = err.response?.data.message
      }
    } finally {
      loading.value = false
    }
  }

  return {
    dataDatang,
    dataPulang,
    pengaturan,
    loading,
    error,
    response,
    datang,
    pulang,
    check
  }
}


