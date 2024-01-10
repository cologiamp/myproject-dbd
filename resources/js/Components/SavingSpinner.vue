<script setup>
import { DotLoader } from "vue3-spinner";
import {CheckBadgeIcon, ExclamationTriangleIcon } from "@heroicons/vue/24/solid/index.js";
import {watch} from "vue";

const props = defineProps({
    saving: {
        type: Number,
        default: 1,
    },
});

</script>

<template>
    <DotLoader v-if="saving === 2" :loading="saving === 2" color="#00B3DD" size="32" class="pt-8 pr-5" />
    <CheckBadgeIcon v-else-if="saving === 1" class="text-aaron-400 w-8 h-8" />
    <ExclamationTriangleIcon v-else class="text-red-500 w-8 h-8" />
</template>
