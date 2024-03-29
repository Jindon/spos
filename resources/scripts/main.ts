import { createApp } from 'vue'
import router from '@/scripts/router'
import store from '@/scripts/store'
import App from '@/views/App.vue'
import axios from 'axios'
import vueDebounce from 'vue-debounce'
import { vfmPlugin } from 'vue-final-modal'
import  VueHtmlToPaper from '@/scripts/plugins/VueHtmlToPaper'

import Toast, { POSITION } from "vue-toastification";

import "vue-toastification/dist/index.css";
import '@/css/app.scss'

axios.defaults.baseURL = import.meta.env.VITE_APP_API_BASE_URL
axios.defaults.withCredentials = true;

store.dispatch('user/authenticate').then(() => {
    const app = createApp(App)
    app.use(router)
    app.use(store)

    app.use(VueHtmlToPaper, {
        name: '_blank',
        specs: [
          'fullscreen=yes',
          'titlebar=yes',
          'scrollbars=yes'
        ],
        styles: [
        //   'https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css',
        ],
        timeout: 500,
        autoClose: true,
        windowTitle: window.document.title, // override the window title
    })

    app.use(vueDebounce, {
        defaultTime: '350ms'
    })

    app.use(vfmPlugin({
        key: '$vfm',
        componentName: 'VueFinalModal',
        dynamicContainerName: 'ModalsContainer'
    }))

    app.use(Toast, {
        position: POSITION.TOP_RIGHT,
        timeout: 3000
    })

    app.mount('#app')
})

const UNAUTHORIZED = 401
axios.interceptors.response.use(
  response => response,
  error => {
    const {status} = error.response;
    if (status === UNAUTHORIZED) {
        location.reload(true)
    }
    return Promise.reject(error);
 })
