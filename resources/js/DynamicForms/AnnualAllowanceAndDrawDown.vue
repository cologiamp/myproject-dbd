<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";

import {watch} from "vue";
import {usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";
import {changeToCurrency} from "@/currency.js";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';
import { DateTime } from "luxon";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
            },
            model: {
                pr_annual_allowances: [{
                    tax_year: null,
                    annual_allowance: null,
                    pension_input: null,
                    unused_allowance: null
                }],
                pr_data: {
                    pension_recommendation_id: null,
                    dd_pcls_spend: null,
                    dd_pcls_income: null,
                    dd_income: null
                }
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    pr_annual_allowances: props.formData.model.pr_annual_allowances,
    pension_recommendation_id: props.formData.model.pr_data.pension_recommendation_id,
    dd_pcls_spend: props.formData.model.pr_data.dd_pcls_spend,
    dd_pcls_income: props.formData.model.pr_data.dd_pcls_income,
    dd_income: props.formData.model.pr_data.dd_income
})

async function autosaveLocally(index){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.pr_annual_allowances = props.formData.model.pr_annual_allowances,
    stepForm.pension_recommendation_id = props.formData.model.pr_data.pension_recommendation_id,
    stepForm.dd_pcls_spend = props.formData.model.pr_data.dd_pcls_spend,
    stepForm.dd_pcls_income = props.formData.model.pr_data.dd_pcls_income,
    stepForm.dd_income = props.formData.model.pr_data.dd_income
}
function removeAllowance(index) {
    if(stepForm.pr_annual_allowances[index].id != null) {
        axios.delete('/api/pr-allowances/'+ stepForm.pr_annual_allowances[index].id).then(function (response){
            console.log(response.data)
        }).catch(function (e){
            console.log(e)
        });
    }

    stepForm.pr_annual_allowances.splice(index, 1);
}

let tax_year = DateTime.now().minus({ years: 1 }).year + '/' + DateTime.now().year;

function addContribution() {
    stepForm.pr_annual_allowances.push({
        tax_year: tax_year,
        annual_allowance: null,
        pension_input: null,
        unused_allowance: null
    });
}

function formatAmount(e, dataField, index) {
    if (index == null) {
        stepForm[dataField] = '';
        stepForm[dataField] = changeToCurrency(e.target.value);
    } else {
        stepForm.pr_annual_allowances[index][dataField] = '';
        stepForm.pr_annual_allowances[index][dataField] = changeToCurrency(e.target.value);
    }
    autosaveLocally()
}
</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="dd_pcls_spend" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Draw Down: PCLS Spend </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="dd_pcls_spend" id="dd_pcls_spend"
                               :value="stepForm.dd_pcls_spend" @change="formatAmount($event, 'dd_pcls_spend')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.dd_pcls_spend">{{ stepForm.errors.dd_pcls_spend }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="dd_pcls_income" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Draw Down: PCLS Income </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="dd_pcls_income" id="dd_pcls_income"
                               :value="stepForm.dd_pcls_income" @change="formatAmount($event, 'dd_pcls_income')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.dd_pcls_income">{{ stepForm.errors.dd_pcls_income }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="dd_income" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Draw Down: Income </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="dd_income" id="dd_income"
                               :value="stepForm.dd_income" @change="formatAmount($event, 'dd_income')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.dd_income">{{ stepForm.errors.dd_income }}</p>
                </div>
            </div>
            <div v-for="(allowance, index) in stepForm.pr_annual_allowances" class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold">Tax Year {{allowance.tax_year}}</label>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="annual_allowance" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Annual Allowance </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="annual_allowance" id="annual_allowance"
                               :value="allowance.annual_allowance" @change="formatAmount($event, 'annual_allowance', index)"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.annual_allowance">{{ stepForm.errors.annual_allowance }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="pension_input" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Pension Input </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="pension_input" id="pension_input"
                               :value="allowance.pension_input" @change="formatAmount($event, 'pension_input', index)"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.pension_input">{{ stepForm.errors.pension_input }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="unused_allowance" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Unused Allowance </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="unused_allowance" id="unused_allowance"
                               :value="allowance.unused_allowance" @change="formatAmount($event, 'unused_allowance', index)"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.unused_allowance">{{ stepForm.errors.unused_allowance }}</p>
                </div>
            </div>
            <button type="button" @click="addContribution"
                    class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add Allowance
            </button>
        </div>
    </dynamic-form-wrapper>
</template>
