<script setup lang="ts">
import { ref, computed, onMounted, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useCurrentTime } from '@/composables/useCurentTime'
import { LMap, LTileLayer, LMarker, LCircleMarker } from '@vue-leaflet/vue-leaflet'
import 'leaflet/dist/leaflet.css'
import profilImage from '@/assets/images/profile-picture-5.webp'
import { useAbsen } from '@/composables/useAbsensi'
import useGeolocation from '@/composables/useGeolocation'
import AttendanceLoading from '@/components/AttendanceLoading.vue'

const zoom = ref(17)
const router = useRouter()

const { check, datang, pulang, dataDatang, dataPulang, pengaturan, loading, error, response } =
  useAbsen()

const geolocation = useGeolocation()
const isLoading = ref<boolean>(true)

const alert = reactive<{
  title: string
  color: string
  show: boolean
  message: string
}>({
  title: '',
  color: '',
  show: false,
  message: '',
})

const { currentTime, currentLongDate } = useCurrentTime()

const formattedTime = computed(() => {
  const d = currentTime.value
  const hours = d.getHours().toString().padStart(2, '0')
  const minutes = d.getMinutes().toString().padStart(2, '0')
  const seconds = d.getSeconds().toString().padStart(2, '0')
  return `${hours}:${minutes}:${seconds}`
})

const formatedDate = computed(() => {
  return currentLongDate
})

const doAbsen = async () => {
  if (dataDatang.value?.tipe_absen == null) {
    await datang({
      latitude: geolocation.latitude.value,
      longitude: geolocation.longitude.value,
    })
  } else if (dataDatang.value.tipe_absen == 1) {
    await pulang({
      latitude: geolocation.latitude.value,
      longitude: geolocation.longitude.value,
    })
  }

  // console.log(error.status)

  if (error.status === null) {
    alert.title = 'Informasi'
    alert.message = response.message
    alert.color = '#075A87'
    alert.show = true
  } else {
    alert.title = 'Peringatan'
    alert.message = error.message
    alert.color = '#ee0a24'
    alert.show = true
  }
}

const confirmAlert = () => {
  alert.show = false
  alert.color = ''
  alert.title = ''
  alert.message = ''
}

const goToDashboard = () => router.push('/dashboard')

const status = computed(() => {
  if (dataPulang.value?.tipe_absen === null || dataPulang.value!.tipe_absen === 1) {
    return true
  }

  return false
})

onMounted(async () => {
  // getLocationRequest()
  await geolocation.getLocationRequest()
  await check()

  isLoading.value = false
})
</script>

<template>
  <attendance-loading v-if="isLoading" />
  <div class="relative" v-else>
    <van-nav-bar title="Absensi" left-text="Kembali" left-arrow @click-left="goToDashboard" />
    <div class="h-[calc(100vh-45px)] w-full">
      <div class="h-1/2">
        <l-map
          ref="map"
          v-model:zoom="zoom"
          :center="[geolocation.latitude.value, geolocation.longitude.value]"
          :use-global-leaflet="false"
          :options="{ attributionControl: false }"
        >
          <l-tile-layer
            url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            attribution="&copy; OpenStreetMap contributors"
          />
          <l-marker :lat-lng="[geolocation.latitude.value, geolocation.longitude.value]" />
          <l-circle-marker :lat-lng="[pengaturan!.latitude, pengaturan!.longitude]" :radius="100" />
        </l-map>
      </div>
      <div class="h-1/2 bg-gray-100 p-3 space-y-2">
        <div class="flex items-center gap-2.5 px-4 py-2 bg-sky-500 rounded-xl shadow-lg text-white">
          <img class="w-10 h-10 rounded-full" :src="profilImage" alt="" />
          <div class="font-medium text-heading">
            <div class="font-semibold">Raditya Wahyu Sasono</div>
            <div class="text-xs font-normal text-body">Status <strong>siswa</strong></div>
          </div>
        </div>
        <div class="bg-white py-2 px-4 rounded-lg shadow-sm">
          <div class="pt-2">
            <div class="flex justify-between mb-2">
              <p>Datang</p>
              <p>{{ dataDatang!.jam ?? 'belum absen' }}</p>
            </div>
            <div class="flex justify-between text-xs text-gray-500">
              <p>Tanggal</p>
              <p class="font-medium">{{ dataDatang!.tgl_absen ?? 'belum absen' }}</p>
            </div>
            <div class="flex justify-between text-xs text-gray-500">
              <p>Jarak</p>
              <p class="font-medium">{{ dataDatang!.jarak ?? 'belum absen' }}</p>
            </div>
          </div>
          <van-divider />
          <div class="pb-2">
            <div class="flex justify-between mb-2">
              <p>Pulang</p>
              <p>
                {{
                  dataPulang?.jam === null
                    ? 'belum absen'
                    : dataPulang?.tipe_absen !== 5
                      ? dataPulang?.jam
                      : 'belum absen'
                }}
              </p>
            </div>
            <div class="flex justify-between text-xs text-gray-500">
              <p>Tanggal</p>
              <p class="font-medium">
                {{
                  dataPulang?.tgl_absen === null
                    ? 'belum absen'
                    : dataPulang?.tipe_absen !== 5
                      ? dataPulang?.tgl_absen
                      : 'belum absen'
                }}
              </p>
            </div>
            <div class="flex justify-between text-xs text-gray-500">
              <p>Jarak</p>
              <p class="font-medium">
                {{
                  dataPulang?.jarak === null
                    ? 'belum absen'
                    : dataPulang?.tipe_absen !== 5
                      ? dataPulang?.jarak
                      : 'belum absen'
                }}
              </p>
            </div>
          </div>
        </div>
        <div class="flex items-center justify-center bg-white px-2 py-1 rounded-lg">
          <div class="flex-1 flex-col flex justify-center items-center">
            <p class="text-sm font-semibold text-slate-600">{{ formatedDate }}</p>
            <p class="text-xl font-medium text-sky-600">{{ formattedTime }}</p>
          </div>
          <div class="flex-1">
            <van-button
              v-if="status"
              type="primary"
              size="large"
              :color="dataDatang?.tipe_absen == 1 ? 'rgb(56 189 248)' : 'rgb(22 78 99)'"
              block
              v-on:click="doAbsen"
              :loading="loading"
              loading-text="Proses absen..."
            >
              <template v-if="dataDatang?.tipe_absen == 1"> Pulang </template>
              <template v-else> Datang </template>
            </van-button>
            <div class="text-center bg-gray-300 text-gray-800 py-2 rounded-sm" v-else>
              Absen berakhir
            </div>
          </div>
        </div>
      </div>
    </div>
    <van-dialog
      :show="alert.show"
      :title="alert.title"
      show-confirm-button
      :show-cancel-button="false"
      confirm-button-text="Mengerti"
      @confirm="confirmAlert"
      :message="alert.message"
      message-align="center"
      theme="round-button"
      :confirm-button-color="alert.color"
    />
    <van-toast
      :show="!isLoading && loading"
      type="loading"
      loading-type="spinner"
      message="Kirim absen"
      overlay
      icon-size="40"
    />
  </div>
</template>
