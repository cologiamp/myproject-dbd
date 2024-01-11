<script setup>
import { router } from '@inertiajs/vue3'

import AppLayout from '@/Layouts/AppLayout.vue';
import ClientsContent from "@/Components/ClientsContent.vue"
import Clients from "@/Components/Clients.vue"
import Paginator from "@/Components/Paginator.vue"

const props = defineProps({
    title: String,
    breadcrumbs: Array,
    clients: Array,
    pagination: Object,
    filters: {
        type: Object,
        default: {}
    }
});

const handleSearchClients = searchParams => {
    const queryParams = {
        ...searchParams,
        page: props.filters.page,
        perPage: props.filters.perPage,
    }

    router.get("/clients", queryParams, {
        preserveScroll: true,
        preserveState: true,
    })
}

const handlePageChange = paginationParams => {
    const queryParams = {
        search: props.filters.search,
        select: props.filters.select,
        ...paginationParams,
    }

    router.get("/clients", queryParams, {
        preserveScroll: true,
        preserveState: true
    })
}

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <div class="w-full h-full min-h-screen flex flex-col gap-12 px-6 md:px-0">
            <ClientsContent @search-clients="handleSearchClients" :filters="filters">
                <Clients :clients="clients"/>
                <template #paginator>
                    <Paginator @page-change="handlePageChange" :pagination="pagination" />
                </template>
            </ClientsContent>
        </div>
    </AppLayout>
</template>
