<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";

import {watch} from "vue";
import {usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                policy_types: [],
                loa_submitted: [],
                is_retained: []
            },
            model: {
                existing_pension_plans: [{
                    client_id: null,
                    employer: null,
                    administrator: null,
                    policy_type: null,
                    policy_number: null,
                    loa_submitted: null,
                    is_retained: null,
                    active_pension_member: null,
                    active_pension_member_reason_not: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    existing_pension_plans: props.formData.model.existing_pension_plans
})

async function autosaveLocally(index){
    // do not autosave if no policy_type selected
    if (stepForm.existing_pension_plans[index].policy_type != null) {
        props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
        stepForm.existing_pension_plans = props.formData.model.existing_pension_plans
    }
}
function removePensionPlan(index) {
    if(stepForm.existing_pension_plans[index].id != null) {
        axios.delete('/api/pensions/'+ stepForm.existing_pension_plans[index].id).then(function (response){
            console.log(response.data)
        }).catch(function (e){
            console.log(e)
        });
    }

    stepForm.existing_pension_plans.splice(index, 1);
}

function addPensionPlan() {
    stepForm.existing_pension_plans.push({
        client_id: null,
        employer: null,
        administrator: null,
        policy_type: null,
        policy_number: null,
        loa_submitted: null,
        is_retained: null,
        active_pension_member: null,
        active_pension_member_reason_not: null
    });
}
</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div v-for="(pension, index) in stepForm.existing_pension_plans"
                 class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold">Pension Plan {{ index + 1 }}</label>
                    <button type="button" @click="removePensionPlan(index)"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Remove Pension Plan
                    </button>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="employer" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Plan/Scheme Name </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="text" name="employer" id="employer"
                           @change="autosaveLocally(index)" v-model="pension.employer"
                           class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.employer">{{ stepForm.errors.employer }}</p>
                </div>
                <div v-if="pension.policy_type == 1" class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="administrator" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Provider/Administrator </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="text" name="administrator" id="administrator"
                           @change="autosaveLocally(index)"  v-model="pension.administrator"
                           class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.administrator">{{ stepForm.errors.administrator }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="policy_type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Type of Policy</label>
                    <select @change="autosaveLocally(index)" v-model="pension.policy_type"
                            id="policy_type" name="income_type"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="income_type" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(policy_type, id) in formData.enums.policy_types">{{ policy_type }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.policy_type">{{ stepForm.errors.policy_type }}</p>
                </div>
                <div v-if="pension.policy_type == 1" class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="policy_number" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Policy Number </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="text" name="policy_number" id="policy_number"
                               @change="autosaveLocally(index)" v-model="pension.policy_number"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.policy_number">{{ stepForm.errors.policy_number }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="loa_submitted" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">LOA Submitted</label>
                    <select @change="autosaveLocally(index)" v-model="pension.loa_submitted"
                            id="loa_submitted" name="loa_submitted"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="loa_submitted" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(loa_submitted, id) in formData.enums.loa_submitted">{{ loa_submitted }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.loa_submitted">{{ stepForm.errors.loa_submitted }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Policy being reviewed for transfer?</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally(index)"
                               v-model="pension.is_retained" type="radio" id="true" :value="1"
                               :checked="pension.is_retained == 1"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Transfer</label>
                        <input @change="autosaveLocally(index)"
                               v-model="pension.is_retained" type="radio" id="false" :value="0"
                               :checked="pension.is_retained == 0"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">Information Only</label>
                    </div>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-6">
                    <div class="flex items-center pt-2">
                        <div class="flex h-6 items-center">
                            <input id="active_pension_member" name="active_pension_member" type="checkbox"
                               v-model="pension.active_pension_member" @change="autosaveLocally(index)"
                               class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="dta_base_costs_availability" class="text-sm font-medium leading-6 text-aaron-50">Is client an active member?</label>
                        </div>
                    </div>
                    <div v-if="pension.active_pension_member === false" class="mt-4">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">If not, why not</label>
                        <div class="mt-2">
                            <textarea rows="3" name="dta_base_costs_available" id="active_pension_member_reason_not"
                              v-model="pension.active_pension_member_reason_not" @change="autosaveLocally(index)"
                              class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" @click="addPensionPlan"
                    class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add Pension Plan
            </button>
        </div>
    </dynamic-form-wrapper>
</template>
