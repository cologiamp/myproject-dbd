<script setup>
//FACTFIND:// you need to make one of these for every step
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import VueDatePicker from "@vuepic/vue-datepicker";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';

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
                // titles: []
            },
            model: {
                employment_status: null,
                intended_retirement_date: null,
                occupation: null,
                employer: null,
                start_at: null,
                end_at: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

let dateRef = ref();
function saveDate(value){
    dateRef.value = value;
    stepForm.date_of_birth = value;
    autosaveT(stepForm,props.formData.submit_url)
}

onMounted(()=>{
    dateRef.value = props.formData.model.born_at;
})

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    employment_status: props.formData.model.employment_status,
    intended_retirement_date: props.formData.model.intended_retirement_date,
    occupation: props.formData.model.occupation,
    employer: props.formData.model.employer,
    start_at: props.formData.model.start_at,
    end_at: props.formData.model.end_at
})


</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="employment_status" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Employment Status</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.employment_status" id="employment_status" name="employment_status" class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="employment_status" :value="null">-</option>
                        <!-- <option :id="id" :value="id" v-for="(title, id) in formData.enums.titles">{{ title }}</option> -->
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.employment_status">{{ stepForm.errors.employment_status }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="first_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Intended Retirement Age </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md w-20">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.intended_retirement_date" type="text" name="intended_retirement_date" id="intended_retirement_date"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Age"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.intended_retirement_date">{{ stepForm.errors.intended_retirement_date }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="first_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Occupation </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.occupation" type="text" name="occupation" id="occupation"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Occupation"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.occupation">{{ stepForm.errors.occupation }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="first_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Employer </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.employer" type="text" name="employer" id="employer"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Employer"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.employer">{{ stepForm.errors.employer }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="born_at" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Start Date </label>
                    <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @update:model-value="saveDate" class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" :model-value="dateRef" name="start_at" id="start_at"  placeholder="dd/mm/yyyy"/>
                    </div>

                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.date_of_birth">{{ stepForm.errors.date_of_birth }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="born_at" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> End Date </label>
                    <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @update:model-value="saveDate" class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" :model-value="dateRef" name="end_at" id="end_at"  placeholder="dd/mm/yyyy"/>
                    </div>

                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.date_of_birth">{{ stepForm.errors.date_of_birth }}</p>
                </div>
            </div>
            <div class="mt-10 float-right">
                <button type="button" class="inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add Employment
                </button>
            </div>
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
