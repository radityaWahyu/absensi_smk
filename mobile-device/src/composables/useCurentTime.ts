// src/composables/useCurrentTime.ts
import { ref, onBeforeUnmount, onMounted } from 'vue'

export const useCurrentTime = () => {
  const currentTime = ref(new Date())
  const currentDate = ref(new Date())
  const currentLongDate = ref('')

  const getLongDate = () => {
    const options: Intl.DateTimeFormatOptions = {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    }
    currentLongDate.value = new Intl.DateTimeFormat('id-ID', options).format(currentDate.value)
  }
  let updateTimeInterval: number

  const updateCurrentTime = () => {
    currentTime.value = new Date()
    getLongDate()
  }

  onMounted(() => {
    updateTimeInterval = setInterval(updateCurrentTime, 1000) as unknown as number
  })

  onBeforeUnmount(() => {
    clearInterval(updateTimeInterval)
  })

  return {
    currentTime,
    currentLongDate,
  }
}
