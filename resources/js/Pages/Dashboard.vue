<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';


defineProps({
    title: String,
    breadcrumbs: Array,
});

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">

    </AppLayout>
</template>
