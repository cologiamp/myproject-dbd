<script setup>
import { defineProps, inject } from "vue";

const props = defineProps({
    item: {
        type: Object,
        default: {
            'name' : 'Section Content',
            'currrent': true
        }
    },
    sectionIndex: {
        type: String
    }
});

// need to send the section
// the input fields

const selectedSectionId = inject("selectedSectionId");

</script>

<template>
    <div class="tab-content" v-show="sectionIndex == selectedSectionId">
        {{ item.name }}
    </div>
</template>

<style scoped>
</style>
