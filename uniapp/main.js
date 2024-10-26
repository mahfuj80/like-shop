import Vue from 'vue'
import App from './App'
import store from './store'
import { toast } from './utils/tools'
import Cache from './utils/cache'
import minxinsApp from '@/mixins/app'
import uView from "@/components/uview-ui";
Vue.prototype.$toast = toast
Vue.prototype.$Cache = Cache
Vue.config.productionTip = false

// i18n 多语言
import messages from './locale/index'
let i18nConfig = {
    locale: uni.getLocale(),
    messages
}
import VueI18n from 'vue-i18n'
Vue.use(VueI18n)
const i18n = new VueI18n(i18nConfig)

Vue.use(uView);
App.mpType = 'app'
Vue.mixin(minxinsApp);
const app = new Vue({
    i18n,
    ...App,
    store
})
app.$mount()
