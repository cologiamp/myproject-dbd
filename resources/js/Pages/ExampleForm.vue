<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormWell from "@/Components/FormWell.vue";
import {useForm} from 'laravel-precognition-vue-inertia';
import SavingSpinner from "@/Components/SavingSpinner.vue";
import {autoS,autosaveT} from "@/autosave.js";




</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">

    </AppLayout>
</template>
