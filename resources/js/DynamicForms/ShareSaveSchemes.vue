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
import { watch} from "vue";

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
            },
            model: {
                schemes: [{
                    id: null,
                    owner: null,
                    name: null,
                    option_price: null,
                    monthly_saving: null,
                    number_of_shares: null,
                    matures_at: null,
                    start_at: null,
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});



function formatAmount(e, index, dataField) {
    stepForm.schemes[index][dataField] = '';
    stepForm.schemes[index][dataField] = changeToCurrency(e.target.value);
    autosaveLocally()
}

function saveMaturesAt(index, value) {
    stepForm.schemes[index].matures_at = value;
    autosaveLocally();
}

function saveStartsAt(index, value) {
    stepForm.schemes[index].start_at = value;
    autosaveLocally();
}

function addScheme() {
    stepForm.schemes.push({
        id: null,
        owner: null,
        name: null,
        option_price: null,
        monthly_saving: null,
        number_of_shares: null,
        matures_at: null,
        start_at: null,
    });
}


const stepForm = useForm({
    schemes: props.formData.model.schemes
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.schemes = props.formData.model.schemes;
}

function removeScheme(index) {
    if(stepForm.schemes[index].id != null)
    {
        axios.delete('/api/share-save-schemes/'+ stepForm.schemes[index].id).then(function (response){
            console.log(response.data)
        }).catch(function (e){
            console.log(e)
        });
    }
    stepForm.schemes.splice(index, 1);
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
            <div v-for="(scheme, index) in stepForm.schemes"
                 class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold">Share Save Scheme {{ index + 1 }}</label>
                    <button type="button" @click="removeScheme(index)"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Delete Scheme
                    </button>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="owner"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Owner</label>
                    <select @change="autosaveLocally()" v-model="scheme.owner"
                            id="owner" name="owner"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="owner" :value="null">-</option>
                        <option :id="id" :value="id"  v-for="(owner, id) in formData.enums.owners">
                            {{owner}}
                        </option>
                    </select>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Name</label>
                    <div class="flex shadow-sm rounded-md   focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="scheme.name" type="text" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-aaron-800 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"  />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Option Price (£) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'option_price')" type="currency" name="option_price" id="option_price"
                               :value="scheme.option_price"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>


                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Monthly Saving (£) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'monthly_saving')" type="currency" name="monthly_saving" id="monthly_saving"
                               :value="scheme.monthly_saving"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Number of Shares</label>
                    <div class="flex shadow-sm rounded-md   focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="scheme.number_of_shares" type="number" step="1" min="0" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-aaron-800 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"  />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="born_at"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2">Start Date</label>
                    <div
                        class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker
                            :enable-time-picker="false"
                            text-input @closed="saveStartsAt(index, scheme.start_at)"
                            class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="scheme.start_at"
                            name="start_at" id="start_at" placeholder="dd/mm/yyyy" />
                    </div>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="born_at"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2">Maturity Date</label>
                    <div
                        class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveMaturesAt(index, scheme.matures_at)"
                                       class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="scheme.matures_at"
                                       name="matures_at" id="matures_at" placeholder="dd/mm/yyyy" />
                    </div>
                </div>
            </div>
            <button type="button" @click="addScheme"
                    class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add Scheme
            </button>
        </div>
    </dynamic-form-wrapper></template>

<style scoped>.aaron-datepicker {
    --dp-background-color: rgb(49 63 167 / var(--tw-bg-opacity));
    --dp-text-color: rgb(236 245 255 / var(--tw-text-opacity));
    --dp-border-radius: 6px;
    --dp-border-color: rgb(70 84 190 / var(--tw-ring-opacity));
}</style>
