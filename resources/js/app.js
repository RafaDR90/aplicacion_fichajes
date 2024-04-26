import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Applayout from './Layouts/AppLayout.vue';
import { initializeApp } from "firebase/app";
import { getStorage } from "firebase/storage";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const firebaseConfig = {
    apiKey: "AIzaSyB1rVTliyMh_DmSV4fVmavO_Tok3bvIus4",
    authDomain: "fichaje-storage.firebaseapp.com",
    projectId: "fichaje-storage",
    storageBucket: "fichaje-storage.appspot.com",
    messagingSenderId: "23179215093",
    appId: "1:23179215093:web:eacd68b694bf0b76f585bc",
    measurementId: "G-661S7DGE72"
};

const app = initializeApp(firebaseConfig);
const storage = getStorage(app);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h(Applayout, {
                page: props.page,
                isAuthenticated: props.page && props.page.props ? props.page.props.user !== null : false
            }, [h(App, props)])
        })
            .directive('focus', {
                mounted(el) {
                    el.focus();
                },
            })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
