<script setup>
import { defineProps, inject } from "vue";

const props = defineProps({
    item: {
        type: Object
    }
});

const selectedItem = inject("selectedItem");

</script>

<template>
    <div class="tab-content" v-show="item.name == selectedItem">
        {{ item.name }}
    </div>
</template>

<style scoped>
</style>