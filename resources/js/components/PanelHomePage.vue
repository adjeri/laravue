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
							label="Home page image"
							filled
							prepend-icon="fas fa-camera"
						></v-text-field>
						<input
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
											@click.stop.prevent.prevent="$root.rmFile('image')"
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
						<v-col class="text-h5 ma-1">Content</v-col>
						<div class="editor">
							<editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
								<div class="menubar">
									<button
										class="menubar__button"
										:class="{ 'is-active': isActive.bold() }"
										@click.stop.prevent="commands.bold"
									>
										<!-- <icon name="bold" /> -->
										<v-icon>fas fa-bold</v-icon>
									</button>

									<button
										class="menubar__button"
										:class="{ 'is-active': isActive.italic() }"
										@click.stop.prevent="commands.italic"
									>
										<!-- <icon name="italic" /> -->
										<v-icon>fas fa-italic</v-icon>
									</button>

									<button
										class="menubar__button"
										:class="{ 'is-active': isActive.strike() }"
										@click.stop.prevent="commands.strike"
									>
										<!-- <icon name="strike" /> -->
										<v-icon>fas fa-strikethrough</v-icon>
									</button>

									<button
										class="menubar__button"
										:class="{ 'is-active': isActive.underline() }"
										@click.stop.prevent="commands.underline"
									>
										<!-- <icon name="underline" /> -->
										<v-icon>fas fa-underline</v-icon>
									</button>

									<button
										class="menubar__button"
										:class="{ 'is-active': isActive.paragraph() }"
										@click.stop.prevent="commands.paragraph"
									>
										<!-- <icon name="paragraph" /> -->
										<v-icon>fas fa-paragraph</v-icon>
									</button>

									<button
										class="menubar__button"
										:class="{ 'is-active': isActive.heading({ level: 1 }) }"
										@click.stop.prevent="commands.heading({ level: 1 })"
									>
										H1
									</button>

									<button
										class="menubar__button"
										:class="{ 'is-active': isActive.heading({ level: 2 }) }"
										@click.stop.prevent="commands.heading({ level: 2 })"
									>
										H2
									</button>

									<button
										class="menubar__button"
										:class="{ 'is-active': isActive.heading({ level: 3 }) }"
										@click.stop.prevent="commands.heading({ level: 3 })"
									>
										H3
									</button>

									<button
										class="menubar__button"
										:class="{ 'is-active': isActive.bullet_list() }"
										@click.stop.prevent="commands.bullet_list"
									>
										<!-- <icon name="ul" /> -->
										<v-icon>fas fa-list-ul</v-icon>
									</button>

									<button
										class="menubar__button"
										:class="{ 'is-active': isActive.ordered_list() }"
										@click.stop.prevent="commands.ordered_list"
									>
										<!-- <icon name="ol" /> -->
										<v-icon>fas fa-list-ol</v-icon>
									</button>

									<button
										class="menubar__button"
										:class="{ 'is-active': isActive.blockquote() }"
										@click.stop.prevent="commands.blockquote"
									>
										<!-- <icon name="quote" /> -->
										<v-icon>fas fa-quote-right</v-icon>
									</button>

									<button class="menubar__button" @click="commands.undo">
										<!-- <icon name="undo" /> -->
										<v-icon>fas fa-undo</v-icon>
									</button>

									<button class="menubar__button" @click="commands.redo">
										<!-- <icon name="redo" /> -->
										<v-icon>fas fa-redo</v-icon>
									</button>
								</div>
							</editor-menu-bar>
							<editor-menu-bubble
								class="menububble"
								:editor="editor"
								@hide="hideLinkMenu"
								v-slot="{ commands, isActive, getMarkAttrs, menu }"
							>
								<div
									class="menububble"
									:class="{ 'is-active': menu.isActive }"
									:style="`left: ${menu.left}px; bottom: ${menu.bottom}px;`"
								>
									<form
										class="menububble__form"
										v-if="linkMenuIsActive"
										@submit.prevent="setLinkUrl(commands.link, linkUrl)"
									>
										<input
											class="menububble__input"
											type="text"
											v-model="linkUrl"
											placeholder="https://"
											ref="linkInput"
											@keydown.esc="hideLinkMenu"
										/>
										<button
											class="menububble__button"
											@click.stop.prevent="setLinkUrl(commands.link, null)"
											type="button"
										>
											<!-- <icon name="remove" /> -->
											<v-icon>fas fa-ban</v-icon>
										</button>
									</form>

									<template v-else>
										<button
											class="menububble__button"
											@click.stop.prevent="showLinkMenu(getMarkAttrs('link'))"
											:class="{ 'is-active': isActive.link() }"
										>
											<span>{{
												isActive.link() ? "Update Link" : "Add Link"
											}}</span>
											<!-- <icon name="link" /> -->
											<v-icon>fas fa-link</v-icon>
										</button>
									</template>
								</div>
							</editor-menu-bubble>

							<editor-content class="editor__content" :editor="editor" />
						</div>
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
	// Import the editor
	// import Icon from "Components/Icon";
	import { Editor, EditorContent, EditorMenuBar, EditorMenuBubble } from "tiptap";
	import {
		Blockquote,
		CodeBlock,
		HardBreak,
		Heading,
		HorizontalRule,
		OrderedList,
		BulletList,
		ListItem,
		TodoItem,
		TodoList,
		Bold,
		Code,
		Italic,
		Link,
		Strike,
		Underline,
		History,
	} from "tiptap-extensions";

	export default {
		components: {
			EditorContent,
			EditorMenuBar,
			EditorMenuBubble,
		},
		data() {
			return {
				imageInput: {},
				INPUT: {
					valid: false,
					image: null,
					content: null,
					meta_title: "",
					meta_description: "",
				},
				ERROR: {},
				linkUrl: null,
				linkMenuIsActive: false,
				editor: null,
			};
		},
		methods: {
			showLinkMenu(attrs) {
				this.linkUrl = attrs.href;
				this.linkMenuIsActive = true;
				this.$nextTick(() => {
					this.$refs.linkInput.focus();
				});
			},
			hideLinkMenu() {
				this.linkUrl = null;
				this.linkMenuIsActive = false;
			},
			setLinkUrl(command, url) {
				command({ href: url });
				this.hideLinkMenu();
			},
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

				// console.log("keys ---- " + Object.keys(this.editor));
				// console.log("content ---- " + this.editor.getHTML());

				if (this.$refs.FORM.validate()) {
					Vue.set(this.$root, "mask", true);
					Vue.set(this, "ERROR", {});

					this.INPUT["content"] = this.editor.getHTML();
					var formdata = new FormData();
					for (var i in this.INPUT) {
						if (this.INPUT[i] != null) {
							formdata.append(i, this.INPUT[i]);
						}
					}

					axios
						.post("/api/panel/home", formdata)
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
					.get("/api/home")
					.then(async (res) => {
						let _URL = window.URL || window.webkitURL;
						let data = res.data.data;
						let image = that.$root.imagePath + data.image;
						let blob = await fetch(image).then((r) => r.blob());

						Vue.set(that.$root.INPUT, "image", that.$root.imagePath + data.image);
						Vue.set(that.INPUT, "meta_title", data.meta_title);
						Vue.set(that.INPUT, "meta_description", data.meta_description);
						Vue.set(that.INPUT, "image", blob);

						// this.editor = new Editor({
						// 	content: "<p>This is just a boring paragraph</p>",
						// });

						const bodyContent = new Editor({
							extensions: [
								new Blockquote(),
								new BulletList(),
								new CodeBlock(),
								new HardBreak(),
								new Heading({ levels: [1, 2, 3] }),
								new HorizontalRule(),
								new ListItem(),
								new OrderedList(),
								new TodoItem(),
								new TodoList(),
								new Link(),
								new Bold(),
								new Code(),
								new Italic(),
								new Strike(),
								new Underline(),
								new History(),
							],
							content: data.content,
						});
						Vue.set(that, "editor", bodyContent);
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
		beforeDestroy() {
			this.editor.destroy();
		},
	};
</script>
