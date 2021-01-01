<template>
	<div role="main" class="fill-height" fluid>
		<v-container fluid class="mt-10">
			<v-row>
				<img :src="DATA.image" style="height: auto; width: 100%" />
			</v-row>
		</v-container>
		<v-container fluid class="px-5 mt-10 mb-2">
			<v-row>
				<v-col class="text-h6 font-weight-bolder" cols="12">
					Academic Consulting Inc.
				</v-col>
				<v-col cols="12">
					<span class="font-weight-bold text-decoration-underline">
						Toll Free </span
					>: 00-00-00-00
				</v-col>
				<v-col cols="12">
					<span class="font-weight-bold text-decoration-underline"> Email </span
					>: {{ DATA.email }}
				</v-col>
			</v-row>
			<v-form v-model="INPUT.valid" ref="FORM" @submit.prevent="sendMail()">
				<v-row class="justify-center text-center">
					<v-col cols="10"> NAME: * </v-col>
					<v-col cols="10">
						<v-text-field
							single-line
							filled
							v-model="INPUT.name"
							:rules="[$root.RULES.input]"
						></v-text-field>
					</v-col>
					<v-col cols="10"> EMAIL ADDRESS: * </v-col>
					<v-col cols="10">
						<v-text-field
							single-line
							filled
							v-model="INPUT.email"
							:rules="$root.RULES.email"
						></v-text-field>
					</v-col>
					<v-col cols="10"> HOW CAN WE HELP YOU? * </v-col>
					<v-col cols="10">
						<v-textarea
							filled
							v-model="INPUT.message"
							:rules="[$root.RULES.input]"
						></v-textarea>
					</v-col>
				</v-row>
				<v-row>
					<v-col class="text-right">
						<v-btn @click="resetfields()" depressed> RESET </v-btn>
					</v-col>
					<v-col>
						<v-btn @click="sendMail()" depressed> SUBMIT </v-btn>
					</v-col>
				</v-row>
				<v-row class="text-center">
					<v-col cols="12">
						<span class="font-weight-bold text-decoration-underline">
							Note:
						</span>
						If you are having difficulties with our contact us form above, send
						us an email to {{ DATA.email }} (copy & paste the email address)
					</v-col>
				</v-row>
			</v-form>
		</v-container>
	</div>
</template>

<script>
	import Vue from "vue";
	import axios from "axios";

	export default {
        name: "Contact",
		metaInfo() {
			return {
				meta: [
					{ name: this.DATA.meta_title, content: this.DATA.meta_description },
				],
			};
		},
		data() {
			return {
				DATA: {
					meta_title: "",
					meta_description: "",
				},
				INPUT: {
					name: "",
					email: "",
					message: "",
				},
			};
		},
		methods: {
			sendMail: function () {
				var that = this;

				if (this.$refs.FORM.validate()) {
					Vue.set(this.$root, "mask", true);

					var formdata = new FormData();
					for (var i in this.INPUT) {
						if (this.INPUT[i] != null) {
							formdata.append(i, this.INPUT[i]);
						}
					}

					axios
						.post("/api/sendmail", formdata)
						.then(function (res) {
							Vue.set(that.$root, "snackbar", {
								show: true,
								color: "success",
								text: "Mail successfully sent",
							});
						})
						.catch(function (res) {
							Vue.set(that.$root, "snackbar", {
								show: true,
								color: "error",
								text: "The mail could not have been sent",
							});
						})
						.finally(function () {
							Vue.set(that.$root, "mask", false);
						});
				}
			},
			resetfields: function () {
				Vue.set(this.INPUT, "name", "");
				Vue.set(this.INPUT, "email", "");
				Vue.set(this.INPUT, "message", "");
			},
			loadData: function () {
				let that = this;
				axios.get("/api/contact").then((res) => {
					let data = res.data.data;
					// console.log("response --- " + res.data.data);
					Vue.set(that.DATA, "image", that.$root.imagePath + data.image);
					Vue.set(that.DATA, "email", data.email);
					Vue.set(that.DATA, "meta_title", data.meta_title);
                    Vue.set(that.DATA, "meta_description", data.meta_description);

                    if (data.meta_title != "") {
						Vue.set(that.DATA, "meta_title", data.meta_title);
						Vue.set(that.DATA, "meta_description", data.meta_description);
					}
				});
			},
		},
		mounted() {
			// console.log("Component mounted.");
			this.loadData();
		},
	};
</script>
