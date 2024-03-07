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
                investment_length: null,
                standard_of_living: null,
                emergency_funds: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

const stepForm = useForm({
    id: props.formData.model.id,
    investment_length: props.formData.model.investment_length,
    standard_of_living: props.formData.model.standard_of_living,
    emergency_funds: props.formData.model.emergency_funds
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.id = props.formData.model.id;
    stepForm.investment_length = props.formData.model.investment_length;
    stepForm.standard_of_living = props.formData.model.standard_of_living;
    stepForm.emergency_funds = props.formData.model.emergency_funds;
}

onMounted(()=>{
})

</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <form-errors :errors="usePage().props.errors"/>
        <div class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
            <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                    How long do you feel you can make this investment?
                </label>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.investment_length"
                           :checked="stepForm.investment_length === 0"
                           type="radio" id="less" :value="0"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label for="less" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Less than 5 years
                    </label>
                </div>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.investment_length"
                           :checked="stepForm.investment_length === 1"
                           type="radio" id="between" :value="1"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label for="between" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Between 5 and 10 years
                    </label>
                </div>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.investment_length"
                           :checked="stepForm.investment_length === 2"
                           type="radio" id="over" :value="2"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label for="over" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Over 10 years
                    </label>
                </div>
                <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.investment_length">{{ stepForm.errors.investment_length }}</p>
            </div>
            <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                    How much would your standard of living be affected if this investment were to perform below your expectations?
                </label>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.standard_of_living"
                           :checked="stepForm.standard_of_living === 0"
                           type="radio" id="significant-impact" :value="0"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label for="significant-impact" class="ml-2 block text-sm font-medium leading-6 text-white">
                        It would have a significant impact. I cannot afford for this investment to not meet my objectives
                    </label>
                </div>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.standard_of_living"
                           :checked="stepForm.standard_of_living === 1"
                           type="radio" id="standard-of-living" :value="1"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label for="standard-of-living" class="ml-2 block text-sm font-medium leading-6 text-white">
                        It would cause me to reassess my standard of living and make some cut-backs
                    </label>
                </div>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.standard_of_living"
                           :checked="stepForm.standard_of_living === 2"
                           type="radio" id="alternative-investments" :value="2"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label for="alternative-investments" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Not much as I have alternative investments / savings to maintain my lifestyle
                    </label>
                </div>
                <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.standard_of_living">{{ stepForm.errors.standard_of_living }}</p>
            </div>
            <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                    If you needed emergency funds, would you consider taking them from this investment?
                </label>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.emergency_funds"
                           :checked="stepForm.emergency_funds === 0"
                           type="radio" id="no-alternatives" :value="0"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label for="no-alternatives" class="ml-2 block text-sm font-medium leading-6 text-white">
                        I have no alternatives. I would almost certainly need access to this investment
                    </label>
                </div>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.emergency_funds"
                           :checked="stepForm.emergency_funds === 1"
                           type="radio" id="take-funds" :value="1"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label for="take-funds" class="ml-2 block text-sm font-medium leading-6 text-white">
                        I would consider taking funds from this investment if necessary
                    </label>
                </div>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.emergency_funds"
                           :checked="stepForm.emergency_funds === 2"
                           type="radio" id="other-savings" :value="2"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label for="other-savings" class="ml-2 block text-sm font-medium leading-6 text-white">
                        No, I have other savings that I can use for emergencies
                    </label>
                </div>
                <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.emergency_funds">{{ stepForm.errors.emergency_funds }}</p>
            </div>
        </div>
    </dynamic-form-wrapper>
</template>
