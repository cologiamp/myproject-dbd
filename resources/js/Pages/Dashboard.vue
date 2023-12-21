<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';


defineProps({
    title: String,
    breadcrumbs: Array,
});

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">

    </AppLayout>
</template>
