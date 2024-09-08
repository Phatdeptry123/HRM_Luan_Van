import { createApp } from 'vue'
import { createPinia } from 'pinia'
import FontAwesomeIcon from '@/plugins/fontAwesome'
import setupVeeValidate from '@/plugins/veeValidate'
import { createVfm } from 'vue-final-modal'
import VueSweetalert2 from 'vue-sweetalert2';
import Swal from 'sweetalert2'; // Import Swal từ sweetalert2
import 'sweetalert2/dist/sweetalert2.min.css';

import './assets/main.css'
import './index.css'
import 'vue-final-modal/style.css'
import App from './App.vue'
import router from './router'

const vfm = createVfm()
const app = createApp(App)
setupVeeValidate(app)
app.component('font-awesome-icon', FontAwesomeIcon)
app.use(createPinia())
app.use(router)
app.use(vfm)
app.use(VueSweetalert2);

// Thiết lập Swal toàn cục
app.config.globalProperties.$swal = Swal;

app.mount('#app')
