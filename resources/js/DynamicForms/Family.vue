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

const emit = defineEmits(['autosaveStateChange'])


watch(autoS, (newValue, oldValue) => {
    emit('autosaveStateChange', newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                relationship_type: [],
                owners: [],
            },
            model: {
                dependents: [{
                    name: null,
                    born_at: null,
                    financially_dependent_until: null,
                    financial_dependent: null,
                    is_living_with_clients: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});




function saveDate(index, value, column) {
    stepForm.dependents[index][column] = value;
    autosaveT(stepForm,props.formData.submit_url);
}

function addDependent() {

    let blankRelationships = Object.keys(props.formData.enums.owners)
        .reduce((obj, key) => {
            obj[key] = {
                'is_related': true,
                'relationship_type': 5
            }
            return obj;
        }, {});

    stepForm.dependents.push({
        name: "",
        relationships: blankRelationships,
        born_at: null,
        financially_dependent_until: null,
        financial_dependent: false,
        is_living_with_clients: false
    });
}

function removeDependent(index) {
    stepForm.dependents[index].relationships = [];
    autosaveLocally();
}



const stepForm = useForm(`EditDependents${ props.formData.model.client_id }`, {
    dependents: props.formData.model.dependents
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.dependents = props.formData.model.dependents;
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
            <div v-for="(dependent, index) in stepForm.dependents"
                class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold">Dependent {{ index + 1 }}</label>
                    <button type="button" @click="removeDependent(index)"
                        class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Remove Dependent
                    </button>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Name </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="dependent.name" type="text" name="name" id="name"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Name" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.name">{{ stepForm.errors.name }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="born_at"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Date of
                        Birth </label>
                    <div
                        class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveDate(index, dependent.born_at,'born_at')"
                                       class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="dependent.born_at"
                                       name="born_at" id="born_at" placeholder="dd/mm/yyyy" />
                    </div>

                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.date_of_birth">{{
                            stepForm.errors.date_of_birth }}</p>
                </div>


                <div class="col-span-6 grid grid-cols-6 rounded-md bg-aaron-950 pt-2 p-4">
                    <h4 class="col-span-6 text-xl font-bold pt-2"> Relationship to Client(s) </h4>
                    <div class="col-span-6 grid grid-cols-6" v-for="(model,key,index) in formData.enums.owners">
                        <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                            <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Related to {{model}}</label>
                            <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                                <input @change="autosaveLocally()"
                                       v-model="dependent.relationships[key].is_related" type="radio" id="true" :value="true"
                                       :checked="dependent.relationships[key].is_related == true"
                                       class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                                <input @change="autosaveLocally()"
                                       v-model="dependent.relationships[key].is_related" type="radio" id="false" :value="false"
                                       :checked="dependent.relationships[key].is_related ==  false"
                                       class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                            </div>

                        </div>

                        <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2" v-if="dependent.relationships[key].is_related">
                            <label for="relationship_type"
                                   class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Nature of relationship</label>
                            <select @change="autosaveLocally()" v-model="dependent.relationships[key].relationship_type"
                                    id="relationship_type" name="relationship_type"
                                    class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                                <option id="relationship_type" :value="null">-</option>
                                <option :id="id" :value="id" v-for="(relationshipType, id) in formData.enums.relationship_type">{{
                                        relationshipType }}</option>
                            </select>

                        </div>
                    </div>

                </div>


                <div class="col-span-6 grid grid-cols-6 rounded-md bg-aaron-950 pt-2 p-4">
                    <h4 class="col-span-6 text-xl font-bold pt-2"> Financial Status </h4>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Financially
                            Dependent?</label>
                        <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                            <input @change="autosaveLocally()"
                                v-model="dependent.financial_dependent" type="radio" id="true" :value="true"
                                :checked="dependent.financial_dependent == true"
                                class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                            <input @change="autosaveLocally()"
                                v-model="dependent.financial_dependent" type="radio" id="false" :value="false"
                                :checked="dependent.financial_dependent == false"
                                class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                        </div>

                    </div>

                    <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3" v-if="dependent.financial_dependent">
                        <label for="financially_dependent_until"
                               class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Financially dependent until? </label>
                        <div
                            class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                            <VueDatePicker text-input @closed="saveDate(index, dependent.financially_dependent_until,'financially_dependent_until')"
                                           class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="dependent.financially_dependent_until"
                                           name="financially_dependent_until" id="financially_dependent_until" placeholder="dd/mm/yyyy" />
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Lives with
                        Client(s)</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()"
                            v-model="dependent.is_living_with_clients" type="radio" id="true" :value="true"
                            :checked="dependent.is_living_with_clients == true"
                            class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()"
                        v-model="dependent.is_living_with_clients" type="radio" id="false" :value="false"
                        :checked="dependent.is_living_with_clients == false"
                        class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                </div>
                <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.is_living_with_clients">{{
                    stepForm.errors.is_living_with_clients }}</p>
            </div>
        </div>
        <button type="button" @click="addDependent"
            class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <PlusCircleIcon class="w-6 h-6" />Add Dependent
        </button>
    </div>
</dynamic-form-wrapper></template>

<style scoped>.aaron-datepicker {
    --dp-background-color: rgb(49 63 167 / var(--tw-bg-opacity));
    --dp-text-color: rgb(236 245 255 / var(--tw-text-opacity));
    --dp-border-radius: 6px;
    --dp-border-color: rgb(70 84 190 / var(--tw-ring-opacity));
}</style>
