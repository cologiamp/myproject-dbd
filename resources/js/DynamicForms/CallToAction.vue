<script setup>
//FACTFIND:// you need to make one of these for every step
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import VueDatePicker from "@vuepic/vue-datepicker";

import { PlusCircleIcon } from '@heroicons/vue/24/solid';

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
                // id: null,
                objective: null,
                what_for: null,
                // objective_type: null,
                // next_meeting_date: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    topicId: props.formData.model.topicId,
    objective: props.formData.model.objective,
    what_for: props.formData.model.what_for,
//     objective_type: props.formData.model.objective_type,
//     next_meeting_date: props.formData.model.next_meeting_date,
});
//
// async function autosaveLocally(){
//     props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
//     stepForm.id = props.formData.model.id;
//     stepForm.report_version = props.formData.model.report_version;
//     stepForm.retirement_status = props.formData.model.retirement_status;
//     stepForm.objective_type = props.formData.model.objective_type;
//     stepForm.next_meeting_date = props.formData.model.next_meeting_date;
// }

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
            <h1 class="text-2xl pb-4"></h1>
            <div class="grid grid-cols-3 gap-2">
                <div class="mb-4 mt-4 w-full">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="topic" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Drag and Drop  Table </label>
<!--                        <select @change="setTopicEnums(stepForm.topicId)" v-model="stepForm.topicId" id="objectives" name="objectives"  class="block rounded-md w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">-->
<!--                            <option id="report_version" :value="null">-</option>-->
<!--                            <option :id="id" :value="id" v-for="(topic, id) in formData.enums.topics">{{ topic }}</option>-->
<!--                        </select>-->
<!--                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.topic">{{ stepForm.errors.topic }}</p>-->
                    </div>
                </div>
                <div class="mb-4 w-full col-span-3">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <button type="button"
                                class="float-left inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Save
                        </button>
                    </div>
                </div>
            </div>
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
