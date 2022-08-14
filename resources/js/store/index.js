import Vue from 'vue';
import Vuex from 'vuex';
import beneficiaries from './modules/beneficieries';
import chartofaccounts  from './modules/chartofaccounts';


Vue.use(Vuex);


export default new Vuex.Store({
    modules: {
        beneficiaries,
        chartofaccounts,

    }
})