<script setup>
//FACTFIND:// you need to make one of these for every step
import {__error, autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import VueDatePicker from "@vuepic/vue-datepicker";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';
import {changeToCurrency} from "@/currency.js";
import '@vuepic/vue-datepicker/dist/main.css'
import {computed, onMounted, ref, watch} from "vue";
import {fetchOptions, filteredProviders} from "@/providers.js";
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
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
                administrators: [],
                account_types: [],
                pension_statuses: [],
                pension_crystallised_statuses: [],
                pension_fund_types: [],
                frequencies: []
            },
            model: {
                dc_pensions: [{
                    id: null,
                    owner: null,
                    type: null,
                    employer: null,
                    administrator: null,
                    policy_starts_at: null,
                    policy_number: null,
                    gross_contribution_percent: null,
                    gross_contribution_absolute: null,
                    employer_contribution_percent: null,
                    employer_contribution_absolute: null,
                    crystallised_percentage: null,
                    crystallised_status: null,
                    valuation_at: null,
                    value: null,
                    retained_value: null,
                    is_retained: null,
                    funds: [],
                    frequency: null,
                }],
                db_pensions: [{
                    id: null,
                    owner: null,
                    status: null,
                    employer: null,
                    retirement_age: null,
                    prospective_pension_standard: null,
                    prospective_pension_max: null,
                    prospective_pcls_standard: null,
                    prospective_pcls_max: null,
                    cetv: null,
                    cetv_ends_at: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});



function formatAmount(e, index, dataField, type) {
    stepForm[type][index][dataField] = '';
    stepForm[type][index][dataField] = changeToCurrency(e.target.value);
    autosaveLocally()
}

function formatFundAmount(e, index, dataField, fundId, type) {
    stepForm[type][index]['funds'][fundId][dataField] = '';
    stepForm[type][index]['funds'][fundId][dataField] = changeToCurrency(e.target.value);
    autosaveLocally()
}

function saveDate(index, value, type) {
    stepForm[type][index].purchased_at = value;
    autosaveLocally();
}



// onMounted(() => {
//     dateRef.value = props.formData.model.born_at;
// })

const stepForm = useForm({
    dc_pensions: props.formData.model.dc_pensions,
    db_pensions: props.formData.model.db_pensions,
})
async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.dc_pensions = props.formData.model.dc_pensions;
    stepForm.db_pensions = props.formData.model.db_pensions;
}
function addDc() {
    let owner = Object.keys(props.formData.enums.owners)[0];
    stepForm.dc_pensions.push({
        id: null,
        pt: 'dc',
        owner: owner,
        type: null,
        employer: null,
        administrator: null,
        policy_starts_at: null,
        policy_number: null,
        gross_contribution_percent: null,
        gross_contribution_absolute: null,
        employer_contribution_percent: null,
        crystallised_percentage: null,
        crystallised_status: null,
        valuation_at: null,
        value: null,
        retained_value: null,
        is_retained: null,
        funds: [],
        frequency: null
    });
}


function addDb() {
    let owner = Object.keys(props.formData.enums.owners)[0];
    stepForm.db_pensions.push({
        id: null,
        pt: 'db',
        owner: owner,
        status: null,
        employer: null,
        retirement_age: null,
        prospective_pension_standard: null,
        prospective_pension_max: null,
        prospective_pcls_standard: null,
        prospective_pcls_max: null,
        cetv: null,
        cetv_ends_at: null
    });
}

function addFund(index){
    console.log((stepForm.dc_pensions[index]));
    stepForm.dc_pensions[index].funds.push({
        fund_name: null,
        fund_type: null,
        current_fund_value: null,
        current_transfer_value: null
    })
}



function removePensionFund(index,pensionIndex) {
    let model = stepForm['dc_pensions'][index]['funds'][pensionIndex];
    if(model.id != null)
    {
        axios.delete('/api/pension-funds/' + model.id).then(function (response){
            console.log(response.data)
        }).catch(function (e){
            console.log(e)
        });
    }
    stepForm['dc_pensions'][index]['funds'].splice(pensionIndex, 1);
}


function removePension(index,type) {
    if(stepForm[type][index].id != null)
    {
        axios.delete('/api/pensions/'+ stepForm[type][index].id).then(function (response){
            console.log(response.data)
        }).catch(function (e){
            console.log(e)
        });
    }
    stepForm[type].splice(index, 1);
}

</script>
<style>
.ph-style .vs__search::placeholder {
    color: #6f707d;
    font-size: 14px;
}
</style>
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
            <div class="mb-6">
                <button type="button" @click="addDc"
                        class="float-right mr-0 inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <PlusCircleIcon class="w-6 h-6" />Add DC Pension
                </button>
                <button type="button" @click="addDb"
                        class="float-right mr-3 inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <PlusCircleIcon class="w-6 h-6" />Add DB Pension
                </button>
            </div>
            <h3 class="text-2xl mt-2 mb-2 text-aaron-400" v-if="stepForm.db_pensions && stepForm.db_pensions.length > 0">DB Pensions</h3>
            <div v-for="(pension, index) in stepForm.db_pensions"
                 class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">

                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold">DB Pension {{ index + 1 }}</label>
                    <button type="button" @click="removePension(index,'db_pensions')"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Delete DB Pension
                    </button>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Scheme Name</label>
                    <div class="flex shadow-sm rounded-md   focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="pension.employer" type="text" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-aaron-800 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"  />
                    </div>
                </div>


                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="relationship_type"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Pension Status</label>
                    <select @change="autosaveLocally()" v-model="pension.status"
                            id="status" name="status"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="status" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(type, id) in formData.enums.pension_statuses">{{ type }}
                        </option>
                    </select>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="owner"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Owner</label>
                    <select @change="autosaveLocally()" v-model="pension.owner"
                            id="owner" name="owner"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="owner" :value="null">-</option>
                        <option :id="id" :value="id"  v-for="(owner, id) in formData.enums.owners">
                            {{owner}}
                        </option>
                    </select>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Retirement Age</label>
                    <div class="flex shadow-sm rounded-md   focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="pension.retirement_age" type="text" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-aaron-800 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"  />
                    </div>
                </div>


                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Prospective Pension (standard) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'prospective_pension_standard','db_pensions')" type="currency" name="pps" id="pps"
                               :value="pension.prospective_pension_standard"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Prospective Pension (max) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'prospective_pension_max','db_pensions')" type="currency" name="ppm" id="ppm"
                               :value="pension.prospective_pension_max"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Prospective PCLS (standard) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'prospective_pcls_standard','db_pensions')" type="currency" name="pps" id="pps"
                               :value="pension.prospective_pcls_standard"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Prospective PCLS (max) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'prospective_pcls_max','db_pensions')" type="currency" name="ppm" id="ppm"
                               :value="pension.prospective_pcls_max"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="cetv" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Cash Equivalent Transfer Value </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'cetv','db_pensions')" type="currency" name="cetv" id="cetv"
                               :value="pension.cetv"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="cetv_ends_at"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2">CETV Expiry Date</label>
                    <div
                        class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveDate(index, pension.cetv_ends_at,'db_pensions')"
                                       class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="pension.cetv_ends_at"
                                       name="cetv_ends_at" id="cetv_ends_at" placeholder="dd/mm/yyyy" />
                    </div>
                </div>



            </div>

            <h3 class="text-2xl mt-2 mb-2 text-aaron-400" v-if="stepForm.dc_pensions && stepForm.dc_pensions.length > 0">DC Pensions</h3>
            <div v-for="(pension, index) in stepForm.dc_pensions"
                 class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">

                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold">DC Pension {{ index + 1 }}</label>
                    <button type="button" @click="removePension(index,'dc_pensions')"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Delete DC Pension
                    </button>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Employer</label>
                    <div class="flex shadow-sm rounded-md   focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="pension.employer" type="text" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-aaron-800 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"  />
                    </div>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="owner"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Owner</label>
                    <select @change="autosaveLocally()" v-model="pension.owner"
                            id="owner" name="owner"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="owner" :value="null">-</option>
                        <option :id="id" :value="id"  v-for="(owner, id) in formData.enums.owners">
                            {{owner}}
                        </option>
                    </select>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="type"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Pension Type</label>
                    <select @change="autosaveLocally()" v-model="pension.type"
                            id="type" name="type"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="type" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(type, id) in formData.enums.pension_types">{{ type }}
                        </option>
                    </select>
                </div>

<!--                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">-->
<!--                    <label for="administrator"-->
<!--                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Administrator</label>-->
<!--                    <select @change="autosaveLocally()" v-model="pension.administrator"-->
<!--                            id="administrator" name="administrator"-->
<!--                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">-->
<!--                        <option id="administrator" :value="null">-</option>-->
<!--                        <option :id="id" :value="id" v-for="(type, id) in formData.enums.administrators">{{ type }}-->
<!--                        </option>-->
<!--                    </select>-->
<!--                </div>-->
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-6">
                    <label for="first_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Provider</label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300">
                        <v-select
                            placeholder="Begin typing a provider name"
                            class="ph-style block rounded-md w-full border-0 bg-aaron-950 text-aaron-50 shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"
                            v-model="pension.administrator"
                            @option:selected="autosaveLocally"
                            @option:deselected="autosaveLocally"
                            :options="filteredProviders"
                            @search="fetchOptions"
                        />
                    </div>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Policy Number</label>
                    <div class="flex shadow-sm rounded-md   focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="pension.policy_number" type="text" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-aaron-800 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"  />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="policy_starts_at"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2">Policy Start Date</label>
                    <div
                        class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveDate(index, pension.policy_starts_at,'dc_pensions')"
                                       class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="pension.policy_starts_at"
                                       name="policy_starts_at" id="policy_starts_at" placeholder="dd/mm/yyyy" />
                    </div>
                </div>


                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Personal Contribution (gross) (%) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="pension.gross_contribution_percent" type="number" name="gross_contribution_percent" id="gross_contribution_percent"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="%" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Personal Contribution (gross) (£) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'gross_contribution_absolute','dc_pensions')" type="currency" name="ppm" id="ppm"
                               :value="pension.gross_contribution_absolute"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="employer_contribution_percent" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Employer Contribution (%) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input  type="number" name="employer_contribution_percent" id="employer_contribution_percent"
                                @change="autosaveLocally()" v-model="pension.employer_contribution_percent"
                                class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2
                                text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50
                                 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="%" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="employer_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Employer Contribution (£) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'employer_contribution_absolute','dc_pensions')" type="currency" name="ppm" id="ppm"
                               :value="pension.employer_contribution_absolute"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="frequency"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Contribution Frequency</label>
                    <select @change="autosaveLocally" v-model="pension.frequency"
                            id="frequency" name="frequency"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="frequency" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(provider, id) in formData.enums.frequencies">{{
                                provider }}</option>
                    </select>
                </div><br>


                    <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                        <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Current Value (£) </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="formatAmount($event, index, 'value','dc_pensions')" type="currency" name="current_value" id="current_value"
                                   :value="pension.value"
                                   class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                        </div>
                    </div>




                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="born_at"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2">Valuation Date</label>
                    <div
                        class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveDate(index, pension.valuation_at, 'dc_pensions')"
                                       class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="pension.valuation_at"
                                       name="valuation_at" id="valuation_at" placeholder="dd/mm/yyyy" />
                    </div>
                </div>


                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Is Retained?</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()"
                               v-model="pension.is_retained" type="radio" id="true" :value="true"
                               :checked="pension.is_retained == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()"
                               v-model="pension.is_retained" type="radio" id="false" :value="false"
                               :checked="pension.is_retained == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3" v-show="pension.is_retained">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Retained Value (£) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'retained_value','dc_pensions')" type="currency" name="retained_value" id="retained_value"
                               :value="pension.retained_value"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="col-span-6 grid grid-cols-6 rounded-md bg-aaron-950 pt-2 p-4">
                    <h4 class="col-span-6 text-xl font-bold pt-2"> Crystallisation </h4>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="type"
                               class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Crystallised Status</label>
                        <select @change="autosaveLocally()" v-model="pension.crystallised_status"
                                id="crystallised_status" name="crystallised_status"
                                class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="type" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(type, id) in formData.enums.pension_crystallised_statuses">{{ type }}
                            </option>
                        </select>
                    </div>
                    <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3" v-show="pension.crystallised_status == 2">
                        <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Part Crystallised (%) </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" type="number" max="100" min="0" step="0.01" name="crystallised_percentage" id="retained_value"
                                   v-model="pension.crystallised_percentage"
                                   class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="%" />
                        </div>
                    </div>

                </div>


                <div class="col-span-6 rounded-md bg-aaron-950 pt-2 p-4">
                    <h4 class="col-span-6 text-xl font-bold pt-2"> Details </h4>

                    <div class="col-span-6 m-2 grid grid-cols-6 rounded-md bg-aaron-900 pt-2 p-4" v-for="(value, key) in pension.funds">
                        <h4 class="col-span-6 text-xl font-bold pt-2"> Fund {{ key + 1 }} </h4>
                        <div class="md:col-span-6 flex flex-row justify-between">
                            <label class="font-bold">DC Pension {{ index + 1 }}</label>
                            <button type="button" @click="removePensionFund(index,key)"
                                    class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                <XCircleIcon class="w-4 h-4" />Delete Fund
                            </button>
                        </div>
                        <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                            <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Fund Name</label>
                            <div class="flex shadow-sm rounded-md   focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                                <input @change="autosaveLocally()" v-model="value.fund_name" type="text" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-aaron-800 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"  />
                            </div>
                        </div>
                        <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                            <label for="type"
                                   class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Fund Type</label>
                            <select @change="autosaveLocally()" v-model="value.fund_type"
                                    id="fund_type" name="fund_type"
                                    class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                                <option id="type" :value="null">-</option>
                                <option :id="id" :value="id" v-for="(type, id) in formData.enums.pension_fund_types">{{ type }}
                                </option>
                            </select>
                        </div>
                        <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                            <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Current Fund Value (£) </label>
                            <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                                <input @change="formatFundAmount($event, index, 'current_fund_value', key,'dc_pensions')" type="currency" name="current_fund_value" id="current_fund_value"
                                       :value="value.current_fund_value"
                                       class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                            </div>
                        </div>
                        <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                            <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Current Transfer Value (£) </label>
                            <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                                <input @change="formatFundAmount($event, index, 'current_transfer_value', key,'dc_pensions')" type="currency" name="current_transfer_value" id="current_transfer_value"
                                       :value="value.current_transfer_value"
                                       class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                            </div>
                        </div>

                    </div>

                    <button type="button" @click="addFund(index)"
                            class="float-right mr-3 inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <PlusCircleIcon class="w-6 h-6" />Add Fund
                    </button>
                </div>

            </div>

            <div v-if="stepForm.db_pensions && stepForm.db_pensions.length > 0">
                <button type="button" @click="addDc"
                        class="float-right mr-3 inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <PlusCircleIcon class="w-6 h-6" />Add DC Pension
                </button>
                <button type="button" @click="addDb"
                        class="float-right mr-3 inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <PlusCircleIcon class="w-6 h-6" />Add DB Pension
                </button>
            </div>
        </div>
    </dynamic-form-wrapper></template>

<style scoped>.aaron-datepicker {
    --dp-background-color: rgb(49 63 167 / var(--tw-bg-opacity));
    --dp-text-color: rgb(236 245 255 / var(--tw-text-opacity));
    --dp-border-radius: 6px;
    --dp-border-color: rgb(70 84 190 / var(--tw-ring-opacity));
}

>>> {
    --vs-controls-color: red;

    --vs-dropdown-bg: #313fa7;
    --vs-dropdown-color: black;
    --vs-dropdown-option-color: $bg-aaron-700;

    --vs-selected-bg: #313fa7;
    --vs-selected-color: #eeeeee;

    --vs-search-input-color: #eeeeee;

    --vs-dropdown-option--active-bg: #0b0f28;
    --vs-dropdown-option--active-color: #eeeeee;
}
</style>
