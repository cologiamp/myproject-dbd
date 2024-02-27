<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";

import {watch} from "vue";
import {usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";
import {changeToCurrency} from "@/currency.js";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                fee_basis: [],
                report_type: []
            },
            model: {
                pension_recommendation: [{
                    previously_invested_amount: null,
                    fee_basis: null,
                    fee_basis_discount: null,
                    report_type: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

function formatAmount(e, dataField) {
    stepForm.pension_recommendation[dataField] = '';
    stepForm.pension_recommendation[dataField] = changeToCurrency(e.target.value);

    autosaveLocally()
}

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    pension_recommendation: props.formData.model.pension_recommendation[0]
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.pension_recommendation = props.formData.model.pension_recommendation[0]
}
</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="previously_invested_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Previously Invested Amount </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="previously_invested_amount" id="previously_invested_amount"
                               :value="stepForm.pension_recommendation.previously_invested_amount"
                               @change="formatAmount($event, 'previously_invested_amount')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.previously_invested_amount">{{ stepForm.errors.previously_invested_amount }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="risk_profile" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Report Type</label>
                    <select @change="autosaveLocally()" v-model="stepForm.pension_recommendation.report_type" id="report_type" name="report_type"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="report_for" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(type, id) in formData.enums.report_type">{{ type }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.report_type">{{ stepForm.errors.report_type }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="fee_basis" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Fee Basis</label>
                    <select @change="autosaveLocally()" v-model="stepForm.pension_recommendation.fee_basis" id="fee_basis" name="fee_basis"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="title" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(fee_basis, id) in formData.enums.fee_basis">{{ fee_basis }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.fee_basis">{{ stepForm.errors.fee_basis }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <div v-if="stepForm.pension_recommendation.fee_basis == 1">
                        <label for="previously_invested_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Fee Basis Discount </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input type="currency" name="fee_basis_discount" id="fee_basis_discount"
                                   :value="stepForm.pension_recommendation.fee_basis_discount"
                                   @change="formatAmount($event, 'fee_basis_discount')"
                                   class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.fee_basis_discount">{{ stepForm.errors.fee_basis_discount }}</p>
                    </div>
                </div>
            </div>
        </div>
    </dynamic-form-wrapper>
</template>
