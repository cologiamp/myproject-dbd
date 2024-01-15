<script setup>

import {autoS} from "@/autosave.js";
import SavingSpinner from "@/Components/SavingSpinner.vue";

const props = defineProps({
    saving: {
        type: Number,
        // default: false,
    },
});
</script>

<template>
    <div>
        <div class="px-1 py-1 sm:p-2 flex flex-row-reverse">
            <div class="flex">
                <saving-spinner :saving="autoS"></saving-spinner>
            </div>
        </div>
        <div class="px-2 py-2 sm:p-4 flex mt-[-40px]">
            <slot/>
        </div>
    </div>
</template>
