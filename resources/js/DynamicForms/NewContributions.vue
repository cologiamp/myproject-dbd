<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";

import {onBeforeMount, watch} from "vue";
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
                types: [],
                paid_by: [],
                frequency: []
            },
            model: {
                prnew_contributions: [{
                    tax_year: null,
                    estimated_relevant_earnings: null,
                    estimated_adjusted_income: null,
                    type: null,
                    paid_by: null,
                    amount_gross: null,
                    frequency: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

let tax_years = [];

function setTaxYears() {
    const newTaxYear = DateTime.local(DateTime.now().year, 4, 5) // April 5 start of new tax year
    let currentDate = DateTime.local()

    if (currentDate.startOf("day") >= newTaxYear.startOf("day") ) {
        tax_years.push(
            DateTime.now().year + '/' + DateTime.now().plus({ years: 1 }).year,
            DateTime.now().plus({ years: 1 }).year + '/' + DateTime.now().plus({ years: 2 }).year
        )
    } else {
        tax_years.push(
            DateTime.now().minus({ years: 1 }).year + '/' + DateTime.now().year,
            DateTime.now().year + '/' + DateTime.now().plus({ years: 1 }).year
        )
    }
}

onBeforeMount(()=>{
    setTaxYears()
})

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    prnew_contributions: props.formData.model.prnew_contributions
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.prnew_contributions = props.formData.model.prnew_contributions
}
function removeContribution(index) {
    if(stepForm.prnew_contributions[index].id != null) {
        axios.delete('/api/pr-contributions/'+ stepForm.prnew_contributions[index].id).then(function (response){
            console.log(response.data)
        }).catch(function (e){
            console.log(e)
        });
    }

    stepForm.prnew_contributions.splice(index, 1);
    setTimeout(()=> {
        autosaveLocally();
    }
    ,500);
}

function addContribution() {
    let year = stepForm.prnew_contributions.length > 0 ? tax_years[1] : tax_years[0]

    if (stepForm.prnew_contributions.length === 0) {
        year = tax_years[0]
    } else if (stepForm.prnew_contributions.length > 0) {
        year = stepForm.prnew_contributions[0].tax_year === tax_years[0] ? tax_years[1] : tax_years[0]
    }

    stepForm.prnew_contributions.push({
        tax_year: year,
        estimated_relevant_earnings: null,
        estimated_adjusted_income: null,
        type: null,
        paid_by: null,
        amount_gross: null,
        frequency: null
    });
}

function formatAmount(e, index, dataField) {
    stepForm.prnew_contributions[index][dataField] = '';
    stepForm.prnew_contributions[index][dataField] = changeToCurrency(e.target.value);

    autosaveLocally()
}
</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div v-for="(pension, index) in stepForm.prnew_contributions" class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold">Tax Year {{ pension.tax_year }}</label>
                    <button type="button" @click="removeContribution(index)"
                        class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Remove Contribution
                    </button>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="estimated_relevant_earnings" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Estimated Relevant Earnings </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="estimated_relevant_earnings" id="estimated_relevant_earnings"
                           :value="pension.estimated_relevant_earnings" @change="formatAmount($event, index, 'estimated_relevant_earnings')"
                           class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.estimated_relevant_earnings">{{ stepForm.errors.estimated_relevant_earnings }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="estimated_adjusted_income" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Estimated Adjusted Income </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="estimated_adjusted_income" id="estimated_adjusted_income"
                           :value="pension.estimated_adjusted_income" @change="formatAmount($event, index, 'estimated_adjusted_income')"
                           class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.estimated_adjusted_income">{{ stepForm.errors.estimated_adjusted_income }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Type of Policy</label>
                    <select id="type" name="type"
                        @change="autosaveLocally()" v-model="pension.type"
                        class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="type" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(type, id) in formData.enums.types">{{ type }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.type">{{ stepForm.errors.type }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="paid_by" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Paid By</label>
                    <select id="paid_by" name="paid_by"
                        @change="autosaveLocally()" v-model="pension.paid_by"
                        class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="paid_by" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(paid_by, id) in formData.enums.paid_by">{{ paid_by }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.type">{{ stepForm.errors.type }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="amount_gross" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Amount (Gross) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="amount_gross" id="amount_gross"
                               :value="pension.amount_gross" @change="formatAmount($event, index, 'amount_gross')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.amount_gross">{{ stepForm.errors.amount_gross }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="frequency" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Frequency</label>
                    <select @change="autosaveLocally()" v-model="pension.frequency"
                            id="frequency" name="frequency"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="frequency" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(frequency, id) in formData.enums.frequency">{{ frequency }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.frequency">{{ stepForm.errors.frequency }}</p>
                </div>
            </div>
            <button v-if="stepForm.prnew_contributions.length < 2" type="button" @click="addContribution"
                class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add Contribution
            </button>
        </div>
    </dynamic-form-wrapper>
</template>
