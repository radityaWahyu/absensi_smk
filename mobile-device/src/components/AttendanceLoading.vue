<script setup lang="ts">
import { useRouter } from 'vue-router'

const router = useRouter()
const goToDashboard = () => router.push('/')
</script>
<template>
  <div>
    <van-nav-bar title="Absensi" left-text="Kembali" left-arrow @click-left="goToDashboard" />
    <div class="h-[calc(100vh-45px)] w-full">
      <div class="h-1/2 overflow-hidden">
        <van-skeleton-image image-size="400" animate />
      </div>
      <div class="h-1/2 bg-white p-3 space-y-3">
        <div
          class="flex items-center gap-2.5 px-4 py-4 bg-gray-200 rounded-xl shadow-sm text-white"
        >
          <van-skeleton-avatar avatar-size="40" />
          <div class="w-[90%]">
            <van-skeleton-paragraph round />
            <van-skeleton-paragraph round />
          </div>
        </div>
        <div class="bg-gray-200 py-2 px-4 rounded-lg shadow-sm">
          <div class="pt-2">
            <van-skeleton-paragraph round />
            <van-skeleton-paragraph round />
          </div>
          <van-divider />
          <div class="pb-2">
            <van-skeleton-paragraph round />
            <van-skeleton-paragraph round />
          </div>
        </div>
        <div class="flex items-center gap-3 bg-gray-200 px-3 py-3 rounded-lg">
          <div class="flex-1 flex-col flex justify-center items-center">
            <van-skeleton-paragraph round />
            <van-skeleton-paragraph round />
          </div>
          <div class="flex-1">
            <div class="h-[50px] w-full bg-gray-100 rounded-full"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
