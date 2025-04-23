<script setup lang="ts">
import { RouterLink, RouterView } from "vue-router";
</script>

<template>
  <header>
    <div class="px-10 mt-10">
      <nav>
        <RouterLink to="/" class="hover:underline text-xl">List of Users</RouterLink>
      </nav>
    </div>
  </header>
  <div class="mx-0 max-w-6xl px-10 mt-5 mb-10">
    <RouterView />
  </div>
</template>

<style scoped>

</style>
