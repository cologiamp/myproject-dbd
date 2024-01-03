<script setup>
import { defineProps, inject, provide } from "vue";

const props = defineProps({
    tab: {
        type: Object
    },
    tabIndex: {
        type: Number
    }
});

const selectedTitle = inject("selectedTitle");

provide("tabIndex", props.tabIndex);

</script>

<template>
    <div class="bg-aaron-900 sm:rounded-lg p-8" v-show="tab.name == selectedTitle">
        <div class="my-6 pl-6">
            <span class="text-2xl font-medium">{{ tab.name }}</span>
        </div>
    </div>
</template>

<style scoped>
</style>