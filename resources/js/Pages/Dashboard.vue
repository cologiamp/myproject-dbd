<script setup>
import { router } from '@inertiajs/vue3';

import AppLayout from '@/Layouts/AppLayout.vue';
import StatsContent from '@/Components/StatsContent.vue';
import Stats from '@/Components/Stats.vue';
import CalendarContent from '@/Components/CalendarContent.vue';
import CalendarMeetings from '@/Components/CalendarMeetings.vue';
import ClientsContent from '@/Components/ClientsContent.vue';
import Clients from '@/Components/Clients.vue';


const props = defineProps({
    title: String,
    breadcrumbs: Array,
    clients: Array,
    filters: {
        type: Object,
        default: {}
    },
    stats: {
        type: Array,
        default: [
            {title: "Hours on AdviserHUB", value: 20},
            {title: "New enquiries", value: 25},
            {title: "Active enquiries", value: 30},
            {title: "Processed clients", value: 35},
        ]
    },
    meetings: {
        type: Array,
        default: [
            { name: "liam", date: "24th Jan", location: "Manchester" },
            { name: "liam", date: "24th Jan", location: "Manchester" },
            { name: "liam", date: "24th Jan", location: "Manchester" },
            { name: "liam", date: "24th Jan", location: "Manchester" },
        ]
    },
});

const handleSearchClients = filters => {
    router.get("/dashboard", { ...filters }, {
        preserveScroll: true,
        preserveState: true,
    })
}
</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <div class="w-full h-full min-h-screen flex flex-col gap-12">
            <StatsContent>
                <Stats :stats="stats" />
            </StatsContent>
            <CalendarContent>
                <CalendarMeetings :meetings="meetings" />
            </CalendarContent>
            <ClientsContent @search-clients="handleSearchClients" :filters="filters">
                <Clients :clients="clients"/>
            </ClientsContent>
        </div>
    </AppLayout>
</template>
