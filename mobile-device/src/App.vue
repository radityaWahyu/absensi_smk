<script setup lang="ts">
import { ref } from 'vue'
import { registerPlugin } from '@capacitor/core'
import { Network } from '@capacitor/network'
import { onMounted } from 'vue'
import DeveloperEnabledPage from './views/DeveloperEnabledPage.vue'
import NoNetworkPage from './views/NoNetworkPage.vue'
import LoadingPage from './components/LoadingPage.vue'

interface DevSettingPlugin {
  isDevModeEnabled(): Promise<{ enabled: boolean }>
}

const devSettings = registerPlugin<DevSettingPlugin>('DevSettings')
const isLoading = ref<boolean>(true)
const networkDisabled = ref<boolean>(false)
const developerMode = ref<boolean>(false)

const checkDevMode = async () => {
  const { enabled } = await devSettings.isDevModeEnabled()
  if (enabled) {
    developerMode.value = true
  }
}

const checkNetwork = async () => {
  const status = await Network.getStatus()

  if (!status.connected) {
    networkDisabled.value = true
  }
}

onMounted(async () => {
  await checkDevMode()
  await checkNetwork()
  isLoading.value = false
})
</script>
<template>
  <loading-page v-if="isLoading" />
  <template v-else>
    <developer-enabled-page v-if="developerMode" />
    <no-network-page v-else-if="networkDisabled" />
    <router-view v-else />
  </template>
</template>
