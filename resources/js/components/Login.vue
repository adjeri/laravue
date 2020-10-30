<template>
	<div role="main">
		<v-card style="width: 560px; margin: 120px auto 0 auto">
			<v-form v-model="FORM.valid" @submit.prevent="login">
				<div class="headline text-center py-5">CADINTERVIEW</div>
				<v-container grid-list-md fluid>
					<v-row row wrap>
						<!---->
						<v-col cols="12">
							<v-text-field
								type="email"
								v-model="INPUT.email"
								label="Email address"
								:rules="[$root.RULES.input]"
								clearable
							></v-text-field>
						</v-col>
						<!---->
						<v-col cols="12">
							<v-text-field
								type="password"
								v-model="INPUT.password"
								label="Password"
								:rules="[$root.RULES.input]"
								clearable
							></v-text-field>
						</v-col>
						<!---->
						<v-col cols="12">
							<v-btn
								type="submit"
								:disabled="!FORM.valid"
								large
								block
								color="blue darken-2 white--text"
								><v-icon size="16" class="mr-1">fa-sign-in-alt</v-icon
								>Login</v-btn
							>
						</v-col>
					</v-row>
				</v-container>
			</v-form>
		</v-card>
	</div>
</template>

<script>
	// Vue JS Modules
	import Vue from "vue";
	import axios from "axios";

	// Vue JS Components

	// Main
	export default {
		data: function () {
			return {
				INPUT: {
					email: null,
					password: null,
				},
				ERROR: {},
				FORM: {},
			};
		},
		created: function () {
			if (this.$root.ME.logged) {
				// console.log("login created --- " + this.$route.fullPath);
				if (
					this.$root.ME.permissions == 1 &&
					this.$route.fullPath.includes("panel")
				) {
					// console.log("cas 1");
				} else if (this.$root.ME.permissions == 2) {
					// console.log("cas 2");

					this.$router.push("/panel/general-settings");
				} else {
					// console.log("cas 3");
					this.$router.push("/");
				}
				// this.$root.redirectAfterLogin(this.$root.ME.permissions);
			}
		},
		methods: {
			login: function () {
				const that = this;

				Vue.set(this.$root, "mask", true);
				Vue.set(this, "ERROR", {});

				axios
					.post("/api/panel/login", JSON.stringify(this.INPUT))
					.then(function (res) {
						localStorage.setItem("access_token", res.data.data.access_token);
						localStorage.setItem("permissions", res.data.data.permissions.length);
						Vue.set(that.$root.ME, "id", res.data.data.id);
						Vue.set(that.$root.ME, "token_type", res.data.data.token_type);

						axios.defaults.headers.common["Authorization"] =
							"Bearer " + res.data.data.access_token;

						that.$root.redirectAfterLogin(res.data.data.permissions.length);
					})
					.catch(function (res) {
						Vue.set(that.$root, "ME", {});
						localStorage.removeItem("access_token");
						localStorage.removeItem("permissions");
						Vue.set(that.$root, "snackbar", {
							show: true,
							color: "error",
							text: "Login failed",
						});
						Vue.set(that.$root, "mask", false);
					})
					.finally(function () {
						Vue.set(that.$root, "mask", false);
					});
			},
		},
	};
</script>
