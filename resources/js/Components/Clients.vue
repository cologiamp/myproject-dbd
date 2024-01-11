<script setup>
import Client from './Client.vue';

const prop = defineProps({
    clients: Array,
})
</script>

<template>
    <!-- clients lozenge -->
    <div class="w-full h-full grid grid-cols-3 gap-6 min-h-[420px]">
        <!-- client panel -->
        <Client
            v-for="(client, index) in clients"
            :key="index"
            :client="client"
        />
    </div>
</template>
