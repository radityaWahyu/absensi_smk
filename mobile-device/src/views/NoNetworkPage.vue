<script setup lang="ts">
import { App } from '@capacitor/app'

const exitApp = () => {
  App.exitApp()
}
</script>
<template>
  <van-nav-bar title="Peringatan" />
  <van-empty image="network" image-size="260">
    <template #description>
      <div class="h-[150px] flex flex-col justify-between text-center">
        <p class="text-center font-medium text-gray-500">
          Device tidak terkoneksi dengan internet. Silahkan mengkoneksikan terlebih dahulu ke
          internet
        </p>
        <van-button type="primary" color="rgb(56 189 248)" round @click="exitApp">
          Tutup aplikasi
        </van-button>
      </div>
    </template>
  </van-empty>
</template>
