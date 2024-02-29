<script setup>
//FACTFIND:// you need to make one of these for every step
import {__error, autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm, usePage, router } from "@inertiajs/vue3";
import VueDatePicker from "@vuepic/vue-datepicker";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';
import Swal from "sweetalert2";

import '@vuepic/vue-datepicker/dist/main.css'
import { onBeforeMount, watch } from "vue";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS, (newValue, oldValue) => {
    emit('autosaveStateChange', newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                expenditure_types: [],
                frequencies: []
            },
            model: {
                expenditures: [{
                    expenditure_type: null,
                    description: null,
                    amount: null,
                    frequency: null,
                    currently_active: true,
                    known_end_date: false,
                    starts_at: null,
                    belongs_to: null,
                    ends_at: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    sectionIndex: String,
    errors: Object,
});

function saveDate(typeIndex, expIndex, value, dateId) {
    stepForm.expenditures[typeIndex][expIndex][dateId] = value;
    autosaveLocally();
}

function addExpenditure(typeIndex) {
    if (stepForm.expenditures[typeIndex]) {
        stepForm.expenditures[typeIndex].push({
            expenditure_type: parseInt(typeIndex),
            description: null,
            amount: null,
            frequency: null,
            currently_active: true,
            known_end_date: false,
            starts_at: null,
            belongs_to: null,
            ends_at: null
        });
    } else {
        stepForm.expenditures[typeIndex] = [{
            expenditure_type: parseInt(typeIndex),
            description: null,
            amount: null,
            frequency: null,
            currently_active: true,
            known_end_date: false,
            starts_at: null,
            belongs_to: null,
            ends_at: null
        }]
    }
}

function removeExpenditure(typeIndex, expIndex) {
    let expenditureId = stepForm.expenditures[typeIndex][expIndex]['expenditure_id']
    let deleteURL = `/api/expenditures/${ expenditureId }`;

    // delete expenditure record
    if(expenditureId) {
        axios.delete(deleteURL).then(response=>{
        }).catch(error=>{
            Swal.fire({
                title: 'Error: Something failed. Please try again later.',
                text: error.response.data.message,
            })
        });
    }

    //remove expenditure from props array
    stepForm.expenditures[typeIndex].splice(expIndex, 1);

    setTimeout(()=> {
        autosaveLocally();
      }
    ,500);

}

const stepForm = useForm(`EditExpenditures${ props.sectionIndex }${ props.formData.model.client_id }`, {
    expenditures: props.formData.model.expenditures != null ? props.formData.model.expenditures : []
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.expenditures = props.formData.model.expenditures;
    formatAmountOnload()
}


onBeforeMount(() => {
    formatAmountOnload();
})

function formatAmountOnload() {
    if (stepForm.expenditures != null) {
        Object.keys(props.formData.enums.expenditure_types).forEach(expType => {
            if(stepForm.expenditures[expType]) {

                stepForm.expenditures[expType].forEach(expenditure => {
                    if (expenditure['amount'] && expenditure['amount'] != null) {
                        expenditure['amount'] = changeToCurrency(expenditure['amount'].toString());
                    }
                });
            }
        });
    }
}

function formatAmount(e, typeIndex, expIndex, dataField) {
    stepForm.expenditures[typeIndex][expIndex][dataField] = '';
    stepForm.expenditures[typeIndex][expIndex][dataField] = changeToCurrency(e.target.value);
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

function expenditureStatus($event, typeIndex, expIndex, dataField) {
    if (dataField == 'currently_active') {
        if ($event.target.value == 'true') {
            stepForm.expenditures[typeIndex][expIndex]['starts_at'] = null
            autosaveLocally();
        }
    }

    if (dataField == 'known_end_date') {
        if ($event.target.value == 'false') {
            stepForm.expenditures[typeIndex][expIndex]['ends_at'] = null
            autosaveLocally();
        }
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
            <div v-for="(expenditureType, typeIndex) in formData.enums.expenditure_types" class="border-b-2 mb-6 last-of-type:border-b-0 last-of-type:mb-10" :class="stepForm.expenditures[typeIndex] ? 'pb-20' : 'pb-6'">
                <div class="flex flex-row justify-between md:col-span-6 md:pr-2 items-center">
                    <label class="font-bold text-white text-lg">{{ expenditureType }} Expenditure</label>
                    <PlusCircleIcon v-if="!stepForm.expenditures[typeIndex]" @click="addExpenditure(typeIndex)" class="w-8 h-8 fill-indigo-600"/>
                </div>
                <div v-for="(expenditure, expIndex) in stepForm.expenditures[typeIndex]" class="grid gap-2 border-b-2 border-aaron-500 border-dashed pb-12 last-of-type:border-none last-of-type:border-b-0 last-of-type:pb-0 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                    <div class="flex flex-row justify-end md:col-span-3 md:pr-2">

                    </div>
                    <div class="md:col-span-6 flex flex-row justify-between">
                        <label class="font-bold">{{ expenditureType }} {{ expIndex + 1 }}</label>
                        <button type="button" @click="removeExpenditure(typeIndex, expIndex)"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <XCircleIcon class="w-4 h-4" />Remove Expenditure
                        </button>
                    </div>
                    <div class="mt-2 md:col-span-3 sm:mt-0 md:pr-2">
                        <label for="expenditure_type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Expenditure Type</label>
                        <select @change="autosaveLocally()" v-model="expenditure.expenditure_type" :disabled="true"
                            id="expenditure_type" name="expenditure_type"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-aaron-700 disabled:text-aaron-50 disabled:border-slate-200 disabled:shadow-none">
                            <option id="expenditure_type" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(expenditure_type, id) in formData.enums.expenditure_types">{{ expenditure_type }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.expenditure_type">{{ stepForm.errors.expenditure_type }}</p>
                    </div>
                    <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                        <label for="description" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Description </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" type="text" name="description" id="description" v-model="expenditure.description"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Description" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.description">{{ stepForm.errors.description }}</p>
                    </div>
                    <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                        <label for="amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Amount </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" type="currency" name="amount" id="amount"
                                :value="expenditure.amount"
                                @input="formatAmount($event, typeIndex, expIndex, 'amount')"
                                class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.amount">{{ stepForm.errors.amount }}</p>
                    </div>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="frequency" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Frequency</label>
                        <select @change="autosaveLocally()" id="frequency" name="frequency" v-model="expenditure.frequency"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="frequency" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(frequency, id) in formData.enums.frequencies">{{ frequency }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.frequency">{{ stepForm.errors.frequency }}</p>
                    </div>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="currently_active" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Currently Active</label>
                        <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                            <input @change="expenditureStatus($event, typeIndex, expIndex, 'currently_active')" type="radio" id="true" :value="true" :checked="expenditure.currently_active == true" v-model="expenditure.currently_active" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                            <input type="radio" id="false" :value="false" :checked="expenditure.currently_active == false" v-model="expenditure.currently_active" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.currently_active">{{ stepForm.errors.currently_active }}</p>
                    </div>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="known_end_date" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Known End Date</label>
                        <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                            <input type="radio" id="true" :value="true" :checked="expenditure.known_end_date == true" v-model="expenditure.known_end_date" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                            <input @change="expenditureStatus($event, typeIndex, expIndex, 'known_end_date')" type="radio" id="false" :value="false" :checked="expenditure.known_end_date == false" v-model="expenditure.known_end_date" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.known_end_date">{{ stepForm.errors.known_end_date }}</p>
                    </div>
                    <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3 mb-8">
                            <label for="starts_at" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Start Date </label>
                            <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                                <VueDatePicker text-input @closed="saveDate(typeIndex, expIndex, expenditure.starts_at, 'starts_at')"
                                    class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="expenditure.starts_at"
                                    name="starts_at" id="starts_at" placeholder="dd/mm/yyyy" :required="expenditure.currently_active == false"/>
                            </div>
                            <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.starts_at">{{ stepForm.errors.starts_at }}</p>
                    </div>
                    <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3 mb-8">
                        <label for="ends_at" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> End Date </label>
                        <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                            <VueDatePicker text-input @closed="saveDate(typeIndex, expIndex, expenditure.ends_at, 'ends_at')"
                                class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="expenditure.ends_at"
                                name="ends_at" id="ends_at" placeholder="dd/mm/yyyy" :required="expenditure.known_end_date == true"/>
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.ends_at">{{ stepForm.errors.ends_at }}</p>
                    </div>
                </div>
                <button type="button" @click="addExpenditure(typeIndex)" v-if="stepForm.expenditures[typeIndex]"
                    class="float-right inline-flex items-center gap-x-1.5 mb-10 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <PlusCircleIcon class="w-6 h-6" />Add {{ expenditureType }} Expenditure
                </button>
            </div>
    </div>
</dynamic-form-wrapper></template>

<style scoped>.aaron-datepicker {
    --dp-background-color: rgb(49 63 167 / var(--tw-bg-opacity));
    --dp-text-color: rgb(236 245 255 / var(--tw-text-opacity));
    --dp-border-radius: 6px;
    --dp-border-color: rgb(70 84 190 / var(--tw-ring-opacity));
}</style>
