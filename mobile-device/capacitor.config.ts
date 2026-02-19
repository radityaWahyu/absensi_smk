import type { CapacitorConfig } from '@capacitor/cli'

const config: CapacitorConfig = {
  appId: 'com.radit.app',
  appName: 'mobile-device',
  webDir: 'dist',
  server: {
    "url": "http://172.26.169.22:5173/",
    "cleartext": true,
    "androidScheme": "http"
  }
}

export default config
