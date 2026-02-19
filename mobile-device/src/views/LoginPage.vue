<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { InformationCircleIcon } from '@heroicons/vue/16/solid'
import { Lineicons } from '@lineiconshq/vue-lineicons'
import { MapMarker5Outlined } from '@lineiconshq/free-icons'
import { useAuth } from '@/composables/useAuth'

const router = useRouter()

const username = ref('')
const password = ref('')

const alert = reactive<{
  show: boolean
  message: string
}>({
  show: false,
  message: '',
})

const { loading, doLogin: login, error, user } = useAuth()

const confirmAlert = () => {
  alert.show = false
  alert.message = ''
}

const doLogin = async () => {
  await login(username.value, password.value)

  if (error.status !== null) {
    // console.log(error)

    alert.message = error.message
    alert.show = true
  } else if (user.value !== null) {
    router.push('/dashboard')
    console.log('berhasil login')
  }
}
</script>
<template>
  <div class="h-screen w-full flex flex-col bg-gradient-to-t from-cyan-600 to-cyan-700">
    <div class="flex-none h-1/2 flex flex-col items-center justify-center gap-6">
      <div>
        <div class="bg-white/20 w-full h-full p-4 rounded-2xl">
          <Lineicons
            :icon="MapMarker5Outlined"
            :size="40"
            :stroke-width="1.5"
            class="text-yellow-400"
          />
        </div>
      </div>
      <div class="text-center">
        <h3 class="text-yellow-400 text-xl font-bold">My Attendance</h3>
        <h3 class="text-sky-50 text-sm font-regular">masukkan username dan password anda.</h3>
      </div>
    </div>
    <div
      class="bg-white flex-1 w-full rounded-t-3xl flex flex-col justify-start px-4 space-y-4 py-6 shadow-[1px_-8px_10px_-3px_rgba(0,0,0,0.1)]"
    >
      <div
        class="px-4 py-4 text-xs text-gray-700 bg-sky-200 rounded-2xl flex gap-4 items-center shadow-sm"
      >
        <div class="p-2 bg-sky-600/30 rounded-full">
          <InformationCircleIcon class="size-6 text-gray-50" />
        </div>
        <p class="">
          <strong>Perhatian : </strong>Pastikan anda telah mengaktifkan fitur GPS pada smartphone,
          apabila belum diaktifkan silahkan di aktifkan terlebih dahulu.
        </p>
      </div>
      <van-form @submit="doLogin">
        <div class="w-full space-y-2">
          <van-field
            v-model="username"
            name="username"
            label="Username :"
            placeholder="input username"
            :rules="[{ required: true, message: 'username harus diisi' }]"
            label-align="top"
            label-class="font-regular"
            clearable
            class="rounded-xl border ring-inset ring-0 ring-gray-200"
            style="background-color: rgb(243 244 246)"
          />
          <van-field
            v-model="password"
            type="password"
            name="password"
            label="Password :"
            placeholder="input password..."
            :rules="[{ required: true, message: 'password harus diisi' }]"
            label-align="top"
            clearable
            label-class="font-regular"
            class="rounded-xl border ring-inset ring-0 ring-gray-200"
            style="background-color: rgb(243 244 246)"
          />
        </div>
        <div class="w-full pt-3">
          <van-button
            native-type="submit"
            type="primary"
            size="large"
            color="rgb(56 189 248)"
            block
            round
          >
            Masuk sistem
          </van-button>
        </div>
      </van-form>
    </div>
    <van-dialog
      :show="alert.show"
      title="Peringatan"
      show-confirm-button
      :show-cancel-button="false"
      confirm-button-text="Mengerti"
      @confirm="confirmAlert"
      :message="alert.message"
      message-align="center"
      theme="round-button"
    />
    <van-toast
      :show="loading"
      type="loading"
      loading-type="spinner"
      message="Autentikasi..."
      overlay
      icon-size="40"
    />
  </div>
</template>
