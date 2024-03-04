<script setup>
//FACTFIND:// you need to make one of these for every step
import {__error, autoS, autosaveT} from "@/autosave.js";
import {filteredProviders, fetchOptions} from "@/providers.js";

import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import VueDatePicker from "@vuepic/vue-datepicker";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';
import {changeToCurrency} from "@/currency.js";
import '@vuepic/vue-datepicker/dist/main.css'
import {computed, onMounted, ref, watch} from "vue";
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
                providers: [],
                account_types: [],
                frequencies: [],
            },
            model: {
                saving_assets: [{
                    id: null,
                    owner: null,
                    provider: null,
                    account_type: null,
                    name: null,
                    current_balance: null,
                    start_date: null,
                    end_date: null,
                    interest_rate: null,
                    is_retained: null,
                    retained_value: null,
                    regular_contributions: null,
                    contribution_amount: null,
                    frequency: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});



function formatAmount(e, index, dataField) {
    stepForm.saving_assets[index][dataField] = '';
    stepForm.saving_assets[index][dataField] = changeToCurrency(e.target.value);
    autosaveLocally()
}

function saveDate(index, value) {
    stepForm.saving_assets[index].purchased_at = value;
    autosaveLocally();
}

function addAsset() {
    if(Object.keys(props.formData.enums.owners).length === 1)
    {
        let owner = Object.keys(props.formData.enums.owners)[0];

        stepForm.saving_assets.push({
            id: null,
            owner: owner,
            provider: null,
            name: null,
            account_type: null,
            current_balance: null,
            start_date: null,
            end_date: null,
            interest_rate: null,
            is_retained: null,
            regular_contributions: null,
            contribution_amount: null,
            retained_value: null,
            frequency: null,
        });
    }
    else{
        stepForm.saving_assets.push({
            id: null,
            owner: null,
            provider: null,
            name: null,
            account_type: null,
            current_balance: null,
            start_date: null,
            end_date: null,
            interest_rate: null,
            is_retained: null,
            regular_contributions: null,
            contribution_amount: null,
            retained_value: null,
            frequency: null,
        });
    }
}

onMounted(() => {
    filteredProviders.value = props.formData.enums.providers;
})

const stepForm = useForm({
    saving_assets: props.formData.model.saving_assets
})


function removeAsset(index) {
    if(stepForm.saving_assets[index].id != null)
    {
        axios.delete('/api/assets/'+ stepForm.saving_assets[index].id).then(function (response){
            console.log(response.data)
        }).catch(function (e){
            console.log(e)
        });
    }
    stepForm.saving_assets.splice(index, 1);
}

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.saving_assets = props.formData.model.saving_assets
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
            <div v-for="(asset, index) in stepForm.saving_assets"
                 class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold">Savings Account {{ index + 1 }}</label>
                    <button type="button" @click="removeAsset(index)"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Delete Savings Account
                    </button>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-6">
                    <label for="first_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Provider</label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300">
                        <v-select
                            class="block rounded-md w-full border-0 bg-aaron-950 text-aaron-50 shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"
                             v-model="asset.provider"
                            @option:selected="autosaveLocally()"
                            @option:deselected="autosaveLocally()"
                            :options="filteredProviders"
                            @search="fetchOptions"
                        />
                    </div>
                 </div>




                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="relationship_type"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Account Type</label>
                    <select @change="autosaveLocally()" v-model="asset.account_type"
                            id="asset_type" name="asset_type"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="asset_type" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(type, id) in formData.enums.account_types">{{ type }}
                        </option>
                    </select>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Product Name</label>
                    <div class="flex shadow-sm rounded-md   focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="asset.name" type="text" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-aaron-800 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"  />
                    </div>
                </div>

                <!--                current_balance: null,-->
                <!--                start_date: null,-->
                <!--                end_date: null,-->
                <!--                interest_rate: null,-->
                <!--                is_retained: null,-->
                <!--                retained_value: null-->
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="owner"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Owner</label>
                    <select @change="autosaveLocally()" v-model="asset.owner"
                            id="asset_type" name="asset_type"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="asset_type" :value="null">-</option>
                        <option :id="id" :value="id"  v-for="(owner, id) in formData.enums.owners">
                            {{owner}}
                        </option>
                    </select>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Current Balance (£) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'current_balance')" type="currency" name="current_balance" id="current_balance"
                               :value="asset.current_balance"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>


                <div class="col-span-6 grid grid-cols-6 rounded-md bg-aaron-950 pt-2 p-4">
                    <h4 class="col-span-6 text-xl font-bold pt-2"> Regular Contributions </h4>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Are you making regular contributions?</label>
                        <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                            <input @change="autosaveLocally()" v-model="asset.regular_contributions" type="radio" id="true" :value="true" :checked="asset.regular_contributions == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                            <input @change="autosaveLocally()" v-model="asset.regular_contributions" type="radio" id="false" :value="false" :checked="asset.regular_contributions == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                        </div>
                    </div>

                    <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3" v-if="asset.regular_contributions == true">
                        <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Contribution Amount (£) </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="formatAmount($event, index, 'contribution_amount')" type="currency" name="contribution_amount" id="contribution_amount"
                                   :value="asset.contribution_amount"
                                   class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                        </div>
                    </div>

                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2" v-if="asset.regular_contributions == true">
                        <label for="frequency"
                               class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Contribution Frequency</label>
                        <select @change="autosaveLocally" v-model="asset.frequency"
                                id="frequency" name="frequency"
                                class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="frequency" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(provider, id) in formData.enums.frequencies">{{
                                    provider }}</option>
                        </select>
                    </div>

                </div>


                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="born_at"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2">Start date </label>
                    <div
                        class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveDate(index, asset.start_date)"
                                       class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="asset.start_date"
                                       name="start_date" id="start_date" placeholder="dd/mm/yyyy" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="born_at"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2">Maturity Date</label>
                    <div
                        class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveDate(index, asset.end_date)"
                                       class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="asset.end_date"
                                       name="end_date" id="end_date" placeholder="dd/mm/yyyy" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Interest Rate </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()"  v-model="asset.interest_rate"  type="number" min="0" max="1000" step="0.01" name="interest_rate" id="interest_rate"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="%" />
                    </div>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Is Retained?</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()"
                               v-model="asset.is_retained" type="radio" id="true" :value="true"
                               :checked="asset.is_retained == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()"
                               v-model="asset.is_retained" type="radio" id="false" :value="false"
                               :checked="asset.is_retained == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3" v-show="asset.is_retained">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Retained Value (£) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'retained_value')" type="currency" name="retained_value" id="retained_value"
                               :value="asset.retained_value"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>
            </div>
            <button type="button" @click="addAsset"
                    class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add Asset
            </button>
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
