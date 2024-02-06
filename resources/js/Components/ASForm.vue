<script setup>

import SectionTitle from "@/Components/SectionTitle.vue";
</script>

<template>
    <div class="md:grid">
        <SectionTitle>
            <template #title>
                <slot name="title" />
            </template>
            <template #description>
                <slot name="description" />
            </template>
        </SectionTitle>

        <div class="mt-5 md:col-span-2">
            <div class="sm:rounded-lg">
                <slot name="content" />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
