<template>
    <!-- Typing area -->
    <form action="#" class="bg-light">
        <div class="input-group flex items-center">
            <input type="text" placeholder="Type a message" v-model="content" @keyup.enter="sendMessage" aria-describedby="button-addon2" class="w-full form-control rounded-0 border-0 p-4 bg-light">
            <div class="input-group-append">
            <button id="button-addon2" type="submit" class="btn btn-link bg-blue-600 text-blue-50 p-2">
                <svg class="w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </button>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    data: () => ({
        content: ''
    }),
    methods: {
        sendMessage() {
            this.$store.dispatch("POST_MESSAGE", {
                conversationId: this.$route.params.id,
                content: this.content
            }).
                then(() => {
                    this.content = ''
                })
        }
    }
}
</script>