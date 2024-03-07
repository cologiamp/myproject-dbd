<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";

import {onMounted, ref, watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";
import {changeToCurrency} from "@/currency.js";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})

const cash_element = 2;
const treasured_stock_transfers = 3;

const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
            },
            model: {
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

onMounted(()=>{
})

</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
    </dynamic-form-wrapper>
</template>
