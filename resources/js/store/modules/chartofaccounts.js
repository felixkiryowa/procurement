import axios from 'axios';

const state = {
    ledger_sub_categories: {
        data: []
    },
    isLoading: true
}

const actions = {
    fetchSubCategories({
        commit
    }) {
        commit('startLoader');
        return new Promise((resolve, reject) => {
            axios.get('/get/ledger/categories').then(response => {
                resolve(response);
                commit('setAllLedgerCatagories', response.data);
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
    allLedgerCategories: (state) => state.ledger_sub_categories
}


const mutations = {
    setAllLedgerCatagories: (state, responseData) => (state.ledger_sub_categories = responseData),

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