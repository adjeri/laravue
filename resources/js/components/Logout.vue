<template>
	<div role="main">
	</div>
</template>

<script>
// Vue JS Modules
import Vue from 'vue';
import axios from 'axios';


// Main
export default {
	data: function(){
		return {
		}
	}
	,methods: {
		 logout: function(){
			Vue.set(this.$root, 'ME', {});
			localStorage.removeItem('access_token');
			localStorage.removeItem('permissions');
			axios.defaults.headers.common['Authorization']	= 'Bearer ';
			this.$router.replace('/login');
			window.scrollTo(0, 0);
		}
	}
	,mounted: function(){
		this.logout();
	}
}
</script>
