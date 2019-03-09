
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap-sass');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });

window.moment = require('moment');
window.accounting = require('accounting');

window.notifyTimeout = 1000;

window.Vue = require('vue');

import MaskedInput from 'vue-masked-input';
Vue.component('masked-input', MaskedInput);

import BillMaskedInput from './components/gui/billmaskedinput.vue';
Vue.component('bill-masked-input', BillMaskedInput);

require('./vue-strap-lang.js');

import { 
	datepicker, accordion, panel
} from 'vue-strap';
Vue.component('vue-datepicker', datepicker);
Vue.component('vue-accordion', accordion);
Vue.component('vue-panel', panel);

import Snotify from 'vue-snotify';
Vue.use(Snotify);

import Spinner from 'vue-simple-spinner';
Vue.component('vue-spinner', Spinner);

window.productGroupNames = {
	BEVERAGES: 'Beverages',
	BUBBLE_TEA: 'Bubble Tea',
	DESSERTS: 'Desserts',
	APPETIZERS: 'Appetizers',
	PHO: 'Pho',
	GRILLED_SELECTIONS: 'Grilled Selections',
	SANDWICH: 'Sandwich',
	BEST_WORK_SELECTIONS: 'Best Work Selections',
	FRIED_RICE: 'Fried Rice',
	ADD_ON: 'Add On'
};

window.materialGroupNames = {
	THUC_UONG: 'Drinks',
	TUOI: 'Fresh',
	KHAC: 'Other',
};

window.unitNames = {
	KG: 'Kg',
	GR: 'Gr',
	CAI: 'Item',
	CUON: 'Roll',
	CHAI: 'Bottle',
	THUNG: 'Box',
	BICH: 'Bag',
	OZ: 'Oz',
	CHUC: 'Dozen',
	ONG: 'Tube',
	LIT: 'Liter',
	GOI: 'Package',
	LB: 'Pound',
	BUN: 'Bunch'
};

window.orderObjectGroupNames = {
	BAN: 'Table',
	KHU_VUC: 'Area',
	KHACH_LE: 'To Go',
	CUSTOM: 'Custom',
};

window.vuetrapFrontendFormat = 'dd/MM/yyyy';
window.frontendFormat = 'DD/MM/YYYY';
window.backendFormat = 'YYYY-MM-DD';
window.backendDateTimeFormat = 'YYYY-MM-DD HH:mm:ss';
window.frontendDateFormat = function(date) {
	if (date) {
		return moment(date, [backendFormat]).format(frontendFormat);
	}
	return '';
}
window.backendDateFormat = function(date) {
	if (date) {
		return moment(date, [frontendFormat]).format(backendFormat);
	}
	return '';
}
window.today = moment().format(frontendFormat);
window.moneyFormat = function(money) {
	return accounting.formatNumber(money, 3);
}
window.masks = {
		currency: {
			mask (value) {
				if (value) {
					return value.toLocaleString();
				} else {
					return 0;
				}
		    },
		    unmask (value) {
		    	value = parseFloat(value.replace(/[^\d\.]/g, ""));
		      	return isNaN(value) ? 0 : value;
			},
		},
}