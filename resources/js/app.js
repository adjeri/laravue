const debug = false;

const server = 'local'; //develop, production, local

//CSS
// require('./bootstrap');
import 'vuetify/dist/vuetify.min.css';
import '@fortawesome/fontawesome-free/css/all.css';
import '@fortawesome/fontawesome-free/js/all.js';


//Components
import Vue from 'vue';
import Vuetify from 'vuetify';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueMeta from 'vue-meta';

// window.Vue = require('vue');

Vue.use(Vuetify);
Vue.use(VueRouter);
Vue.use(VueMeta);

const vuetify = new Vuetify({});

// import App from './components/App.vue';
import App from './components/App.vue';
import home from './components/Home.vue';
import globalsettings from './components/PanelGlobalSettings.vue';
import panelhome from './components/PanelHomePage.vue';
import panelcontact from './components/PanelContactPage.vue';
import contact from './components/Contact.vue';
import loginHome from './components/Login.vue';
import logout from './components/Logout.vue';

Vue.config.productionTip = (debug) ? true : false;

// AXIOS
if (server === 'production') {
    axios.defaults.baseURL = 'http://link.com';
}
else if (server === 'develop') {
    axios.defaults.baseURL = 'http://link';
}
else {
    axios.defaults.baseURL = 'http://cdainterview.adjeri'; //change this according to your localhost configuration
}
axios.defaults.timeout = 20000;
axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.headers.get['Content-Type'] = 'application/json';
axios.defaults.headers.common.Authorization = 'Bearer ' + localStorage.getItem('access_token');

const imagePath = axios.defaults.baseURL + '/storage/';

// Router
var router = new VueRouter({
    mode: 'hash',
    routes: [
        {
            path: '/',
            component: home
        },
        {
            path: '/contact',
            component: contact
        },
        {
            path: '/panel/general-settings',
            component: globalsettings
        },
        {
            path: '/panel/home',
            component: panelhome
        },
        {
            path: '/panel/contact',
            component: panelcontact
        },
        {
            path: '/login',
            component: loginHome
        },
        {
            path: '/panel/login',
            component: loginHome
        },
        {
            path: '/logout',
            component: logout
        },
    ]
});

router.beforeEach(function (to, from, next) {
    // If it is initialized and you are logged in, check permissions and continue
    if (window.App && router.app.ME.logged) {
        window.App.loading = false;
        if (router.app.ME.permissions == 1 && to.fullPath.includes('panel')) {
            next('/login');
        } else {
            // console.log('after ==== ');
            next();
        }

    }
    // If initialized and not logged in, go to login page
    else if (window.App && !router.app.ME.logged) {

        // if (to.path != '/login') {
        //     next('/login');
        // }

        next();
    }
    else {
        setTimeout(async function () {
            await router.app.getUserData();
            if (router.app.ME.logged) {
                // console.log('still logged ==== ');

                if (router.app.ME.permissions == 1 && to.fullPath.includes('panel')) {
                    next('/login');
                }
                next();
            }
            else {

                next('/login');

            }
        }, 100);


    }

});


window.App = new Vue({
    el: '#app',
    vuetify: vuetify,
    router: router,
    data: {
        appName: 'CadInterview',
        imagePath: imagePath,
        mask: false,
        loading: true,
        loginDialog: false,
        loginWithEmail: false,
        debug: debug,
        height: window.innerHeight - 30,
        snackbar: {
            show: false,
            color: 'error',
            text: '',
            timeout: 5000
        },
        ME: {},
        INPUT: {},
        ERROR: {},
        RULES: {
            input: value => (value === null || value === undefined || value === '') ? 'Please enter a value' : true,
            inputEmail: [
                value => (value === null || value === undefined || value === '') ? 'Please enter a value' : true,
                value => (/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(value) === false) ? 'Please enter a valid email address' : true
            ],
            select: value => (value === null || value === undefined || value === '') ? 'Please select a value' : true
        },
        refresh_timer: null
    },
    methods: {
        rmFile: function (ref) {
            Vue.delete(this.INPUT, ref);
        },
        networkError: function (res, response) {
            // console.log(res, response);
            Vue.set(this.$root, 'snackbar', { show: true, color: 'error', text: 'A network error occured' });
            window.scrollTo(0, 0);
        },
        redirectAfterLogin: function (permission) {
            if (permission == 2) {
                this.$router.replace("/panel/general-settings");
            } else {
                this.$router.replace("/");
            }
        },
        getUserData: function () {
            if (localStorage.getItem('access_token')) {
                // console.log('get user data ');
                Vue.set(this.$root.ME, 'access_token', localStorage.getItem('access_token'));
                Vue.set(this.$root.ME, 'logged', true);
                Vue.set(this.$root.ME, 'permissions', localStorage.getItem('permissions'));
                axios.defaults.headers.common.Authorization = 'Bearer ' + localStorage.getItem('access_token');
                return true;
            }
            return false;
        },
    },
    mounted: function () {
        this.getUserData();
    },
    template: '<App/>',
    components: { App }
});
