<template>
	<div class="col-span-3 bg-white">
		<div class="bg-white">
			<div class="px-4 py-2 bg-light">
				<p class="text-xl">Recent</p>
			</div>
			<div class="messages-box">
				<div class="grid grid-cols-1 divide-y divide-gray-200">
					<template v-for="(conversation, index, key) in CONVERSATIONS">
						<Conversation :conversation="conversation" />
					</template>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import {mapGetters} from "vuex";
    import Conversation from "./Conversation";
export default {
	components: {Conversation},
	computed: {
		...mapGetters(["CONVERSATIONS", "HUBURL", "USERNAME"])
	},
	methods: {
		updateConversations(data) {
			this.$store.commit("UPDATE_CONVERSATIONS", data)
		}
	},	
	mounted() {
		const vm = this;
		this.$store.dispatch("GET_CONVERSATIONS")
			.then(() => {
				let url = new URL(this.HUBURL);
				url.searchParams.append('topic', `/conversation/${this.USERNAME}`)
				const eventSource = new EventSource(url, {
					withCredentials: true
				})

				eventSource.onmessage = function (event) {
					vm.updateConversations(JSON.parse(event.data))
				}
			})
	},
}
</script>