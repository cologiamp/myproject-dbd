<script setup>
import { DotLoader } from "vue3-spinner";
import {CheckBadgeIcon} from "@heroicons/vue/24/solid/index.js";

const props = defineProps({
    saving: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <DotLoader v-if="saving" :loading="saving" color="#00B3DD" size="32" class="pt-8 pr-5" />
    <CheckBadgeIcon v-else class="text-aaron-400 w-8 h-8" />

</template>
