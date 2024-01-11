<script setup>
import { router } from '@inertiajs/vue3';

import AppLayout from '@/Layouts/AppLayout.vue';
import StatsContent from '@/Components/StatsContent.vue';
import Stats from '@/Components/Stats.vue';
import ClientsContent from '@/Components/ClientsContent.vue';
import Clients from '@/Components/Clients.vue';
import Paginator from '@/Components/Paginator.vue';


const props = defineProps({
    title: String,
    breadcrumbs: Array,
    clients: Array,
    pagination: Object,
    filters: {
        type: Object,
        default: {}
    },
    stats: {
        type: Array,
        default: [
            { title: "Hours on AdviserHUB", value: 20 },
            { title: "New enquiries", value: 25 },
            { title: "Active enquiries", value: 30 },
            { title: "Processed clients", value: 35 },
        ]
    },
});


const handleSearchClients = searchParams => {
    const queryParams = {
        ...searchParams,
        page: props.pagination.current_page,
        perPage: props.pagination.per_page,
    }

    router.get("/dashboard", queryParams, {
        preserveScroll: true,
        preserveState: true,
    })
}

const handlePageChange = paginationParams => {
    const queryParams = {
        search: props.filters.search,
        select: props.filters.select,
        searching: false,
        ...paginationParams,
    }

    router.get("/dashboard", queryParams, {
        preserveScroll: true,
        preserveState: true
    })
}

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <div class="w-full h-full min-h-screen flex flex-col gap-12">
            <StatsContent>
                <Stats :stats="stats" />
            </StatsContent>
            <ClientsContent @search-clients="handleSearchClients" :filters="filters">
                <Clients :clients="clients"/>
                <template #paginator>
                    <Paginator @page-change="handlePageChange" :pagination="pagination" />
                </template>
            </ClientsContent>
        </div>
    </AppLayout>
</template>
