<script setup>

import {autoS} from "@/autosave.js";
import SavingSpinner from "@/Components/SavingSpinner.vue";

const props = defineProps({
    saving: {
        type: Number
    },
});
</script>

<template>
    <div>
        <div class="px-2 py-2 sm:p-4 flex mt-[-40px]">
            <slot/>
        </div>
    </div>
</template>
