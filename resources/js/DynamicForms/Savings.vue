<script setup>
//FACTFIND:// you need to make one of these for every step


import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {autoS} from "@/autosave.js";
</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="text-red-600">Test</div>
    </dynamic-form-wrapper>
</template>

<style scoped>
    .aaron-datepicker{
        --dp-background-color: rgb(49 63 167 / var(--tw-bg-opacity));
        --dp-text-color: rgb(236 245 255 / var(--tw-text-opacity));
        --dp-border-radius: 6px;
        --dp-border-color: rgb(70 84 190 / var(--tw-ring-opacity));
    }

</style>
