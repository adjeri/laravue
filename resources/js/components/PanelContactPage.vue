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
							@click="pickFile('image')"
							prepend-inner-icon="fas fa-file-image"
							label="Contact page image"
							filled
							prepend-icon="fas fa-camera"
						></v-text-field>
						<input
							required
							type="file"
							class="d-none"
							ref="image"
							accept="image/*"
							@change="onFilePicked($event)"
						/>
					</v-col>
					<v-col cols="4">
						<div v-ripple>
							<v-img :src="$root.INPUT.image" cover width="100" height="100">
								<!-- <v-tooltip bottom>
									<template #activator="{ on }">
										<v-btn
											fab
											x-small
											v-on="on"
											@click.stop.prevent="$root.rmFile('image')"
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
						<v-text-field
							v-model="INPUT.meta_title"
							label="Meta title"
							outlined
						></v-text-field>
					</v-col>
				</v-row>
				<v-row>
					<v-col cols="12">
						<v-text-field
							v-model="INPUT.meta_description"
							label="Meta description"
							outlined
						></v-text-field>
					</v-col>
				</v-row>
				<v-row>
					<v-col cols="12">
						<v-text-field
							v-model="INPUT.email"
							label="Email"
							outlined
							:rules="$root.RULES.inputEmail"
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
					image: null,
					email: "",
					meta_title: "",
					meta_description: "",
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

				Vue.set(this.$root.INPUT, "image", img.src);
				Vue.set(this.INPUT, "image", this.imageInput);
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
						.post("/api/panel/contact", formdata)
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
					.get("/api/contact")
					.then(async (res) => {
						let _URL = window.URL || window.webkitURL;
						let data = res.data.data;
						let image = that.$root.imagePath + data.image;
						let blob = await fetch(image).then((r) => r.blob());

						Vue.set(that.$root.INPUT, "image", that.$root.imagePath + data.image);
						Vue.set(that.INPUT, "email", data.email);
						Vue.set(that.INPUT, "meta_title", data.meta_title);
						Vue.set(that.INPUT, "meta_description", data.meta_description);
						Vue.set(that.INPUT, "image", blob);
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
