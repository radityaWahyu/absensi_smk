<template>
  <div
    class="flex flex-col items-center justify-between h-screen w-screen bg-gradient-to-t from-cyan-600 to-cyan-700 py-4"
  >
    <div class="h-[5px]"></div>
    <div class="w-full">
      <div class="mb-6 animate-bounce flex flex-col justify-center items-center">
        <div
          class="relative flex items-center justify-center w-24 h-24 bg-white/20 rounded-3xl backdrop-blur-sm text-yellow-400"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="48"
            height="48"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
            <circle cx="12" cy="10" r="3" />
          </svg>
        </div>
      </div>

      <div class="text-center">
        <h1 class="text-3xl font-semibold text-yellow-400">Absensi Digital</h1>
        <p class="text-white text-sm">aplikasi monitoring absensi secara realtime dan akurat</p>
      </div>
    </div>
    <div class="pb-14">
      <van-loading type="spinner" color="#FFF" size="40" />
    </div>
  </div>
</template>
<style scoped>
/* Animasi bouncing custom jika ingin lebih halus */
@keyframes bounce {
  0%,
  100% {
    transform: translateY(-5%);
  }
  50% {
    transform: translateY(0);
  }
}
.animate-bounce {
  animation: bounce 2s infinite;
}
</style>
