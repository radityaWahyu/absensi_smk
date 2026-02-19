<script setup lang="ts">
import type { IResponseAbsen } from '@/type/global'

defineProps<{
  loading: boolean
  datang: IResponseAbsen | null
  pulang: IResponseAbsen | null
}>()
</script>
<template>
  <template v-if="loading">
    <div class="w-full bg-white py-2 px-4 rounded-lg shadow-md shadow-gray-200 mt-6">
      <p class="text-gray-500 text-sm">Absensi hari ini</p>
      <div class="pt-3 pb-3 space-y-2">
        <div class="flex items-center justify-center gap-4">
          <div class="w-1/2">
            <van-skeleton-paragraph round :style="{ height: '10px' }" />
          </div>
          <div class="w-1/2">
            <van-skeleton-paragraph round :style="{ height: '10px' }" />
          </div>
        </div>
        <div class="flex items-center justify-center gap-4">
          <div class="w-1/2">
            <van-skeleton-paragraph round :style="{ height: '10px' }" />
          </div>
          <div class="w-1/2">
            <van-skeleton-paragraph round :style="{ height: '10px' }" />
          </div>
        </div>
        <div class="flex items-center justify-center gap-4">
          <div class="w-1/2">
            <van-skeleton-paragraph round :style="{ height: '10px' }" />
          </div>
          <div class="w-1/2">
            <van-skeleton-paragraph round :style="{ height: '10px' }" />
          </div>
        </div>
      </div>
      <van-divider />
      <div class="pt-2 pb-3 space-y-2">
        <div class="flex items-center justify-center gap-4">
          <div class="w-1/2">
            <van-skeleton-paragraph round :style="{ height: '10px' }" />
          </div>
          <div class="w-1/2">
            <van-skeleton-paragraph round :style="{ height: '10px' }" />
          </div>
        </div>
        <div class="flex items-center justify-center gap-4">
          <div class="w-1/2">
            <van-skeleton-paragraph round :style="{ height: '10px' }" />
          </div>
          <div class="w-1/2">
            <van-skeleton-paragraph round :style="{ height: '10px' }" />
          </div>
        </div>
        <div class="flex items-center justify-center gap-4">
          <div class="w-1/2">
            <van-skeleton-paragraph round :style="{ height: '10px' }" />
          </div>
          <div class="w-1/2">
            <van-skeleton-paragraph round :style="{ height: '10px' }" />
          </div>
        </div>
      </div>
    </div>
  </template>
  <template v-else>
    <div class="w-full bg-white py-2 px-4 rounded-lg shadow-md shadow-gray-200 mt-6">
      <p class="text-gray-500 text-sm">Absensi hari ini</p>
      <div class="pt-2">
        <div class="flex items-center justify-between mb-2">
          <p>Datang</p>
          <p>{{ datang?.jam ?? 'belum absen' }}</p>
        </div>
        <div class="flex justify-between text-xs text-gray-500">
          <p>Tanggal</p>
          <p class="font-medium">{{ datang?.tgl_absen ?? 'belum absen' }}</p>
        </div>
        <div class="flex justify-between text-xs text-gray-500">
          <p>Jarak</p>
          <p class="font-medium">{{ datang?.jarak ?? 'belum absen' }}</p>
        </div>
      </div>
      <van-divider />
      <div class="pb-2">
        <div class="flex justify-between mb-2">
          <p>Pulang</p>
          <p>
            {{
              pulang?.jam === null
                ? 'belum absen'
                : pulang?.tipe_absen === 5
                  ? 'tidak absen'
                  : pulang?.jam
            }}
          </p>
        </div>
        <div class="flex justify-between text-xs text-gray-500">
          <p>Tanggal</p>
          <p class="font-medium">
            {{
              pulang?.tgl_absen === null
                ? 'belum absen'
                : pulang?.tipe_absen === 5
                  ? 'tidak absen'
                  : pulang?.tgl_absen
            }}
          </p>
        </div>
        <div class="flex justify-between text-xs text-gray-500">
          <p>Jarak</p>
          <p class="font-medium">
            {{
              pulang?.jarak === null
                ? 'belum absen'
                : pulang?.tipe_absen === 5
                  ? 'tidak absen'
                  : pulang?.jarak
            }}
          </p>
        </div>
      </div>
    </div>
  </template>
</template>
