<script setup>
//FACTFIND:// you need to make one of these for every step
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {usePage} from "@inertiajs/vue3";
import {useForm} from "laravel-precognition-vue-inertia";
import VueDatePicker from "@vuepic/vue-datepicker";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';

import '@vuepic/vue-datepicker/dist/main.css'
import {onMounted, ref, watch} from "vue";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                employment_status: []
            },
            model: {
                employment_details: [{
                    employment_status: 0,
                    intended_retirement_age: null,
                    occupation: null,
                    employer: null,
                    start_at: null,
                    end_at: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

function saveDate(index, value, dateId) {
    stepForm.employment_details[index][dateId] = value;
    autosaveT(stepForm,props.formData.submit_url);
}

function addEmployment() {
    stepForm.employment_details.push({
        employment_status: 0,
        intended_retirement_age: null,
        occupation: null,
        employer: null,
        start_at: null,
        end_at: null
    });
}

function removeEmployment(index) {
    stepForm.employment_details.splice(index, 1);
    autosaveT(stepForm,props.formData.submit_url);
}

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    employment_details: props.formData.model.employment_details
})


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
            <div v-for="(employment_detail, index) in stepForm.employment_details"
                class="grid gap-2 mb-8 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4"
                id="empdtls">
                <div class="col-span-6 flex flex-row justify-between">
                    <label class="font-bold">Employment {{ index + 1 }}</label>
                    <button type="button" @click="removeEmployment(index)"
                        class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Remove Employment
                    </button>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="employment_status" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Employment Status</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="employment_detail.employment_status"
                        id="employment_status" name="employment_status"
                        class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="employment_status" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(employmentStatus, id) in formData.enums.employment_status">{{
                            employmentStatus }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.employment_status">{{ stepForm.errors.employment_status }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="intended_retirement_age" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Intended Retirement Age </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md w-20">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="employment_detail.intended_retirement_age" type="text" name="intended_retirement_age" id="intended_retirement_age"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Age"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.intended_retirement_age">{{ stepForm.errors.intended_retirement_age }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="occupation" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Occupation </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="employment_detail.occupation" type="text" name="occupation" id="occupation"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Occupation"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.occupation">{{ stepForm.errors.occupation }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="employer" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Employer </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="employment_detail.employer" type="text" name="employer" id="employer"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Employer"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.employer">{{ stepForm.errors.employer }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="start_at" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Start Date </label>
                    <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveDate(index, employment_detail.start_at, 'start_at')"
                            class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="employment_detail.start_at"
                            name="start_at" id="start_at" placeholder="dd/mm/yyyy" />
                    </div>

                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.date_of_birth">{{ stepForm.errors.date_of_birth }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="end_at" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> End Date </label>
                    <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveDate(index, employment_detail.end_at, 'end_at')"
                            class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="employment_detail.end_at"
                            name="end_at" id="end_at" placeholder="dd/mm/yyyy" />
                    </div>

                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.date_of_birth">{{ stepForm.errors.date_of_birth }}</p>
                </div>
            </div>
            <button type="button" @click="addEmployment" 
                    class="float-right inline-flex items-center gap-x-1.5 mt-4 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add Employment
            </button>
        </div>
    </dynamic-form-wrapper>
</template>

<style scoped>
    .aaron-datepicker{
        --dp-background-color: rgb(49 63 167 / var(--tw-bg-opacity));
        --dp-text-color: rgb(236 245 255 / var(--tw-text-opacity));
        --dp-border-radius: 6px;
        --dp-border-color: rgb(70 84 190 / var(--tw-ring-opacity));
    }
</style>
