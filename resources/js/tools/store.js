
import Vuex from 'vuex';
const store = new Vuex.Store({
    state: {
        companies_filter: {
            enterprise_id: '',
            company: '',
        }
    },
    mutations: {
        companiesFilter (state, payload) {
            state.companies_filter[payload.field] = payload.value;
        }
    }
});
export default store;
