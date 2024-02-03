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

const emit = defineEmits(['autosaveStateChange'])

// const clientId = ;

watch(autoS, (newValue, oldValue) => {
    emit('autosaveStateChange', newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                owners: [],
                providers: [],
                account_types: []
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
                    valuation_at: null,
                    value: null,
                    retained_value: null,
                    is_retained: null
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
    stepform[type][index][dataField] = '';
    stepform[type][index][dataField] = changeToCurrency(e.target.value);
    autosaveT(stepForm,props.formData.submit_url)
}

function saveDate(index, value, type) {
    stepform[type][index].purchased_at = value;
    autosaveT(stepForm,props.formData.submit_url);
}



// onMounted(() => {
//     dateRef.value = props.formData.model.born_at;
// })

const stepForm = useForm(`EditPensions${ props.formData.model.client_id }`, {
    dc_pensions: props.formData.model.dc_pensions,
    db_pensions: props.formData.model.db_pensions,
})

function addDc() {
    let owner = Object.keys(props.formData.enums.owners)[0];
    stepForm.dc_pensions.push({
        id: null,
        pt: 'db',
        owner: owner,
        type: null,
        employer: null,
        administrator: null,
        policy_starts_at: null,
        policy_number: null,
        gross_contribution_percent: null,
        gross_contribution_absolute: null,
        employer_contribution_percent: null,
        valuation_at: null,
        value: null,
        retained_value: null,
        is_retained: null
    });
}


function addDb() {
    let owner = Object.keys(props.formData.enums.owners)[0];
    stepForm.db_pensions.push({
        id: null,
        pt: 'dc',
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



function removePension(index,type) {
    if(stepForm[type][index].id != null)
    {
        axios.delete('/api/pensions/'+ stepform[type][index].id).then(function (response){
            console.log(response.data)
        }).catch(function (e){
            console.log(e)
        });
    }
    stepform[type].splice(index, 1);
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
            DB Pensions
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
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Employer</label>
                    <div class="flex shadow-sm rounded-md   focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="pension.employer" type="text" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-aaron-800 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"  />
                    </div>
                </div>


                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="relationship_type"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Pension Status</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="pension.status"
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
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="pension.owner"
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
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="pension.retirement_age" type="text" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-aaron-800 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"  />
                    </div>
                </div>


                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Prospective Pension (standard) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'prospective_pension_standard','db_pension')" type="currency" name="pps" id="pps"
                               :value="pension.prospective_pension_standard"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Prospective Pension (max) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'prospective_pension_max','db_pension')" type="currency" name="ppm" id="ppm"
                               :value="pension.prospective_pension_max"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Prospective PCLS (standard) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'prospective_pcls_standard','db_pension')" type="currency" name="pps" id="pps"
                               :value="pension.prospective_pcls_standard"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Prospective PCLS (max) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'prospective_pcls_max','db_pension')" type="currency" name="ppm" id="ppm"
                               :value="pension.prospective_pcls_max"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="cetv" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Cash Equivalent Transfer Value </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'cetv','db_pension')" type="currency" name="cetv" id="cetv"
                               :value="pension.cetv"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="cetv_ends_at"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2">CETV Expiry Date</label>
                    <div
                        class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveDate(index, pension.cetv_ends_at,'db_pension')"
                                       class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="pension.cetv_ends_at"
                                       name="cetv_ends_at" id="cetv_ends_at" placeholder="dd/mm/yyyy" />
                    </div>
                </div>



            </div>
            <button type="button" @click="addDc"
                    class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add DC Pension
            </button>
            <button type="button" @click="addDb"
                    class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add DB Pension
            </button>
        </div>
    </dynamic-form-wrapper></template>

<style scoped>.aaron-datepicker {
    --dp-background-color: rgb(49 63 167 / var(--tw-bg-opacity));
    --dp-text-color: rgb(236 245 255 / var(--tw-text-opacity));
    --dp-border-radius: 6px;
    --dp-border-color: rgb(70 84 190 / var(--tw-ring-opacity));
}</style>
