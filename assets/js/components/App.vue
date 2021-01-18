<template>
  <div class="container py-5 px-4">
    <div class=" grid grid-cols-5 gap-4">
      <!-- Users box-->
      <Left />
      <!-- Chat Box-->
      <router-view :key="$route.fullPath"></router-view>
    </div>
  </div>

</template>

<script>
  import Left from './Left/Left';
  import Right from './Right/Right';
    export default {
      components: {Left, Right}
    }
</script>