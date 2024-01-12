<script setup>
import { CalendarDaysIcon } from '@heroicons/vue/24/outline';
import { MapPinIcon } from '@heroicons/vue/24/outline';
const props = defineProps({
    meeting: Object,
    last: Boolean
})
</script>

<template>
    <div
        :class="last ? 'mb-12' : 'mb-0'"
        class="w-full flex flex-row items-center border-b border-b-[rgba(255,255,255,0.4)] py-4 gap-4"
    >
        <div class="rounded-full bg-white min-w-12 min-h-12"></div>
        <div class="flex flex-col w-full gap-1">
            <div class="flex flex-row w-full items-center justify-between">
                <p>{{ meeting.name }}</p>
                <p>...</p>
            </div>
            <div class="flex flex-row w-full items-center justify-start">
                <div class="flex flex-row items-center justify-start gap-2 border-r pr-2">
                    <CalendarDaysIcon class="w-4 h-4" />
                    <p>{{ meeting.date }}</p>
                </div>
                <div class="flex flex-row items-center justify-start gap-2 pl-2">
                    <MapPinIcon class="w-4 h-4" />
                    <p>{{ meeting.location }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
