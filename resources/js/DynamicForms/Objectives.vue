<script setup>
//FACTFIND:// you need to make one of these for every step
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import 'vue-select/dist/vue-select.css';
import DraggableTable from "@/Components/DraggableTableRows.vue";

import '@vuepic/vue-datepicker/dist/main.css'
import {watch, ref} from "vue";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                topics: [],
                recom_objective_types: [],
                legacy_planning_topic: [],
                tax_efficiency_topic: [],
                short_term_cash_needs_topic: [],
                income_needs_topic: [],
                capital_growth_topic: [],
                simplify_approach_topic: [],
                flexibility_topic: [],
                advice_service_topic: [],
                repayment_liabilities_topic: [],
                pension_planning_topic: []
            },
            model: {
                objectives: [],
                // objectives: [{
                    id: null,
                    client_id: null,
                    strategy_report_recommendation_id: null,
                    is_primary: null,
                    type: null, //topic
                    objective: null,
                    objective_custom: null,
                    what_for: null,
                    what_for_custom: null
                // }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    objectives: props.formData.model.objectives,
});

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.objectives = props.formData.model.objectives;
}

const objectives = ref([]);
const what_for = ref([]);

function setTopicEnums(topicId) {
    switch (parseInt(topicId)) {
        case 0:
            objectives.value = props.formData.enums.legacy_planning_topic['objectives']
            what_for.value = props.formData.enums.legacy_planning_topic['what_for']
            break;
        case 1:
            objectives.value = props.formData.enums.tax_efficiency_topic['objectives']
            what_for.value = props.formData.enums.tax_efficiency_topic['what_for']
            break;
        case 2:
            objectives.value = props.formData.enums.short_term_cash_needs_topic['objectives']
            what_for.value = props.formData.enums.short_term_cash_needs_topic['what_for']
            break;
        case 3:
            objectives.value = props.formData.enums.income_needs_topic['objectives']
            what_for.value = props.formData.enums.income_needs_topic['what_for']
            break;
        case 4:
            objectives.value = props.formData.enums.capital_growth_topic['objectives']
            what_for.value = props.formData.enums.capital_growth_topic['what_for']
            break;
        case 5:
            objectives.value = props.formData.enums.simplify_approach_topic['objectives']
            what_for.value = props.formData.enums.simplify_approach_topic['what_for']
            break;
        case 6:
            objectives.value = props.formData.enums.flexibility_topic['objectives']
            what_for.value = props.formData.enums.flexibility_topic['what_for']
            break;
        case 7:
            objectives.value = props.formData.enums.advice_service_topic['objectives']
            what_for.value = props.formData.enums.advice_service_topic['what_for']
            break;
        case 8:
            objectives.value = props.formData.enums.repayment_liabilities_topic['objectives']
            what_for.value = props.formData.enums.repayment_liabilities_topic['what_for']
            break;
        case 9:
            objectives.value = props.formData.enums.pension_planning_topic['objectives']
            what_for.value = props.formData.enums.pension_planning_topic['what_for']
            break;
        default:
            break;
    }
}
</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="grid grid-cols-3 gap-2">
                <div class="mt-4 w-full">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="is_primary" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Objective Type </label>
                        <select id="is_primary" name="is_primary" v-model="stepForm.is_primary"
                            class="block rounded-md w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="type" :value="null">-</option>
                            <option :id="1" :value="1">Primary Objective</option>
                            <option :id="0" :value="0">Secondary Objective</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.is_primary">{{ stepForm.errors.is_primary }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-8 w-full flex items-center justify-center col-start-3">
                    <button type="button" @click="autosaveLocally()"
                        class="inline-flex items-center gap-x-1.5 rounded-md bg-green-600 px-8 py-2 text-base font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Save
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div class="mb-4 mt-4 w-full">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Topic </label>
                        <select @change="setTopicEnums(stepForm.type)" v-model="stepForm.type" id="type" name="type"  class="block rounded-md w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="type" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(topic, id) in formData.enums.topics">{{ topic }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.type">{{ stepForm.errors.type }}</p>
                    </div>
                </div>
                <div class="mb-4 mt-4 w-full">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="objective" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Objective </label>
                        <select id="objective" name="objective" v-model="stepForm.objective" class="block rounded-md w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="objective" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(objective, id) in objectives">{{ objective }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.objective">{{ stepForm.errors.objective }}</p>
                    </div>
                </div>
                <div class="mb-4 mt-4 w-full">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="what_for" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> What For? </label>
                        <select id="what_for" name="what_for"  v-model="stepForm.what_for" class="block rounded-md w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="what_for" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(what_for, id) in what_for">{{ what_for }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.what_for">{{ stepForm.errors.what_for }}</p>
                    </div>
                </div>
                <div class="mb-4 w-full col-start-2">
                    <div v-if="stepForm.objective == 99" class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="objective_custom" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Custom Topic </label>
                        <textarea rows="3" name="objective_custom" id="objective_custom"
                            v-model="stepForm.objective_custom"
                            class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.objective_custom">{{ stepForm.errors.objective_custom }}</p>
                    </div>
                </div>
                <div class="mb-4 w-full">
                    <div v-if="stepForm.what_for == 99" class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="what_for_custom" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Custom Topic </label>
                        <textarea rows="3" name="what_for_custom" id="what_for_custom"
                          v-model="stepForm.what_for_custom"
                          class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.what_for_custom">{{ stepForm.errors.what_for_custom }}</p>
                    </div>
                </div>
            </div>
            <DraggableTable :objectives="stepForm.objectives"></DraggableTable>
        </div>
    </dynamic-form-wrapper>
</template>
<style>

</style>
<style scoped>
/* Needed for the ring/border colour when dropdown is active/opened, note: hover wasn't the right behavier and active/enable weren't working. */
.vs--open {
    box-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);
}

>>> {
    --vs-controls-color: white;

    --vs-dropdown-bg: #4654be;
    --vs-dropdown-color: red;
    --vs-dropdown-option-color: white;

    --vs-selected-bg: #6170dd;
    --vs-selected-color: #eeeeee;

    --vs-search-input-color: #eeeeee;

    --vs-dropdown-option--active-bg: #6170dd;
    --vs-dropdown-option--active-color: white;

    --vs-border-radius: 4px;
}
.dropdown-container .vs__dropdown-menu {
    border-radius: 4px !important;
}
</style>
