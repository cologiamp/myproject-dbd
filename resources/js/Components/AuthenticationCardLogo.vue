<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import {computed} from "vue";

const page = usePage();
const logo = computed(() => page.props.logo)
</script>

<template>
    <Link :href="'/'">
        <img :src="logo" alt="AdviserHub Logo" class="w-80 md:w-full">
    </Link>
</template>
