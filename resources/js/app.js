/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import ConceptsEdit from "./components/hierarchy/ConceptsEdit";
import VueProgressBar from 'vue-progressbar';
import VueRouter from 'vue-router';
import { VuejsDatatableFactory } from 'vuejs-datatable';

require('./bootstrap');
import swal from 'sweetalert2'
window.Vue = require('vue');
Vue.use(VuejsDatatableFactory);

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '2px'
})
Vue.use(VueRouter);
window.swal = swal

const Toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
})

window.toast = Toast;
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.use(require('vuex'));

Vue.component('simple-list', require('./components/SimpleList').default);
Vue.component('categories-list', require('./components/CategoriesList').default);

Vue.component('concepts-list', require('./components/hierarchy/ConceptsList').default);
Vue.component('concepts-edit', require('./components/hierarchy/ConceptsEdit').default);
Vue.component('enterprises-list-wrapper', require('./components/hierarchy/EnterprisesListWrapper').default);
Vue.component('enterprises-edit', require('./components/hierarchy/EnterprisesEdit').default);
Vue.component('companies-list-wrapper', require('./components/hierarchy/CompaniesListWrapper').default);
Vue.component('companies-edit', require('./components/hierarchy/CompaniesEdit').default);
Vue.component('service-groups-list', require('./components/hierarchy/ServiceGroupsList').default);
Vue.component('service-groups-edit', require('./components/hierarchy/ServiceGroupsEdit').default);
Vue.component('entity-edit-form', require('./components/EntityEditForm').default);

Vue.component('parse-denny-finance-main', require('./components/other/ParseDennyFinanceMain').default);
Vue.component('check-dsr-data-form', require('./components/other/CheckDsrDataForm').default);
Vue.component('gl-history-form', require('./components/other/GlHistoryForm').default);

Vue.component('users-list', require('./components/acl/UsersList').default);
Vue.component('users-edit', require('./components/acl/UsersEdit').default);

Vue.component('test-date-picker', require('./components/other/TestDatePicker').default);
Vue.component('intacct-export-form', require('./components/other/IntacctExportForm').default);
Vue.component('internal-documents-categories-selector', require('./components/other/InternalDocumentsCategoriesSelector').default);

Vue.component('employees-list-wrapper', require('./components/EmployeesListWrapper').default);
Vue.component('employees-edit', require('./components/EmployeesEdit').default);

/**
 * Quickbook Components
 */
Vue.component('gl-history-export', require('./components/quickbook/GlHistoryExport').default);
Vue.component('MultiSelect', require('./components/quickbook/Multiselect').default);
Vue.component('jib-invoice-import', require('./components/quickbook/JibInvoiceImport').default);
Vue.component('mbm-invoice-import', require('./components/quickbook/MbmInvoiceImport').default);
Vue.component('jib-validate-import-list', require('./components/quickbook/JibValidateImportList').default);
Vue.component('jib-imported-list', require('./components/quickbook/JibImportedList').default);
Vue.component('mbm-validate-import-list', require('./components/quickbook/MbmValidateImportList').default);
Vue.component('mbm-imported-list', require('./components/quickbook/MbmImportedList').default);
Vue.component('ready-import-list', require('./components/quickbook/ReadyImportList').default);



/**
 * Employee Documents Download
 */
Vue.component('documents-transfer', require('./components/isolved/AddDocumentsTransfer').default);
Vue.component('documents-transfer-list-wrapper', require('./components/isolved/DocumentsTransferListwrapper').default);
Vue.component('documents-transfer-details-wrapper', require('./components/isolved/DocumentsTransferDetailsWrapper').default);




// Employees documents
// Vue.component('documents-list-wrapper', require('./components/isolved/DocumentsListWrapper').default);
// Vue.component('documents-edit', require('./components/isolved/DocumentsEdit').default);
Vue.component('documents-transfer', require('./components/isolved/AddDocumentsTransfer').default);
Vue.component('documents-transfer-list-wrapper', require('./components/isolved/DocumentsTransferListwrapper').default);
Vue.component('documents-transfer-details-wrapper', require('./components/isolved/DocumentsTransferDetailsWrapper').default);

// Vue.component('documents-uploaded-wrapper', require('./components/isolved/DocumentsUploadedWrapper').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
