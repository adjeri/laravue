<template>
	<div role="main" class="fill-height" fluid>
		<v-form v-model="INPUT.valid" ref="FORM" @submit.prevent="saveSettings()">
			<v-container class="pa-5" fluid grid-list-md>
				<v-row>
					<v-col cols="8">
						<v-text-field
							readonly
							solo
							rounded
							@click="pickFile('logo')"
							prepend-inner-icon="fas fa-file-image"
							label="Company logo"
							filled
							prepend-icon="fas fa-camera"
						></v-text-field>
						<input
							type="file"
							class="d-none"
							ref="logo"
							accept="image/*"
							@change="onFilePicked($event)"
						/>
					</v-col>
					<v-col cols="4">
						<div v-ripple>
							<v-img :src="$root.INPUT.logo" cover width="100" height="100">
								<!-- <v-tooltip bottom>
									<template #activator="{ on }">
										<v-btn
											fab
											x-small
											v-on="on"
											@click.stop.prevent="$root.rmFile('logo')"
											color="red"
										>
											<v-icon color="white"> fas fa-minus </v-icon>
										</v-btn>
									</template>
								</v-tooltip> -->
							</v-img>
						</div>
					</v-col>
				</v-row>
				<v-row>
					<v-col cols="12">
						<v-switch
							false-value="0"
							true-value="1"
							v-model="INPUT.index_pages"
							label="Index pages"
						></v-switch>
					</v-col>
				</v-row>
				<v-row>
					<v-col cols="12">
						<v-text-field
							v-model="INPUT.google_analytics"
							label="Google analytics"
							outlined
						></v-text-field>
					</v-col>
				</v-row>
				<v-row>
					<v-col cols="12">
						<v-text-field
							v-model="INPUT.facebook_ads"
							label="Facebook pixel ads"
							outlined
						></v-text-field>
					</v-col>
				</v-row>
				<v-row>
					<v-col cols="12">
						<v-btn
							@click="saveSettings"
							:disabled="!INPUT.valid"
							block
							color="primary"
						>
							Save
						</v-btn>
					</v-col>
				</v-row>
			</v-container>
		</v-form>
	</div>
</template>

<script>
	import Vue from "vue";
	import axios from "axios";

	export default {
		data() {
			return {
				imageInput: {},
				INPUT: {
					valid: false,
					logo: null,
					index_pages: true,
					google_analytics: "",
					facebook_ads: "",
				},
				ERROR: {},
			};
		},
		methods: {
			pickFile: function (ref) {
				this.$refs[ref].click();
			},
			onFilePicked: function (event) {
				let _URL = window.URL || window.webkitURL;
				this.imageInput = event.target.files[0];
				let img = new Image();

				img.src = _URL.createObjectURL(this.imageInput);

				Vue.set(this.$root.INPUT, "logo", img.src);
				Vue.set(this.INPUT, "logo", this.imageInput);
			},
			saveSettings: function () {
				var that = this;

				if (this.$refs.FORM.validate()) {
					Vue.set(this.$root, "mask", true);
					Vue.set(this, "ERROR", {});

					var formdata = new FormData();
					for (var i in this.INPUT) {
						if (this.INPUT[i] != null) {
							formdata.append(i, this.INPUT[i]);
						}
					}

					axios
						.post("/api/panel/setting", formdata)
						.then(function (res) {
							Vue.set(that.$root, "snackbar", {
								show: true,
								color: "success",
								text: "Settings saved",
							});
						})
						.catch(function (res) {
							if (res && res.response && res.response.status === 403) {
								Vue.set(that.$root, "snackbar", {
									show: true,
									color: "blue",
									text: "Please login as an administrator",
								});
							} else {
								Vue.set(that.$root, "snackbar", {
									show: true,
									color: "error",
									text: "Please check your input",
								});
							}
						})
						.finally(function () {
							Vue.set(that.$root, "mask", false);
						});
				}
			},
			loadSettings: function () {
				let that = this;
				Vue.set(this.$root, "mask", true);
				axios
					.get("/api/setting")
					.then(async res => {
						let _URL = window.URL || window.webkitURL;
						let data = res.data.data;
						let image = that.$root.imagePath + data.logo;
						let blob = await fetch(image).then((r) => r.blob());

						Vue.set(that.$root.INPUT, "logo", that.$root.imagePath + data.logo);
						Vue.set(that.INPUT, "index_pages", data.index_pages.toString());
						Vue.set(that.INPUT, "google_analytics", data.google_analytics);
						Vue.set(that.INPUT, "facebook_ads", data.facebook_ads);
						Vue.set(that.INPUT, "logo", blob);

					})
					.catch(function (err) {
						Vue.set(that.$root, "snackbar", {
							show: true,
							color: "error",
							text: "Data could not be retrieved",
						});
					})
					.finally(function () {
						Vue.set(that.$root, "mask", false);
					});
			},
		},
		mounted() {
			console.log("Component mounted.");
			this.loadSettings();
		},
		created() {},
	};
</script>
