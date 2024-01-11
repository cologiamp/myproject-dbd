<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    client: Object,
})

const navigateToClientPage = client => {
    router.get(route("client.dashboard", {client: client.io_id}));
}

</script>

<template>
    <div @click="navigateToClientPage(client)" class="rounded-[20px] max-h-[197px] bg-aaron-900 min-h-24 min-w-24 p-6 hover:cursor-pointer hover:scale-105 duration-300">
        <div class="flex flex-row items-center w-full justify-between mb-6">
            <div class="flex flex-row items-center justify-start gap-2">
                <div class="flex flex-col items-start">
                    <p>{{ client.first_name }} {{ client.last_name }}</p>
                    <p>{{ client.age }}, {{ client.job_title }}</p>
                </div>
            </div>
            <p>...</p>
        </div>

        <div class="flex flex-row items-center w-full justify-between border-b border-b-[rgba(255,255,255,0.4)] pb-3">
            <p>Last Contact</p>
            <p>{{ client.last_contact }}</p>
        </div>

        <div class="flex flex-row items-center w-full justify-between pt-3">
            <div class="flex flex-row items-center gap-2">
                <p>Status</p>
                <div class="bg-orange-600 px-2 text-sm py-1 rounded-md">{{ client.status_text }}</div>
            </div>
        </div>

    </div>
</template>
