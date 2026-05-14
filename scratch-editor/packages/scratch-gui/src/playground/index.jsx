// Polyfills
import 'es6-object-assign/auto';
import 'core-js/fn/array/includes';
import 'core-js/fn/promise/finally';
import 'intl'; // For Safari 9

import React from 'react';
import ReactDomClient from 'react-dom/client';

import AppStateHOC from '../lib/app-state-hoc.jsx';
import BrowserModalComponent from '../components/browser-modal/browser-modal.jsx';
import supportedBrowser from '../lib/supported-browser';

import styles from './index.css';

const appTarget = document.createElement('div');
appTarget.className = styles.app;
document.body.appendChild(appTarget);

if (supportedBrowser()) {
    // require needed here to avoid importing unsupported browser-crashing code
    // at the top level
    require('./render-gui.jsx').default(appTarget);

} else {
    BrowserModalComponent.setAppElement(appTarget);
    const WrappedBrowserModalComponent = AppStateHOC(BrowserModalComponent, true /* localesOnly */);
    const handleBack = () => {};
    const root = ReactDomClient.createRoot(appTarget);
    // eslint-disable-next-line react/jsx-no-bind
    root.render(<WrappedBrowserModalComponent onBack={handleBack} />);
}

// 🔥 SCRATCH SAVE LISTENER
window.addEventListener("message", async function (event) {

    if (event.data && event.data.type === "GET_SCRATCH_CODE") {

        console.log("✅ GET_SCRATCH_CODE received");

        try {

            // 🔥 IMPORTANT: VM access
            const vm = window.vm;

            if (!vm) {
                console.log("❌ VM not ready");
                return;
            }

            const blob = await vm.saveProjectSb3();

            console.log("📦 Blob generated");

            const reader = new FileReader();

            reader.onload = function () {

                console.log("📤 Sending to parent");

                event.source.postMessage({
                    type: "SCRATCH_CODE",
                    code: reader.result
                }, event.origin);

            };

            reader.readAsDataURL(blob);

        } catch (e) {
            console.error("❌ Scratch save error:", e);
        }
    }
});
window.addEventListener("load", () => {

  const params = new URLSearchParams(window.location.search);
  const projectUrl = params.get("project_url");

  if (!projectUrl) {
    console.log("No project URL");
    return;
  }

  const loadProject = async () => {
    try {
      console.log("Fetching:", projectUrl);

      const response = await fetch(decodeURIComponent(projectUrl));

      if (!response.ok) {
        console.log("❌ Fetch failed:", response.status);
        return;
      }

      const buffer = await response.arrayBuffer();

      if (window.vm) {
        await window.vm.loadProject(buffer);
        console.log("✅ Project loaded");
      } else {
        setTimeout(loadProject, 500);
      }

    } catch (e) {
      console.error("❌ Load error:", e);
    }
  };

  loadProject();
});
// window.addEventListener("load", () => {

//   const params = new URLSearchParams(window.location.search);
//   const projectUrl = params.get("project_url");

//   if (!projectUrl) return;

//   console.log("Loading project:", projectUrl);

//   const loadProject = async () => {
//     try {
//       const response = await fetch(decodeURIComponent(projectUrl));
//       const buffer = await response.arrayBuffer();

//       if (window.vm) {
//         await window.vm.loadProject(buffer);
//         console.log("✅ Project loaded successfully");
//       } else {
//         console.log("⏳ Waiting for VM...");
//         setTimeout(loadProject, 500);
//       }

//     } catch (err) {
//       console.error("❌ Error loading project:", err);
//     }
//   };

//   loadProject();
// });
