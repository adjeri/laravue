<template>
	<v-app light>
		<header
			class="header"
			v-show="
				!$route.fullPath.includes('panel') && !$route.fullPath.includes('login')
			"
		>
			<router-link style="position: absolute" :to="'/'" class="logo">
				<v-img
					:lazy-src="DATA.logo"
					height="100"
					width="167"
					alt="Site logo"
				></v-img>
			</router-link>
			<input class="menu-btn" type="checkbox" id="menu-btn" />
			<label class="menu-icon" for="menu-btn"
				><span class="navicon"></span
			></label>
			<ul class="menu">
				<li><router-link :to="'/'">Main</router-link></li>
				<li><a href="#">How to prepare</a></li>
				<li><a href="#">Questions</a></li>
				<li><router-link :to="'/contact'">Contact Us</router-link></li>
				<li>
					<router-link :to="'/panel/general-settings'">Panel</router-link>
				</li>
			</ul>
		</header>
		<v-navigation-drawer
			v-if="
				$route.fullPath.includes('panel') && !$route.fullPath.includes('login')
			"
			app
			v-model="drawer"
			fixed
			clipped
			width="270"
		>
			<v-list nav dense>
				<v-list-item-group
					v-model="group"
					active-class="deep-purple--text text--accent-4"
				>
					<v-list-item to="/">
						<v-list-item-title>Go to the website</v-list-item-title>
					</v-list-item>

					<v-list-item to="/panel/general-settings">
						<v-list-item-title>Global settings</v-list-item-title>
					</v-list-item>

					<v-list-item to="/panel/home">
						<v-list-item-title>Home page</v-list-item-title>
					</v-list-item>

					<v-list-item to="/panel/contact">
						<v-list-item-title>Contact page</v-list-item-title>
					</v-list-item>

					<v-list-item to="/logout">
						<v-list-item-title>Logout</v-list-item-title>
					</v-list-item>
				</v-list-item-group>
			</v-list>
		</v-navigation-drawer>
		<v-card
			color="grey lighten-4"
			flat
			tile
			v-if="
				$route.fullPath.includes('panel') && !$route.fullPath.includes('login')
			"
		>
			<v-app-bar app clipped-left fixed dense>
				<v-app-bar-nav-icon @click.stop="drawer = !drawer"
					><v-icon>fas fa-bars</v-icon></v-app-bar-nav-icon
				>

				<v-toolbar-title class="text-black"
					>Administration panel</v-toolbar-title
				>
			</v-app-bar>
		</v-card>

		<v-main>
			<v-fade-transition hide-on-leave leave-absolute>
				<router-view class="py-2 ma-auto" :key="$route.fullPath"></router-view>
			</v-fade-transition>
		</v-main>

		<v-footer
			v-if="
				$route.fullPath.includes('panel') && !$route.fullPath.includes('login')
			"
			fixed
			padless
			class="font-weight-medium py-2"
		>
			<v-col class="text-center py-0" cols="12">
				<strong>CDAINTERVIEW</strong>
				&copy; {{ new Date().getFullYear() }}
			</v-col>
		</v-footer>
		<v-footer
			v-if="
				!$route.fullPath.includes('panel') && !$route.fullPath.includes('login')
			"
			padless
			class="px-5 indigo darken-4 py-2"
		>
			<v-row>
				<v-col cols="10" class="text-left">
					<span class="text-white"
						>&copy; 2013-2016 BeMo Academic Consulting Inc. All rights
						reserved.</span
					>
					<span
						><a
							class="link"
							href="http://www.cdainterview.com/disclaimer-privacy-policy.html"
						>
							Disclaimer & Privacy Policy</a
						>
						<router-link class="link" to="/contact"> Contact Us</router-link>
					</span>
				</v-col>
				<v-col class="text-right" cols="2"
					><a
						class="mr-2"
						href="https://www.facebook.com/bemoacademicconsulting"
					>
						<v-icon size="20" color="white">fab fa-facebook-f</v-icon>
					</a>
					<a href="https://twitter.com/BeMo_AC">
						<v-icon size="20" color="white">fab fa-twitter</v-icon>
					</a>
				</v-col>
			</v-row>
		</v-footer>
		<v-snackbar
			bottom
			multi-line
			v-model="$root.snackbar.show"
			:color="$root.snackbar.color"
			:timeout="$root.snackbar.timeout"
		>
			{{ $root.snackbar.text }}
			<template v-slot:action="{ attrs }">
				<v-btn
					class="white--text"
					text
					v-bind="attrs"
					@click="$root.snackbar.show = false"
					>Close</v-btn
				>
			</template>
		</v-snackbar>
		<v-overlay :value="$root.mask">
			<v-progress-circular
				indeterminate
				size="50"
				class="primary--text"
			></v-progress-circular>
		</v-overlay>
	</v-app>
</template>

<script>
	import axios from "axios";
	import Vue from "vue";

	export default {
		name: "App",
		metaInfo() {
			return {
				meta: [
					{ name: "robots", content: this.index_pages },
				],
			};
		},
		data: function () {
			return {
				drawer: true,
				group: null,
				loading: false,
				DATA: {},
				index_pages: "noindex,nofollow",
				FORM: {},
				INPUT: {},
				ERROR: {},
			};
		},
		methods: {
			loadData: function () {
				let that = this;
				axios.get("/api/setting").then((res) => {
					let data = res.data.data;
					console.log("data ----" + data.index_pages);
					Vue.set(that.DATA, "logo", that.$root.imagePath + data.logo);
					Vue.set(that.DATA, "google_analytics", data.google_analytics);
					Vue.set(that.DATA, "facebook_ads", data.facebook_ads);

					if (data.index_pages == true) {
						Vue.set(that, "index_pages", "index,follow");
					} else {
						Vue.set(that, "index_pages", "noindex,nofollow");
					}
				});
			},
		},
		mounted() {
			this.loadData();
		},
	};
</script>

<style scoped>
	.v-application a {
		color: inherit;
		text-decoration: none;
	}
	.v-application--wrap {
		min-height: 0px;
	}
	.btn-close-dialog {
		position: absolute;
		top: 0px;
		right: 0px;
	}
	.btn-previous {
		position: absolute;
		top: 0px;
		left: 0px;
	}
</style>
