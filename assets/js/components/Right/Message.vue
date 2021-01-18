<template>
    <!-- Sender Message-->
    <div class="media w-1/2 mb-3 flex items-center mb-4" :class="{'ml-auto': message.mine}">
        <img v-if="!message.mine" src="https://res.cloudinary.com/mhmd/image/upload/v1564960395/avatar_usae7z.svg" alt="user" width="50" class="rounded-circle mr-2">
        <div class="media-body">
            <div class=" w-52 px-4 py-2 rounded-lg mb-2" :class="[message.mine ? 'bg-blue-500' : 'bg-gray-100']">
                <p class="text-sm mb-0" :class="[message.mine ? 'text-white' : 'text-gray-600']">{{ message.content }}</p>
            </div>
            <p class="text-sm text-gray-300">{{ date }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        message: Object
    },
    computed: {
        date() {
            return new Date(this.message.createdAt).toLocaleString();
        }
    }
}
</script>