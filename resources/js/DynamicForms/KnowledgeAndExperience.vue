<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import {calculateKnE} from "@/calculateRiskAssesment.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";

import {watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";
import ExperienceRatingTable from "@/Components/ExperienceRatingTable.vue";
import {InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})

const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                risk_assessment_cash: [],
                risk_assessment_bonds: [],
                risk_assessment_equities: [],
                risk_assessment_insurance: []
            },
            model: {
                knowledge: {
                    type: null,
                    particular_issues: null,
                    level_of_knowledge: null,
                    aware_of_market_fluctuations: null,
                    comfort_of_fluctuations: null,
                    active_interest: null,
                    discretionary_experience: null,
                    ever_taken_invest_advice: null,
                    experience_buying_cash: null,
                    experience_buying_bonds: null,
                    experience_buying_equities: null,
                    experience_buying_insurance: null,
                    execution_only_experience: null,
                    experience_details: null,
                    experience_of_annuities: null,
                    experience_of_income_drawdown: null,
                    experience_of_phased_retirement: null,
                    spoken_to_pensionwise: null
                },
                risk_outcome_id: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
    sidebarItemsLength: Number
});

const stepForm = useForm({
    id: props.formData.model.knowledge.id,
    type: props.formData.model.knowledge.type,
    particular_issues: props.formData.model.knowledge.particular_issues,
    level_of_knowledge: props.formData.model.knowledge.level_of_knowledge,
    aware_of_market_fluctuations: props.formData.model.knowledge.aware_of_market_fluctuations,
    comfort_of_fluctuations: props.formData.model.knowledge.comfort_of_fluctuations,
    active_interest: props.formData.model.knowledge.active_interest,
    discretionary_experience: props.formData.model.knowledge.discretionary_experience,
    ever_taken_invest_advice: props.formData.model.knowledge.ever_taken_invest_advice,
    experience_buying_cash: JSON.parse(props.formData.model.knowledge.experience_buying_cash),
    experience_buying_bonds: JSON.parse(props.formData.model.knowledge.experience_buying_bonds),
    experience_buying_equities: JSON.parse(props.formData.model.knowledge.experience_buying_equities),
    experience_buying_insurance: JSON.parse(props.formData.model.knowledge.experience_buying_insurance),
    experience_details: props.formData.model.knowledge.experience_details,
    execution_only_experience: props.formData.model.knowledge.execution_only_experience, //Q7 pension
    experience_of_annuities: props.formData.model.knowledge.experience_of_annuities,
    experience_of_income_drawdown: props.formData.model.knowledge.experience_of_income_drawdown,
    experience_of_phased_retirement: props.formData.model.knowledge.experience_of_phased_retirement,
    spoken_to_pensionwise: props.formData.model.knowledge.spoken_to_pensionwise,
    risk_outcome_id: props.formData.model.risk_outcome_id
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.id = props.formData.model.knowledge.id;
    stepForm.type = props.formData.model.knowledge.type;
    stepForm.particular_issues = props.formData.model.knowledge.particular_issues;
    stepForm.level_of_knowledge = props.formData.model.knowledge.level_of_knowledge;
    stepForm.aware_of_market_fluctuations = props.formData.model.knowledge.aware_of_market_fluctuations;
    stepForm.comfort_of_fluctuations = props.formData.model.knowledge.comfort_of_fluctuations;
    stepForm.active_interest = props.formData.model.knowledge.active_interest;
    stepForm.discretionary_experience = props.formData.model.knowledge.discretionary_experience;
    stepForm.ever_taken_invest_advice = props.formData.model.knowledge.ever_taken_invest_advice;
    stepForm.experience_buying_cash = JSON.parse(props.formData.model.knowledge.experience_buying_cash);
    stepForm.experience_buying_bonds = JSON.parse(props.formData.model.knowledge.experience_buying_bonds);
    stepForm.experience_buying_equities = JSON.parse(props.formData.model.knowledge.experience_buying_equities);
    stepForm.experience_buying_insurance = JSON.parse(props.formData.model.knowledge.experience_buying_insurance);
    stepForm.experience_details = props.formData.model.knowledge.experience_details;
    stepForm.execution_only_experience = props.formData.model.knowledge.execution_only_experience; //Q7 pension
    stepForm.experience_of_annuities = props.formData.model.knowledge.experience_of_annuities; //Q10 pension
    stepForm.experience_of_income_drawdown = props.formData.model.knowledge.experience_of_income_drawdown; //Q10 pension
    stepForm.experience_of_phased_retirement = props.formData.model.knowledge.experience_of_phased_retirement; //Q10 pension
    stepForm.spoken_to_pensionwise = props.formData.model.knowledge.spoken_to_pensionwise; //Q10 pension
    stepForm.risk_outcome_id = props.formData.model.risk_outcome_id;
}

function setRating(data) {
    JSON.stringify(data)
    autosaveLocally()

}

function submitAssessment() {
    calculateKnE(stepForm.experience_buying_equities, stepForm.type, props.formData.model.risk_outcome_id, props.sidebarItemsLength);
    autosaveLocally();
}

function isSubmitDisabled() {
    if (isRatingSet(stepForm.experience_buying_cash) === false ||
        isRatingSet(stepForm.experience_buying_bonds) === false || isRatingSet(stepForm.experience_buying_equities) === false ||
        isRatingSet(stepForm.experience_buying_insurance) === false) {
        return true;
    }

    return false;
}

function isRatingSet(rangeData) {
    let flag = true;
    Object.entries(rangeData).forEach(data => {
        const [key, item] = data;
        if (item.value === null) {
            flag = false;
        }
    });

    return flag;
}

</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <form-errors :errors="usePage().props.errors"/>
            <div class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <!-- Q1 -->
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Are there any particular issues which we should be aware of in terms of your level of education, profession or former profession which are relevant to your investment knowledge or experience?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.particular_issues"
                               :checked="stepForm.particular_issues === true"
                               type="radio" id="true" :value="true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                    <input @change="autosaveLocally()" v-model="stepForm.particular_issues"
                               :checked="stepForm.particular_issues === false"
                               type="radio" id="false" :value="false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.particular_issues">{{ stepForm.errors.particular_issues }}</p>
                </div>
                <!-- Q2 -->
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Which option best describes your knowledge of investment and pension products?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.level_of_knowledge"
                               :checked="stepForm.level_of_knowledge === 1"
                               type="radio" id="no-knowledge" :value="1"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="no-knowledge" class="ml-2 block text-sm font-medium leading-6 text-white">
                            I have no knowledge of investments whatsoever.
                        </label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.level_of_knowledge"
                               :checked="stepForm.level_of_knowledge === 2"
                               type="radio" id="some-knowledge" :value="2"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="some-knowledge" class="ml-2 block text-sm font-medium leading-6 text-white">
                            I have some knowledge of investments
                        </label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.level_of_knowledge"
                               :checked="stepForm.level_of_knowledge === 3"
                               type="radio" id="reasonable-knowledge" :value="3"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="reasonable-knowledge" class="ml-2 block text-sm font-medium leading-6 text-white">
                            I have a reasonable knowledge of investments, having previously purchased investment / pension products.
                        </label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.level_of_knowledge"
                               :checked="stepForm.level_of_knowledge === 4"
                               type="radio" id="strong-knowledge" :value="4"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="strong-knowledge" class="ml-2 block text-sm font-medium leading-6 text-white">
                            I have a strong knowledge of investments, having previously purchased a range of investment / pension products.
                        </label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.level_of_knowledge">{{ stepForm.errors.level_of_knowledge }}</p>
                </div>
                <!-- Q3 - Investment type only -->
                <div v-if="stepForm.type === 0" class="sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Are you aware of market fluctuations and how these might generate growth / income within your investments but also the fact that you may lose all or part of an investment made?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.aware_of_market_fluctuations"
                               :checked="stepForm.aware_of_market_fluctuations === true"
                               type="radio" id="true" :value="true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()" v-model="stepForm.aware_of_market_fluctuations"
                               :checked="stepForm.aware_of_market_fluctuations === false"
                               type="radio" id="false" :value="false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.aware_of_market_fluctuations">{{ stepForm.errors.aware_of_market_fluctuations }}</p>
                </div>
                <!-- Q3 - Pension type only -->
                <div v-if="stepForm.type === 1" class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Investment funds are subject to market fluctuations, which might result in growth in the value of your investments, or could result in you losing all or part of that fund.
                        <br/><br/>
                        Which option best describes how prepared you are to accept this natural market behaviour in the hope of your investment fund growing?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.aware_of_market_fluctuations"
                               :checked="stepForm.aware_of_market_fluctuations === 1"
                               type="radio" id="1" :value="1"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="1" class="ml-2 block text-sm font-medium leading-6 text-white">I am very comfortable with market fluctuations in the value of my investment fund.</label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.aware_of_market_fluctuations"
                               :checked="stepForm.aware_of_market_fluctuations === 2"
                               type="radio" id="2" :value="2"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="2" class="ml-2 block text-sm font-medium leading-6 text-white">I am comfortable with market fluctuations in the value of my investment fund.</label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.aware_of_market_fluctuations"
                               :checked="stepForm.aware_of_market_fluctuations === 3"
                               type="radio" id="3" :value="3"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="3" class="ml-2 block text-sm font-medium leading-6 text-white">I am uncomfortable with market fluctuations in the value of my investment fund.</label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.aware_of_market_fluctuations"
                               :checked="stepForm.aware_of_market_fluctuations === 4"
                               type="radio" id="4" :value="4"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="4" class="ml-2 block text-sm font-medium leading-6 text-white">I do not want any market fluctuations in the value of my investment fund.</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.aware_of_market_fluctuations">{{ stepForm.errors.aware_of_market_fluctuations }}</p>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <label class="ml-2 block text-sm font-medium leading-6 text-white">Please only complete this section if you have previously purchased an investment or pension product</label>
                    </div>
                </div>
                <!-- Q4 -->
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        If the products purchased fluctuated in value, have you been comfortable with this?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.comfort_of_fluctuations"
                               :checked="stepForm.comfort_of_fluctuations === true"
                               type="radio" id="true" :value="true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()" v-model="stepForm.comfort_of_fluctuations"
                               :checked="stepForm.comfort_of_fluctuations === false"
                               type="radio" id="false" :value="false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.comfort_of_fluctuations">{{ stepForm.errors.comfort_of_fluctuations }}</p>
                </div>
                <!-- Q5 -->
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Have you taken an active interest in following investment markets and reviewing your financial plans?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.active_interest"
                               :checked="stepForm.active_interest === true"
                               type="radio" id="true" :value="true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()" v-model="stepForm.active_interest"
                               :checked="stepForm.active_interest === false"
                               type="radio" id="false" :value="false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.active_interest">{{ stepForm.errors.active_interest }}</p>
                </div>
                <!-- Q6 -->
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Have you an experience of a discretionary managed investment services?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.discretionary_experience"
                               :checked="stepForm.discretionary_experience === true"
                               type="radio" id="true" :value="true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()" v-model="stepForm.discretionary_experience"
                               :checked="stepForm.discretionary_experience === false"
                               type="radio" id="false" :value="false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.discretionary_experience">{{ stepForm.errors.discretionary_experience }}</p>
                </div>
                <!-- Q7 - Pension type only -->
                <div v-if="stepForm.type === 1" class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Have you experience of an ‘execution only’ service? {{stepForm.execution_only_experience}}
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.execution_only_experience"
                               :checked="stepForm.execution_only_experience === true"
                               type="radio" id="true" :value="true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()" v-model="stepForm.execution_only_experience"
                               :checked="stepForm.execution_only_experience === false"
                               type="radio" id="false" :value="false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.execution_only_experience">{{ stepForm.errors.execution_only_experience }}</p>
                </div>
                <!-- Q7 - Investment type -->
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Have you ever taken investment advice?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.ever_taken_invest_advice"
                               :checked="stepForm.ever_taken_invest_advice === true"
                               type="radio" id="true" :value="true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()" v-model="stepForm.ever_taken_invest_advice"
                               :checked="stepForm.ever_taken_invest_advice === false"
                               type="radio" id="false" :value="false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.ever_taken_invest_advice">{{ stepForm.errors.ever_taken_invest_advice }}</p>
                </div>
                <!-- Q8 - Investment type -->
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Have you experience of buying / selling:
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <ExperienceRatingTable
                            :stepFormData="stepForm.experience_buying_cash"
                            :enumItem="formData.enums.risk_assessment_cash"
                            @set-rating="setRating">
                            Cash (Inc ISA)
                        </ExperienceRatingTable>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.experience_buying_cash">{{ stepForm.errors.experience_buying_cash }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <ExperienceRatingTable
                            :stepFormData="stepForm.experience_buying_bonds"
                            :enumItem="formData.enums.risk_assessment_bonds"
                            @set-rating="setRating">
                            Bonds (Inc ISA)
                        </ExperienceRatingTable>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.experience_buying_bonds">{{ stepForm.errors.experience_buying_bonds }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <ExperienceRatingTable
                            :stepFormData="stepForm.experience_buying_equities"
                            :enumItem="formData.enums.risk_assessment_equities"
                            @set-rating="setRating">
                            Equities (Inc ISA)
                        </ExperienceRatingTable>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.experience_buying_equities">{{ stepForm.errors.experience_buying_equities }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <ExperienceRatingTable
                            :stepFormData="stepForm.experience_buying_insurance"
                            :enumItem="formData.enums.risk_assessment_insurance"
                            @set-rating="setRating">
                            Insurance Products
                        </ExperienceRatingTable>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.experience_buying_insurance">{{ stepForm.errors.experience_buying_insurance }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">If you have knowledge / experience of any of the items listed above, please explain how often you have opened/traded/used such facilities and investments and comment on the size of your dealings: </label>
                    <div class="mt-2">
                        <textarea @change="autosaveT(stepForm, props.formData.submit_url)" v-model="stepForm.experience_details" rows="3" name="experience_details" id="experience_details"
                                  class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>

                <div v-if="stepForm.type === 1" class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Retirement Options (if applicable)
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead>
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold sm:pl-3"></th>
                                <th scope="col" class="px-3 py-3.5 text-left font-semibold">1</th>
                                <th scope="col" class="px-3 py-3.5 text-left font-semibold">2</th>
                                <th scope="col" class="px-3 py-3.5 text-left font-semibold">3</th>
                                <th scope="col" class="px-3 py-3.5 text-left font-semibold">4</th>
                                <th scope="col" class="px-3 py-3.5 text-left font-semibold">5</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="even:bg-aaron-950">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-3 w-1/2">Annuities</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_annuities"
                                           :checked="stepForm.experience_of_annuities === 1"
                                           type="radio" id="one" :value="1"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_annuities"
                                           :checked="stepForm.experience_of_annuities === 2"
                                           type="radio" id="two" :value="2"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_annuities"
                                           :checked="stepForm.experience_of_annuities === 3"
                                           type="radio" id="five" :value="3"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_annuities"
                                           :checked="stepForm.experience_of_annuities === 4"
                                           type="radio" id="five" :value="4"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_annuities"
                                           :checked="stepForm.experience_of_annuities === 5"
                                           type="radio" id="six" :value="5"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                            </tr>
                            <tr class="even:bg-aaron-950">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-3 w-1/2">Income Drawdown</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_income_drawdown"
                                           :checked="stepForm.experience_of_income_drawdown === 1"
                                           type="radio" id="one" :value="1"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_income_drawdown"
                                           :checked="stepForm.experience_of_income_drawdown === 2"
                                           type="radio" id="two" :value="2"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_income_drawdown"
                                           :checked="stepForm.experience_of_income_drawdown === 3"
                                           type="radio" id="five" :value="3"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_income_drawdown"
                                           :checked="stepForm.experience_of_income_drawdown === 4"
                                           type="radio" id="five" :value="4"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_income_drawdown"
                                           :checked="stepForm.experience_of_income_drawdown === 5"
                                           type="radio" id="six" :value="5"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                            </tr>
                            <tr class="even:bg-aaron-950">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-3 w-1/2">Phased Retirement</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_phased_retirement"
                                           :checked="stepForm.experience_of_phased_retirement === 1"
                                           type="radio" id="one" :value="1"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_phased_retirement"
                                           :checked="stepForm.experience_of_phased_retirement === 2"
                                           type="radio" id="two" :value="2"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_phased_retirement"
                                           :checked="stepForm.experience_of_phased_retirement === 3"
                                           type="radio" id="five" :value="3"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_phased_retirement"
                                           :checked="stepForm.experience_of_phased_retirement === 4"
                                           type="radio" id="five" :value="4"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <input @change="autosaveLocally()" v-model="stepForm.experience_of_phased_retirement"
                                           :checked="stepForm.experience_of_phased_retirement === 5"
                                           type="radio" id="six" :value="5"
                                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.experience_of_annuities">{{ stepForm.errors.experience_of_annuities }}</p>
                </div>
                <div v-if="stepForm.type === 1" class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Have you spoken to PensionWise?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.spoken_to_pensionwise"
                               :checked="stepForm.spoken_to_pensionwise === true"
                               type="radio" id="true" :value="true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()" v-model="stepForm.spoken_to_pensionwise"
                               :checked="stepForm.spoken_to_pensionwise === false"
                               type="radio" id="false" :value="false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.spoken_to_pensionwise">{{ stepForm.errors.spoken_to_pensionwise }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2 flex justify-center">
                    <button type="button" @click="submitAssessment()"
                        :disabled="isSubmitDisabled()"
                        class="inline-flex items-center gap-x-1.5 rounded-md bg-sage px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#00b49d] disabled:bg-slate-300 disabled:text-slate-700 disabled:cursor-not-allowed">
                        Submit Assessment
                    </button>
                </div>
                <div class="sm:col-span-6 sm:mt-0 flex justify-center gap-x-2 text-red-500"
                     v-if="isSubmitDisabled()">
                    <InformationCircleIcon class="w-5 h-5"></InformationCircleIcon>
                    <span class="text-xs">Please fill out the all necessary fields before assessment submission</span>
                </div>
            </div>
        </div>
    </dynamic-form-wrapper>
</template>
