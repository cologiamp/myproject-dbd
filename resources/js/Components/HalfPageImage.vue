<script setup>
defineProps({
    image: String,
});
</script>

<template>
    <div class="relative hidden w-0 flex-1 lg:block">
        <img class="absolute inset-0 h-full w-full object-cover" :src="image" alt="" />
    </div>
</template>
