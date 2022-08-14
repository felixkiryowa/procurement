import axios from 'axios';

const state = {
    orders: {
        data: []
    },
    isLoading: true
}

const actions = {
    fetchMadeOrders({
        commit
    }) {
        commit('startLoader');
        return new Promise((resolve, reject) => {
            axios.get('/get/all/made/orders').then(response => {
                resolve(response);
                commit('setAllOrders', response.data);
                commit('stopLoader');
            }).catch(error => {
                reject(error);
                commit('stopLoader');
            })
        });
    },

    fetchMoreMadeOrders({
        commit
    }, data) {
        commit('startLoader')
        return new Promise((resolve, reject) => {
            axios.get(data.linkUrl + data.page).then(response => {
                resolve(response);
                commit('setAllOrders', response.data);
                commit('stopLoader');
            }).catch(error => {
                reject(error);
                commit('stopLoader');
            });
        })
    },

    searchForOrder({
        commit
    }, query) {
        commit('startLoader')
        return new Promise((resolve, reject) => {
            axios.get('/search/product?q=' + query).then((response) => {
                resolve(response);
                commit('setAllOrders', response.data);
                commit('stopLoader');
            }).catch((error) => {
                reject(error);
                commit('stopLoader');
            })
        })
    },

    showLoader({
        commit
    }) {
        commit('startLoader');
    },

    hideLoader({
        commit
    }) {
        commit('stopLoader');
    }
}



const getters = {
    allOrders: (state) => state.orders
}


const mutations = {
    setAllOrders: (state, responseData) => (state.orders = responseData),

    startLoader: state => {
        state.isLoading = true;
    },

    stopLoader: state => {
        state.isLoading = false;
    }
}


export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}