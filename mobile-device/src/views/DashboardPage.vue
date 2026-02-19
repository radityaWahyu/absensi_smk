<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue'
import { Lineicons } from '@lineiconshq/vue-lineicons'
import { ArrowRightEndOnRectangleIcon } from '@heroicons/vue/16/solid'
import {
  Home2Outlined,
  MapMarker1Stroke,
  Gear1Outlined,
  FilePencilOutlined,
} from '@lineiconshq/free-icons'
import { Preferences } from '@capacitor/preferences'
import { useAbsen } from '@/composables/useAbsensi'
import { useAuth } from '@/composables/useAuth'
import AttendanceBox from '@/components/AttendanceBox.vue'
import profilImage from '@/assets/images/profile-picture-5.webp'

type TUser = {
  nama: string
  jenisUser: string
  jenisKelamin: string
}

const user = reactive<TUser>({
  nama: '',
  jenisUser: '',
  jenisKelamin: '',
})
const isLoading = ref<boolean>(true)
const logoutLoading = ref<boolean>(false)

const { check, dataDatang, dataPulang, loading } = useAbsen()
const auth = useAuth()

const getUser = async () => {
  isLoading.value = true
  const { value: nama } = await Preferences.get({ key: 'nama' })
  const { value: jenis_user } = await Preferences.get({ key: 'jenis_user' })
  const { value: jenis_kelamin } = await Preferences.get({ key: 'jenis_kelamin' })

  user.nama = nama!
  user.jenisUser = jenis_user!
  user.jenisKelamin = jenis_kelamin!

  isLoading.value = false
}

const doLogout = () => {
  logoutLoading.value = true
  auth.doLogout()
  logoutLoading.value = false
}

onMounted(() => {
  getUser()
  check()
})
</script>
<template>
  <div class="bg-gray-100 h-screen relative">
    <div class="h-2/5 bg-sky-700"></div>
    <div class="absolute z-50 h-screen overflow-y-scroll w-full top-0 p-5">
      <div class="flex justify-between items-end">
        <div class="flex items-center gap-2.5 px-2 py-1 text-white mt-3">
          <img class="w-10 h-10 rounded-full" :src="profilImage" alt="" />
          <div class="font-medium text-heading">
            <div>
              <template v-if="isLoading">
                <van-skeleton-paragraph round />
              </template>
              <template v-else>
                {{ user.nama }}
              </template>
            </div>
            <div class="text-sm font-normal text-body">
              <template v-if="isLoading">
                <van-skeleton-paragraph round />
              </template>
              <template v-else>
                Status <strong>{{ user.jenisUser }}</strong>
              </template>
            </div>
          </div>
        </div>
        <button
          @click="doLogout"
          class="rounded-full bg-sky-800 px-2 py-2 text-center border border-sky-900"
        >
          <ArrowRightEndOnRectangleIcon class="size-5 text-sky-200" />
        </button>
      </div>
      <div class="mt-3">
        <h3 class="text-white text-2xl font-light">
          Selamat datang <strong class="block">Raditya Wahyu Sasono</strong>
        </h3>
      </div>
      <attendance-box :loading="loading" :datang="dataDatang" :pulang="dataPulang!" />
      <div class="mt-6 space-y-2">
        <p class="text-gray-500 text-sm">Absensi minggu ini</p>
        <van-cell-group>
          <van-cell title="Senin" size="large">
            <template #value>
              <span class="text-sky-500">Absen Full</span>
            </template>
          </van-cell>
          <van-cell title="Selasa" size="large">
            <template #value>
              <span class="text-red-500">Tidak absen</span>
            </template>
          </van-cell>
          <van-cell title="Rabu" size="large">
            <template #value>
              <span class="text-sky-500">Absen Full</span>
            </template>
          </van-cell>
          <van-cell title="Kamis" size="large">
            <template #value>
              <span class="text-yellow-500">Absen datang</span>
            </template>
          </van-cell>
          <van-cell title="Jumat" size="large">
            <template #value>
              <span class="text-green-500">Sakit</span>
            </template>
          </van-cell>
        </van-cell-group>
      </div>
    </div>
    <van-tabbar z-index="80" active-color="blue" fixed border safe-area-inset-bottom>
      <van-tabbar-item>
        <span>Profil</span>
        <template #icon>
          <Lineicons :icon="Home2Outlined" :size="22" color="blue" :stroke-width="1.5" />
        </template>
      </van-tabbar-item>
      <van-tabbar-item to="/attendance">
        <span>Absensi</span>
        <template #icon>
          <Lineicons :icon="MapMarker1Stroke" :size="22" :stroke-width="1.5" />
        </template>
      </van-tabbar-item>
      <van-tabbar-item>
        <span>Ijin</span>
        <template #icon>
          <Lineicons :icon="FilePencilOutlined" :size="22" :stroke-width="1.5" />
        </template>
      </van-tabbar-item>
      <van-tabbar-item>
        <span>Pengaturan</span>
        <template #icon>
          <Lineicons :icon="Gear1Outlined" :size="22" :stroke-width="1.5" />
        </template>
      </van-tabbar-item>
    </van-tabbar>
    <van-toast
      z-index="85"
      :show="logoutLoading"
      type="loading"
      loading-type="spinner"
      message="Logout..."
      overlay
      icon-size="40"
    />
  </div>
</template>
<style scoped>
.van-tabbar {
  box-shadow: 0 0 4px 1px rgb(209 213 219);
}
</style>
