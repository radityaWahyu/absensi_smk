package com.radit.app;

import android.os.Bundle;

import com.getcapacitor.BridgeActivity;
import android.webkit.WebView;



public class MainActivity extends BridgeActivity {
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        registerPlugin(DevSettingsPlugin.class);
        super.onCreate(savedInstanceState);
        WebView.setWebContentsDebuggingEnabled(true);
    }


}


