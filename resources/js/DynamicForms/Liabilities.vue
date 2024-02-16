<script setup>
//FACTFIND:// you need to make one of these for every step
import {__error, autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import VueDatePicker from "@vuepic/vue-datepicker";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';

import '@vuepic/vue-datepicker/dist/main.css'
import { onMounted, ref, watch } from "vue";
import {changeToCurrency} from "@/currency.js";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS, (newValue, oldValue) => {
    emit('autosaveStateChange', newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                owners: [],
                category: [],
                repayment_or_interest: []
            },
            model: {
                liabilities: [{
                    owner: null,
                    type: null,
                    repayment: null,
                    amount_outstanding: null,
                    monthly_repayment: null,
                    lender: null,
                    ends_at: null,
                    is_to_be_repaid: true,
                    interest_rate: null,
                    repay_details: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

function saveDate(index, value) {
    stepForm.liabilities[index].ends_at = value;
    autosaveLocally();
}

function addLiability() {
    stepForm.liabilities.push({
        owner: null,
        type: null,
        repayment: null,
        amount_outstanding: null,
        monthly_repayment: null,
        lender: null,
        interest_rate: null,
        ends_at: null,
        is_to_be_repaid: true,
        repay_details: null
    });
}

function formatAmount(e, index, dataField) {
    stepForm.liabilities[index][dataField] = '';
    stepForm.liabilities[index][dataField] = changeToCurrency(e.target.value);
    autosaveLocally()
}

onMounted(() => {
})

const stepForm = useForm({
    liabilities: props.formData.model.liabilities
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.liabilities = props.formData.model.liabilities;
}

function removeLiability(index) {
    console.log(JSON.stringify(stepForm.liabilities[index]));
    if(stepForm.liabilities[index].id != null)
    {
        let deleteURL = `/api/liabilities/${ stepForm.liabilities[index].id }`;

        axios.delete(deleteURL).then(response=>{
            console.log(response.data)
        }).catch(error=>{
            Swal.fire({
                title: 'Error: Something failed. Please try again later.',
                text: error.response.data.message,
            })
        });
    }

    stepForm.liabilities.splice(index, 1);
    autosaveLocally();
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
            <div v-for="(liability, index) in stepForm.liabilities"
                class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold">Liability {{ index + 1 }}</label>
                    <button type="button" @click="removeLiability(index)"
                        class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Remove Liability
                    </button>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="owner" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Owners(s)</label>
                    <select @change="autosaveLocally()" v-model="liability.owner"
                        id="owner" name="owner"
                        class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="owner" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(owner, id) in formData.enums.owners">{{ owner }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.owner">{{ stepForm.errors.owner }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Liability Category</label>
                    <select @change="autosaveLocally()" v-model="liability.type"
                        id="type" name="type" class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="type" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(type, id) in formData.enums.types">{{ type }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.type">{{ stepForm.errors.type }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="repayment" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Repayment or interest only?</label>
                    <select @change="autosaveLocally()" v-model="liability.repayment"
                        id="repayment" name="repayment"
                        class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="repayment" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(repayment, id) in formData.enums.repayment_or_interest">{{
                            repayment }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.repayment">{{ stepForm.errors.repayment }}</p>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Interest Rate </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()"  v-model="liability.interest_rate"  type="number" min="0" max="1000" step="0.01" name="interest_rate" id="interest_rate"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="%" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="amount_outstanding" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Amount outstanding </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'amount_outstanding')" type="currency" name="amount_outstanding" id="amount_outstanding"
                            :value="liability.amount_outstanding"
                            class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="monthly_repayment" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Payment amount (Monthly) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'monthly_repayment')" type="currency" name="monthly_repayment" id="monthly_repayment"
                            :value="liability.monthly_repayment"
                            class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="lender" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Lender </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" type="text" name="lender" id="lender" v-model="liability.lender" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Lender" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.lender">{{ stepForm.errors.lender }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="born_at"
                        class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> End date </label>
                    <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveDate(index, liability.ends_at)" v-model="liability.ends_at" class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" name="ends_at" id="ends_at" placeholder="dd/mm/yyyy" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.ends_at">{{ stepForm.errors.ends_at }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Whether liability is to be repaid?</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="liability.is_to_be_repaid" type="radio" id="true" :value="true"
                            :checked="liability.is_to_be_repaid == true"
                            class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()" v-model="liability.is_to_be_repaid" type="radio" id="false" :value="false"
                            :checked="liability.is_to_be_repaid == false"
                            class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                </div>
                <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.is_to_be_repaid">{{ stepForm.errors.is_to_be_repaid }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2">

                        <label v-if="liability.is_to_be_repaid == true" for="repay_details" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">If yes please provide details</label>
                        <label v-else for="repay_details" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">If no, why not</label>
                        <div class="mt-2">
                            <textarea @change="autosaveLocally()" rows="3" name="repay_details" id="repay_details" v-model="liability.repay_details" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950  shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>

                </div>
            </div>
            <button type="button" @click="addLiability"
                class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add Liabilities
            </button>
        </div>
    </dynamic-form-wrapper>
</template>

<style scoped>.aaron-datepicker {
    --dp-background-color: rgb(49 63 167 / var(--tw-bg-opacity));
    --dp-text-color: rgb(236 245 255 / var(--tw-text-opacity));
    --dp-border-radius: 6px;
    --dp-border-color: rgb(70 84 190 / var(--tw-ring-opacity));
}</style>
