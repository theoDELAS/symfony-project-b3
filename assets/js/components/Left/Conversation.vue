<template>
    <router-link :to="{name: 'conversation', params: {id: conversation.conversationId}}" class="block group hover:bg-gray-100 p-5">
        <div class="media grid grid-cols-6 flex items-center">
            <img src="https://res.cloudinary.com/mhmd/image/upload/v1564960395/avatar_usae7z.svg" alt="user" width="50" class="rounded-circle col-span-1 justify-self-center">
            <div class="media-body ml-4 col-span-5">
                <div class="flex items-center justify-between mb-2">
                    <h6 class="mb-0">{{ conversation.name }}</h6><small class="small font-weight-bold">{{ date }}</small>
                </div>
                <p class="italic mb-0 text-sm">{{ conversation.content }}</p>
            </div>
        </div>
    </router-link>
</template>

<script>

export default {
    props: {
        conversation: Object
    },
    computed: {
        date() {
            return new Date(this.conversation.createdAt).toLocaleTimeString();
        }
    }
}
</script>