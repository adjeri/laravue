<template>
	<div role="main" class="fill-height" fluid>
		<v-container fluid class="mt-10">
			<v-row>
				<img :src="DATA.image" style="height: auto; width: 100%" />
			</v-row>
		</v-container>
		<v-container fluid class="px-5 mt-10 mb-2">
			<v-row v-html="DATA.content"> </v-row>
		</v-container>
	</div>
</template>

<script>
	import Vue from "vue";
	import axios from "axios";

	export default {
		name: "Home",
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
			};
		},
		methods: {
			loadData: function () {
				let that = this;
				axios.get("/api/home").then((res) => {
					let data = res.data.data;
					// console.log("response --- " + res.data.data);
					Vue.set(that.DATA, "image", that.$root.imagePath + data.image);
					Vue.set(that.DATA, "content", data.content);
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
