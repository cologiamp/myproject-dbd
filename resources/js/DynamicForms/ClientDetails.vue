<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";

import {onMounted, ref, watch} from "vue";
import {usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";
import {changeToCurrency} from "@/currency.js";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                employment_status: [],
                workplace_pension_types: [],
                pension_review_transfer: [],
                retirement_options: []
            },
            model: {
                pension_recommendation: [{
                    employment_status: null,
                    current_employer_name: null,
                    workplace_pension_type: null,
                    employers_pension_name: null,
                    active_pension_member: null,
                    active_pension_member_reason_not: null,
                    active_pension_review_for_transfer: null,
                    active_pension_review_transfer_reason: null,
                    pension_draw_age: null,
                    retirement_option: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

function formatAmount(e, dataField) {
    stepForm.pension_recommendation[dataField] = '';
    stepForm.pension_recommendation[dataField] = changeToCurrency(e.target.value);

    autosaveLocally()
}

onMounted(()=>{
})

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    pension_recommendation: props.formData.model.pension_recommendation[0]
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.pension_recommendation = props.formData.model.pension_recommendation[0]
}
</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="employment_status" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Client Employment Status</label>
                    <select @change="autosaveLocally()" v-model="stepForm.pension_recommendation.employment_status" id="report_type" name="employment_status"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="report_for" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(status, id) in formData.enums.employment_status">{{ status }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.employment_status">{{ stepForm.errors.employment_status }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="current_employer_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Current Employer Name </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="text" name="current_employer_name" id="current_employer_name"
                           v-model="stepForm.pension_recommendation.current_employer_name"
                           @change="autosaveLocally()"
                           class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Employer Name" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.current_employer_name">{{ stepForm.errors.current_employer_name }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="workplace_pension_type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Type of workplace Pension Provided</label>
                    <select @change="autosaveLocally()" v-model="stepForm.pension_recommendation.workplace_pension_type" id="report_type" name="workplace_pension_type"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="report_for" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(pension_type, id) in formData.enums.workplace_pension_types">{{ pension_type }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.workplace_pension_type">{{ stepForm.errors.workplace_pension_type }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="employers_pension_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Name of employer's pension </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="text" name="employers_pension_name" id="employers_pension_name"
                           v-model="stepForm.pension_recommendation.employers_pension_name"
                           @change="autosaveLocally()"
                           class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Employer Pension Name" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.employers_pension_name">{{ stepForm.errors.employers_pension_name }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-6">
                    <div class="flex items-center pt-2">
                        <div class="flex h-6 items-center">
                            <input id="active_pension_member" name="active_pension_member" type="checkbox"
                               v-model="stepForm.pension_recommendation.active_pension_member" @change="autosaveLocally()"
                               class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="dta_base_costs_availability" class="text-sm font-medium leading-6 text-aaron-50">Is client an active member?</label>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">If not, why not</label>
                        <div class="mt-2">
                            <textarea rows="3" name="dta_base_costs_available" id="active_pension_member_reason_not"
                              v-model="stepForm.pension_recommendation.active_pension_member_reason_not"
                              @change="autosaveLocally()"
                              class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="active_pension_review_for_transfer" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Is this plan being reviewed for Transfer?</label>
                    <select @change="autosaveLocally()" v-model="stepForm.pension_recommendation.active_pension_review_for_transfer" id="pension_review_transfer" name="workplace_pension_type"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="pension_review_transfer" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(review, id) in formData.enums.pension_review_transfer">{{ review }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.active_pension_review_for_transfer">{{ stepForm.errors.active_pension_review_for_transfer }}</p>
                </div>
                <div v-if="stepForm.pension_recommendation.active_pension_review_for_transfer === 3"
                     class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">If other, why?</label>
                    <div class="mt-2">
                        <textarea rows="3" name="active_pension_review_transfer_reason" id="active_pension_review_transfer_reason"
                              v-model="stepForm.pension_recommendation.active_pension_review_transfer_reason"
                              @change="autosaveLocally()"
                              class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="pension_draw_age" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Age when client intends to draw benefits </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md w-20">
                        <input @change="autosaveLocally()" v-model="stepForm.pension_recommendation.pension_draw_age" type="text" name="pension_draw_age" id="pension_draw_age"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Age"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.pension_draw_age">{{ stepForm.errors.pension_draw_age }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="retirement_option" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Retirement option most likely at present?</label>
                    <select @change="autosaveLocally()" v-model="stepForm.pension_recommendation.retirement_option" id="retirement_option" name="retirement_option"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="retirement_option" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(retirement_option, id) in formData.enums.retirement_options">{{ retirement_option }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.retirement_option">{{ stepForm.errors.retirement_option }}</p>
                </div>
            </div>
        </div>
    </dynamic-form-wrapper>
</template>
