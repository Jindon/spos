import axios from "axios"

export default {
    namespaced: true,
    state: () => ({
        authenticated: false,
        user: null,
        shop: null,
    }),

    getters: {
        authenticated (state) {
            return state.authenticated
        },
        user (state) {
            return state.user
        },
        shop (state) {
            return state.shop
        },
    },

    mutations: {
        SET_AUTHENTICATED (state, authenticated) {
            state.authenticated = authenticated
        },
        SET_USER (state, user) {
            state.user = user
        },
        SET_SHOP (state, shop) {
            state.shop = shop
        },
    },

    actions: {
        authenticate ({ commit }) {
            const uninterceptedAxiosInstance = axios.create()
            uninterceptedAxiosInstance.defaults.baseURL = import.meta.env.VITE_APP_API_BASE_URL
            uninterceptedAxiosInstance.defaults.withCredentials = true;
            return uninterceptedAxiosInstance.get('/api/user').then((response) => {
                commit('SET_AUTHENTICATED', true)
                commit('SET_USER', response.data.user)
                commit('SET_SHOP', response.data.shop)
            }).catch(() => {
                commit('SET_AUTHENTICATED', false)
                commit('SET_USER', null)
                commit('SET_SHOP', null)
            })
        },

        async login ({ dispatch }, credentials) {
            await axios.get('/sanctum/csrf-cookie')
            await axios.post('/login', credentials)
            await dispatch('authenticate')
        },

        async logout ({ commit }) {
            await axios.post('/logout').then(() => {
                commit('SET_AUTHENTICATED', false)
                commit('SET_USER', null)
            }).catch((error) => {
                console.log(error)
            })
        }
    }
}
