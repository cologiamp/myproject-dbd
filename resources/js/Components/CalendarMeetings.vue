<script setup>
import CalendarMeeting from './CalendarMeeting.vue';
const props = defineProps({
    meetings: Array
})
</script>
<template>
    <!-- calendar lozenge -->
    <div class="w-full h-full flex flex-col items-start bg-aaron-900 rounded-[20px] p-8">
        <p class="mb-8">Upcoming Meetings</p>
        <div class="w-full h-full flex flex-row items-start justify-between">
            <!-- meetings -->
            <div class="flex flex-col w-full h-full max-w-lg gap-6">
                <CalendarMeeting
                    v-for="(meeting, index) in meetings"
                    :meeting="meeting"
                    :last="index == meetings.length - 1"
                />
             </div>
            <!-- calendar -->
            <div class="w-full h-full flex flex-col items-center gap-4">
                <p>Calendar</p>
                <button class="bg-aaron-700 px-2 py-2 rounded-md w-full max-w-sm">
                    Add event
                </button>
            </div>
        </div>
    </div>
</template>
