package com.radit.app;

import android.provider.Settings;
import com.getcapacitor.JSObject;
import com.getcapacitor.Plugin;
import com.getcapacitor.PluginCall;
import com.getcapacitor.PluginMethod;
import com.getcapacitor.annotation.CapacitorPlugin;

@CapacitorPlugin(name = "DevSettings")
public class DevSettingsPlugin extends Plugin {
    @PluginMethod
    public void isDevModeEnabled(PluginCall call) {
        int devMode = Settings.Global.getInt(
                getContext().getContentResolver(),
                Settings.Global.DEVELOPMENT_SETTINGS_ENABLED, 0
        );
        JSObject ret = new JSObject();
        ret.put("enabled", devMode != 0);
        call.resolve(ret);
    }
}