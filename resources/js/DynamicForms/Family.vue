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
                relationship_type: null,
                born_at: null,
                financial_dependant: null,
                is_living_with_clients: null
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
    relationship_type: props.formData.model.relationship_type,
    born_at: props.formData.model.born_at,
    financial_dependant: props.formData.model.financial_dependant,
    is_living_with_clients: props.formData.model.is_living_with_clients
})


</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="relationship_type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Relationship</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.relationship_type" id="relationship_type" name="relationship_type" class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="relationship_type" :value="null">-</option>
                        <!-- <option :id="id" :value="id" v-for="(title, id) in formData.enums.titles">{{ title }}</option> -->
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.relationship_type">{{ stepForm.errors.relationship_type }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="born_at" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Date of Birth </label>
                    <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @update:model-value="saveDate" class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" :model-value="dateRef" name="born_at" id="born_at"  placeholder="dd/mm/yyyy"/>
                    </div>

                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.date_of_birth">{{ stepForm.errors.date_of_birth }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Financially Dependant</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.financial_dependant" type="radio" id="true" :value="true" :checked="stepForm.financial_dependant == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.financial_dependant" type="radio" id="false" :value="false" :checked="stepForm.financial_dependant == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.financial_dependant">{{ stepForm.errors.financial_dependant }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Lives with Client(s)</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.is_living_with_clients" type="radio" id="true" :value="true" :checked="stepForm.is_living_with_clients == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.is_living_with_clients" type="radio" id="false" :value="false" :checked="stepForm.is_living_with_clients == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.is_living_with_clients">{{ stepForm.errors.is_living_with_clients }}</p>
                </div>
            </div>
            <div class="mt-10 float-right">
                <button type="button" class="inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add Dependent
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
