<script setup>
//FACTFIND:// you need to make one of these for every step
import {__error, autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import VueDatePicker from "@vuepic/vue-datepicker";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';

import '@vuepic/vue-datepicker/dist/main.css'
import {computed, onBeforeMount, ref, watch} from "vue";
import {formatDate} from "@vueuse/core";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS, (newValue, oldValue) => {
    emit('autosaveStateChange', newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                income_types: [],
                frequencies: [],
                per_year_frequencies: [],
                belongs_to: []
            },
            model: {
                useIncome: true,
                total: null,
                incomes: [{
                    income_type: null,
                    gross_amount: null,
                    net_amount: null,
                    expenses: null,
                    frequency: null,
                    starts_at: null,
                    ends_at: null,
                    belongs_to: null,
                    record_exists: null,
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

function saveDate(index, value,type) {
    stepForm.incomes[index][type] = value;
    autosaveT(stepForm,props.formData.submit_url);
}

function addIncome() {
    stepForm.incomes.push({
        income_type: null,
        gross_amount: null,
        net_amount: null,
        expenses: null,
        frequency: null,
        starts_at: null,
        ends_at: null,
        belongs_to: null,
        record_exists: null,
    });
    calculatedTotals.value.push([0])
}

let calculatedTotals = ref([]);
let calculatedTotal = computed(() => {
    return calculatedTotals.value.reduce((partialSum, a) => partialSum + a, 0);
})
function calculateTotals(){
    if(stepForm.incomes && stepForm.incomes.length > 0 && props && props.formData)
    {
        calculatedTotals.value = stepForm.incomes.map(function (item){
            if(item.frequency !== null && item.gross_amount !== null)
            {
                let tempAmount = item.gross_amount.replace(/[^\d.]/g, '');
                return tempAmount * props.formData.enums.per_year_frequencies[item.frequency];
            }
            return 0;
        })
    }
    else{
        calculatedTotals.value = [];
    }
}

function updateAndSave()
{
    calculateTotals()
    autosaveT(stepForm,props.formData.submit_url)
}

function removeIncome(index) {
    stepForm.incomes.splice(index, 1);
    calculateTotals()
    autosaveT(stepForm,props.formData.submit_url);
}

const stepForm = useForm(`EditIncomes${ props.formData.model.client_id }`, {
    incomes: props.formData.model.incomes != null ? props.formData.model.incomes : [],
    useIncome: props.formData.model.useIncome,
    total: props.formData.model.total,
})

onBeforeMount(() => {
    formatAmountOnload()
    calculateTotals()
})

function formatAmountOnload() {
    Object.entries(stepForm.incomes).forEach(income => {
        const [key, value] = income;

        if (value['gross_amount'] && value['gross_amount'] != null) {
            value['gross_amount'] = changeToCurrency(value['gross_amount'].toString());
        }
        if (value['net_amount'] && value['net_amount'] != null) {
            value['net_amount'] = changeToCurrency(value['net_amount'].toString());
        }
        if (value['expenses'] && value['expenses'] != null) {
            value['expenses'] = changeToCurrency(value['expenses'].toString());
        }

    });
    if(stepForm.total && stepForm.total > 0)
    {
        stepForm.total = changeToCurrency(stepForm.total.toString())
    }
}

function formatAmount(e, index, dataField) {
    stepForm.incomes[index][dataField] = '';
    stepForm.incomes[index][dataField] = changeToCurrency(e.target.value);
}
function formatTotal()
{
    stepForm.total = changeToCurrency(stepForm.total);
}
function saveTotal()
{
    autosaveT(stepForm,props.formData.submit_url)
}


function changeToCurrency(amount) {
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'GBP',
        minimumFractionDigits: 0
      });

    // Remove non-numeric characters from user input and convert to int
    let tempAmount = amount.replace(/[^\d.]/g, '');
    let numberValue = ''

    if (tempAmount) {
        numberValue = tempAmount;
        // Format input using Intl.NumberFormat
        return formatter.format(numberValue)
    }
}




</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="rounded-md bg-red-50 p-4 mb-2" v-if="usePage().props.errors && Object.keys(usePage().props.errors).length > 0">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800" v-if="Object.keys(usePage().props.errors).length === 1">There was an error identified</h3>
                        <h3 class="text-sm font-medium text-red-800" v-else>There were {{Object.keys(usePage().props.errors).length}} errors identified</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul role="list" class="list-disc space-y-1 pl-5">
                                <li v-for="(key, value) in usePage().props.errors">
                                    {{key}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 grid grid-cols-6 rounded-md bg-aaron-950 pt-2 mb-4 p-4">
                <h4 class="col-span-6 text-xl font-bold pt-2"> Overall Income </h4>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3" v-if="stepForm.useIncome == false">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Total: </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input
                               v-model="stepForm.total"
                               @input="formatTotal"
                               @change="saveTotal"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.gross_amount">{{ stepForm.errors.gross_amount }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3" v-else>
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Total: </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input
                            disabled
                            :value="changeToCurrency(calculatedTotal.toString())"
                            class="disabled block ring-1 disabled:bg-gray-400 ring-inset flex-1 border-0 rounded-md py-1.5 pl-2
                                placeholder:text-aaron-950 focus:ring-0 sm:text-sm sm:leading-6 shadow-none" placeholder="£" />
                            </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.gross_amount">{{ stepForm.errors.gross_amount }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-11">
                        <div class="flex h-6 items-center">
                            <input :disabled="stepForm.incomes.length > 0" id="is_primary" name="is_primary" type="checkbox" v-model="stepForm.useIncome" class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400 disabled:bg-gray-500 disabled:cursor-not-allowed disabled:hover:bg-gray-500" />
                        </div>
                        <div class="ml-3">
                            <label for="is_primary" class="text-sm font-medium leading-6 text-aaron-50">Collect Income Details</label>
                        </div>
                    </div>
                </div>
            </div>
            <div v-for="(income, index) in stepForm.incomes"
                class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold">Income {{ index + 1 }}</label>
                    <button type="button" @click="removeIncome(index)"
                        class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Remove Income
                    </button>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="income_type"
                        class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Income Type</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)"
                        id="income_type" name="income_type" v-model="income.income_type"
                        class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="income_type" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(income_type, id) in formData.enums.income_types">{{ income_type }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.income_type">{{ stepForm.errors.income_type }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Gross Amount </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="updateAndSave" type="currency" name="gross_amount" id="gross_amount"
                            :value="income.gross_amount"
                            @input="formatAmount($event, index, 'gross_amount')"
                            class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.gross_amount">{{ stepForm.errors.gross_amount }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="net_amount" v-if="income.income_type != 10" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Net Amount </label>
                    <label for="net_profit" v-else class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Net Profit </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" type="text" name="net_amount" id="net_amount"
                            :value="income.net_amount"
                            @input="formatAmount($event, index, 'net_amount')"
                            class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.net_amount">{{ stepForm.errors.net_amount }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <div v-if="income.income_type == 10">
                        <label for="expenses" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Valid Expenses </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveT(stepForm,props.formData.submit_url)" type="currency" name="expenses" id="expenses"
                                :value="income.expenses"
                                @input="formatAmount($event, index, 'expenses')"
                                class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.expenses">{{ stepForm.errors.expenses }}</p>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="frequency" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Frequency</label>
                    <select @change="updateAndSave" id="frequency" name="frequency" v-model="income.frequency"
                        class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="frequency" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(frequency, id) in formData.enums.frequencies">{{ frequency }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.frequency">{{ stepForm.errors.frequency }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                   <label for="net_profit" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Total </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input disabled type="text" name="total" id="total"
                               :value="changeToCurrency(calculatedTotals[index].toString())"
                               class="disabled block ring-1 disabled:bg-gray-400 ring-inset ring-aaron-500 flex-1 border-0 rounded-md py-1.5 pl-2
                                placeholder:text-aaron-950 focus:ring-0 sm:text-sm sm:leading-6 shadow-none" placeholder="£" />
                    </div>
                </div>
                <div class="col-span-6 grid grid-cols-6 rounded-md bg-aaron-950 pt-2 p-4">
                    <h4 class="col-span-6 text-xl font-bold pt-2"> Income Dates </h4>
                    <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                        <label for="starts_at" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Start Date </label>
                        <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                            <VueDatePicker text-input @closed="saveDate(index, income.starts_at,'starts_at')" v-model="income.starts_at"
                                           class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy"
                                           name="starts_at" id="starts_at" placeholder="dd/mm/yyyy" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.starts_at">{{ stepForm.errors.starts_at }}</p>
                    </div>
                    <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                        <label for="ends_at" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> End Date </label>
                        <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                            <VueDatePicker text-input @closed="saveDate(index, income.ends_at,'ends_at')" v-model="income.ends_at"
                                class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy"
                                name="ends_at" id="ends_at" placeholder="dd/mm/yyyy" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.ends_at">{{ stepForm.errors.ends_at }}</p>
                    </div>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="belongs_to" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Belongs To</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" id="belongs_to" name="belongs_to" v-model="income.belongs_to"
                        class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="belongs_to" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(belongs_to, id) in formData.enums.belongs_to">{{ belongs_to }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.belongs_to">{{
                        stepForm.errors.belongs_to }}</p>
                </div>
        </div>
        <button type="button" @click="addIncome" v-if="stepForm.useIncome == true"
            class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <PlusCircleIcon class="w-6 h-6" />Add Income
        </button>
    </div>
</dynamic-form-wrapper></template>

<style scoped>.aaron-datepicker {
    --dp-background-color: rgb(49 63 167 / var(--tw-bg-opacity));
    --dp-text-color: rgb(236 245 255 / var(--tw-text-opacity));
    --dp-border-radius: 6px;
    --dp-border-color: rgb(70 84 190 / var(--tw-ring-opacity));
}</style>
