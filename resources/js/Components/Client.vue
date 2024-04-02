<script setup>

const props = defineProps({
    client: Object,
})

</script>

<template>
    <div class="rounded-[20px] max-h-full xl:max-h-[197px] bg-aaron-900 min-h-24 min-w-24 p-4 md:p-6 hover:cursor-pointer hover:scale-105 duration-300">
        <div class="flex flex-wrap md:flex-row items-start md:items-center w-full justify-end md:justify-between mb-6">
            <div class="flex flex-row items-center justify-start gap-2">
                <div class="flex flex-col items-start">
                    <p>{{ client.first_name }} {{ client.last_name }}</p>
                    <p><template v-if="client.age > 0">Age {{ client.age }}</template><template v-if="client.job_title.length > 0">, {{ client.job_title }}</template>&nbsp;</p>
                </div>
            </div>
            <p class="order-first md:order-none">...</p>
        </div>

        <div class="flex flex-wrap md:flex-row items-center w-full justify-between border-b border-b-[rgba(255,255,255,0.4)] pb-3">
            <p>Last Updated</p>
            <p>{{ client.last_updated }}</p>
        </div>

        <div class="flex flex-row items-center w-full justify-between pt-3">
            <div class="flex flex-row items-center gap-2">
                <p>Status</p>
                <div v-if="client.status_text == 'In Progress'" class="bg-orange-300 text-aaron-950 font-black px-2 text-sm py-1 rounded-md">{{ client.status_text }}</div>
                <div v-else-if="client.status_text == 'Completed'" class="bg-green-600 text-aaron-950 font-black px-2 text-sm py-1 rounded-md">{{ client.status_text }}</div>
                <div v-else class="bg-aaron-400 text-aaron-950 font-black px-2 text-sm py-1 rounded-md">{{ client.status_text }}</div>
            </div>
        </div>

    </div>
</template>
