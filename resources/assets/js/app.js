
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const sale = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/sale.vue'], () => {
    resolve(require('./components/sale.vue'))
  })
}

const salereport = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/salereport.vue'], () => {
    resolve(require('./components/salereport.vue'))
  })
}

const product = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/product.vue'], () => {
    resolve(require('./components/product.vue'))
  })
}

const material = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/material.vue'], () => {
    resolve(require('./components/material.vue'))
  })
}

const materialin = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/materialin.vue'], () => {
    resolve(require('./components/materialin.vue'))
  })
}

const materialout = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/materialout.vue'], () => {
    resolve(require('./components/materialout.vue'))
  })
}

const materialinventory = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/materialinventory.vue'], () => {
    resolve(require('./components/materialinventory.vue'))
  })
}

const materialinventoryspending = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/materialinventoryspending.vue'], () => {
    resolve(require('./components/materialinventoryspending.vue'))
  })
}

const orderobject = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/orderobject.vue'], () => {
    resolve(require('./components/orderobject.vue'))
  })
}

const order = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/order.vue'], () => {
    resolve(require('./components/order.vue'))
  })
}

const orderbill = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/orderbill.vue'], () => {
    resolve(require('./components/orderbill.vue'))
  })
}

const orderhistoryinit = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/orderhistoryinit.vue'], () => {
    resolve(require('./components/orderhistoryinit.vue'))
  })
}

const orderhistoryreview = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/orderhistoryreview.vue'], () => {
    resolve(require('./components/orderhistoryreview.vue'))
  })
}

const bill = resolve => {
  // require.ensure is Webpack's special syntax for a code-split point.
  require.ensure(['./components/bill.vue'], () => {
    resolve(require('./components/bill.vue'))
  })
}

//Vue.component('example', require('./components/Example.vue'));
Vue.component('sale', sale);
Vue.component('salereport', salereport);
Vue.component('product', product);
Vue.component('material', material);
Vue.component('materialin', materialin);
Vue.component('materialout', materialout);
Vue.component('materialinventory', materialinventory);
Vue.component('materialinventoryspending', materialinventoryspending);
Vue.component('orderobject', orderobject);
Vue.component('order', order);
Vue.component('orderbill', orderbill);
Vue.component('orderhistoryinit', orderhistoryinit);
Vue.component('orderhistoryreview', orderhistoryreview);
Vue.component('bill', bill);

var global = {error: false};

const app = new Vue({
    el: '#app',
    data() {
    	return {
    		loading: false,
    		errorMessage: '',
    		global,
    	}
    },
    
    created() {
		var self = this;
		axios.interceptors.request.use(function (config) {
		    // Do something before request is sent
			self.loading = true;
		    return config;
		 }, function (error) {
		    // Do something with request error
			self.loading = false;  
		    return Promise.reject(error);
		 });

		// Add a response interceptor
		axios.interceptors.response.use(function (response) {
		    // Do something with response data
			self.loading = false;
		    return response;
		 }, function (error) {
			self.global.error = true;
		 	var errorString = error + 'test';
			if (errorString.indexOf && errorString.indexOf('status code 401') > -1) {
				self.$snotify.error('Đã hết phiên đăng nhập', 'Lỗi', {
				  timeout: 3000,
				  position: 'leftTop',
				  showProgressBar: false,
				  closeOnClick: false,
				  pauseOnHover: false
				});
				setTimeout(function() {
					window.location.href = '/login';
				}, 10);
			} else if (errorString.indexOf && errorString.indexOf('status code 403') > -1) {
				self.errorMessage = 'Bạn không có quyền truy cập chức năng này';
		 	} else {
		 		console.log(error);
				self.errorMessage = 'Lỗi hệ thống';
			}
		    // Do something with response error
			self.loading = false;
		    return Promise.reject(error);
		 });

	},	
});
