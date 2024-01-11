<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {ref} from "vue";
import { router } from '@inertiajs/vue3'
import ClientsContent from "@/Components/ClientsContent.vue"
import Clients from "@/Components/Clients.vue"

let selectedClient = ref(null);

const props = defineProps({
    title: String,
    breadcrumbs: Array,
    clients: Array,
    filters: {
        type: Object,
        default: {}
    }
});


const handleSearchClients = filters => {
    router.get("/clients", { ...filters }, {
        preserveScroll: true,
        preserveState: true,
    })
}
</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <div class="w-full h-full min-h-screen flex flex-col gap-12">
            <ClientsContent @search-clients="handleSearchClients" :filters="filters">
                <Clients :clients="clients"/>
            </ClientsContent>
        </div>
    </AppLayout>
</template>
