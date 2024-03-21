<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";

import {watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";
import {forceRefresh} from "@/calculateRiskAssesment.js";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})

watch(forceRefresh,(newValue,oldValue) => {
    if (newValue > oldValue) {
        autosaveLocally()
    }
})

const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                assessment_result_type: []
            },
            model: {
                assessment_result_investment: null,
                assessment_result_pension: null,
                using_calculated_risk_profile_investment: null,
                using_calculated_risk_profile_pension: null,
                adviser_recommendation_investment: null,
                adviser_recommendation_pension: null,
                why_investment: null,
                why_pension: null,
                knowledge_and_experience_investment: null,
                knowledge_and_experience_pension: null,
                capacity_for_loss_investment: null,
                capacity_for_loss_pension: null,
                attitude_to_risk: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

const stepForm = useForm({
    assessment_result_investment: props.formData.model.assessment_result_investment,
    assessment_result_pension: props.formData.model.assessment_result_pension,
    using_calculated_risk_profile_investment: props.formData.model.using_calculated_risk_profile_investment,
    using_calculated_risk_profile_pension: props.formData.model.using_calculated_risk_profile_pension,
    adviser_recommendation_investment: props.formData.model.adviser_recommendation_investment,
    adviser_recommendation_pension: props.formData.model.adviser_recommendation_pension,
    why_investment: props.formData.model.why_investment,
    why_pension: props.formData.model.why_pension
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.assessment_result_investment = props.formData.model.assessment_result_investment;
    stepForm.assessment_result_pension = props.formData.model.assessment_result_pension;
    stepForm.using_calculated_risk_profile_investment = props.formData.model.using_calculated_risk_profile_investment;
    stepForm.using_calculated_risk_profile_pension = props.formData.model.using_calculated_risk_profile_pension;
    stepForm.adviser_recommendation_investment = props.formData.model.adviser_recommendation_investment;
    stepForm.adviser_recommendation_pension = props.formData.model.adviser_recommendation_pension;
    stepForm.why_investment = props.formData.model.why_investment;
    stepForm.why_pension = props.formData.model.why_pension;
}

function investmentResult() {
    if (props.formData.model.assessment_result_investment != null && props.formData.model.knowledge_and_experience_investment !== null
        && props.formData.model.capacity_for_loss_investment !== null
        && props.formData.model.attitude_to_risk !== null) {
        return props.formData.model.assessment_result_investment
    }

    return 'No result available'
}

function pensionResult() {
    if (props.formData.model.assessment_result_pension != null && props.formData.model.knowledge_and_experience_pension !== null
        && props.formData.model.capacity_for_loss_pension !== null
        && props.formData.model.attitude_to_risk !== null) {
        return props.formData.model.assessment_result_pension
    }

    return 'No result available'
}

</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <form-errors :errors="usePage().props.errors"/>
            <div class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 p-4 rounded-md bg-aaron-950 grid grid-cols-2">
                    <label class="block text-base font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Investment Risk Assessment Result
                    </label>
                    <label class="block text-base font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                       {{investmentResult()}}
                    </label>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 p-4 rounded-md bg-aaron-950 grid grid-cols-2">
                    <label class="block text-base font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Pension Risk Assessment Result
                    </label>
                    <label class="block text-base font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        {{pensionResult()}}
                    </label>
                </div>
                <!-- INVESTMENT -->
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Use calculated investment risk profile?</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input type="radio" id="true" :value="true"
                               v-model="stepForm.using_calculated_risk_profile_investment"
                               :checked="stepForm.using_calculated_risk_profile_investment === true"
                               @change="autosaveLocally()"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input type="radio" id="false" :value="false"
                               v-model="stepForm.using_calculated_risk_profile_investment"
                               :checked="stepForm.using_calculated_risk_profile_investment === false"
                               @change="autosaveLocally()"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.using_calculated_risk_profile_investment">{{ stepForm.errors.using_calculated_risk_profile_investment }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div v-if="stepForm.using_calculated_risk_profile_investment === false">
                        <label for="inv_risk_profile_type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Recommendation for Investment Risk Profile</label>
                        <select id="inv_risk_profile_type" name="inv_risk_profile_type"
                                v-model="stepForm.adviser_recommendation_investment"
                                @change="autosaveLocally()"
                                class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="inv_risk_profile_type" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(type, id) in formData.enums.assessment_result_type">{{ type }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.adviser_recommendation_investment">{{ stepForm.errors.adviser_recommendation_investment }}</p>
                    </div>
                </div>
                <div v-if="stepForm.using_calculated_risk_profile_investment === false" class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Why?</label>
                    <div class="mt-2">
                        <textarea rows="3" name="why_investment" id="why_investment"
                          v-model="stepForm.why_investment"
                          @change="autosaveLocally()"
                          class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>
                <!-- PENSION -->
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Use calculated pension risk profile?</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input type="radio" id="true" :value="true"
                               v-model="stepForm.using_calculated_risk_profile_pension"
                               :checked="stepForm.using_calculated_risk_profile_pension === true"
                               @change="autosaveLocally()"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input type="radio" id="false" :value="false"
                               v-model="stepForm.using_calculated_risk_profile_pension"
                               :checked="stepForm.using_calculated_risk_profile_pension === false"
                               @change="autosaveLocally()"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.using_calculated_risk_profile_pension">{{ stepForm.errors.using_calculated_risk_profile_pension }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div v-if="stepForm.using_calculated_risk_profile_pension === false">
                        <label for="pension_risk_profile_type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Recommendation for Pension Risk Profile</label>
                        <select id="pension_risk_profile_type" name="pension_risk_profile_type"
                                v-model="stepForm.adviser_recommendation_pension"
                                @change="autosaveLocally()"
                                class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="pension_risk_profile_type" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(type, id) in formData.enums.assessment_result_type">{{ type }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.adviser_recommendation_pension">{{ stepForm.errors.adviser_recommendation_pension }}</p>
                    </div>
                </div>
                <div v-if="stepForm.using_calculated_risk_profile_pension === false" class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Why?</label>
                    <div class="mt-2">
                        <textarea rows="3" name="why_pension" id="why_pension"
                          v-model="stepForm.why_pension"
                          @change="autosaveLocally()"
                          class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </dynamic-form-wrapper>
</template>
