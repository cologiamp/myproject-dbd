<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import 'vue-select/dist/vue-select.css';
import DraggableTable from "@/Components/ObjectivesTable.vue";
import {PlusCircleIcon} from '@heroicons/vue/24/solid';
import {changeToCurrency} from "@/currency.js";

import {onBeforeMount, ref, watch} from "vue";
import {usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";

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
                recom_objective_types: []
            },
            model: {
                objectives: []
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

    forceRerender();
}

const draggableKey = ref(0);
const forceRerender = () => {
    draggableKey.value += 1;
};

const topics_enum = ref([]);
const objectives_enum = ref([]);
const what_for_enum = ref([]);
const is_edit = ref(false);
const addObjective = ref(false);

function setTopicEnums(topicId) {
    objectives_enum.value = props.formData.enums.topics[topicId]['objectives']
    what_for_enum.value = props.formData.enums.topics[topicId]['what_for']
}

async function getObjectiveToEdit(objectiveId) {
    is_edit.value = true;
    addObjective.value = false;

    axios.get('/api/strategy-objectives/'+ objectiveId).then(function (response){
        stepForm.id = response.data.id
        stepForm.client_id = response.data.client_id
        stepForm.strategy_report_recommendation_id = response.data.strategy_report_recommendation_id
        stepForm.objective_type = response.data.is_primary
        stepForm.type = response.data.type
        setTopicEnums(stepForm.type)
        stepForm.objective = parseInt(response.data.objective)
        stepForm.objective_custom = response.data.objective_custom
        stepForm.what_for = parseInt(response.data.what_for)
        stepForm.what_for_custom = response.data.what_for_custom
    }).catch(function (e){
        console.log(e)
    });

    return stepForm;
}

onBeforeMount(() => {
    topics_enum.value = []

    Object.entries(props.formData.enums.topics).forEach(topic => {
        const [key, value] = topic;
            topics_enum.value.push(value.name);
    });

    addObjective.value = false;
    is_edit.value = false;
})

function addObj() {
    addObjective.value = true
    resetData();
}

function resetData() {
    delete stepForm.id
    delete stepForm.client_id
    delete stepForm.strategy_report_recommendation_id
    delete stepForm.objective_type
    delete stepForm.type
    delete stepForm.objective
    delete stepForm.objective_custom
    delete stepForm.what_for
    delete stepForm.what_for_custom
}

function removeObjective(id) {
    let objIndex = null;

    Object.entries(stepForm.objectives).forEach(objective => {
        const [key, value] = objective;

        if (value.id === id) {
            objIndex = key
        }
    });

    if(stepForm.objectives[objIndex].id != null) {
        axios.delete('/api/strategy-objectives/'+ stepForm.objectives[objIndex].id).then(function (response){
            console.log(response.data)
            stepForm.objectives.splice(objIndex, 1)
            autosaveLocally()
        }).catch(function (e){
            console.log(e)
        });
    }
}

function reOrderObjectives(objectives) {
    resetData();
    stepForm.objectives = objectives
    autosaveLocally()
}

function cancel() {
    is_edit.value = false;
    addObjective.value = false;
    resetData();
}

function save() {
    if (stepForm.objective_custom && typeof stepForm.objective_custom !== 'string') {
        stepForm.objective_custom = stepForm.objective_custom.toString()
    }
    if (stepForm.what_for_custom && typeof stepForm.what_for_custom !== 'string') {
        stepForm.what_for_custom = stepForm.what_for_custom.toString()
    }

    autosaveLocally();
    is_edit.value = false;
    addObjective.value = false;
    resetData();
}

function formatAmount(e, typeFieldName) {
    if(typeFieldName === 'objective_custom') {
        stepForm.objective_custom = '';
        stepForm.objective_custom = changeToCurrency(e.target.value);
    }
}

</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div v-if="addObjective === false && is_edit === false" class="grid grid-cols-3 gap-2">
                <div class="mt-4 pt-8 w-full flex items-center justify-end col-start-3">
                    <button type="button" @click="addObj"
                            class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <PlusCircleIcon class="w-6 h-6" />Add Objective
                    </button>
                </div>
            </div>
            <div v-if="addObjective === true || is_edit === true" class="grid grid-cols-3 gap-2">
                <div class="mt-4 w-full">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="objective_type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Objective Type </label>
                        <select id="objective_type" name="objective_type" v-model="stepForm.objective_type" required
                            class="block rounded-md w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="type" :value="null">-</option>
                            <option :id="1" :value="1">Primary Objective</option>
                            <option :id="0" :value="0">Secondary Objective</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.objective_type">{{ stepForm.errors.objective_type }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-8 w-full flex items-center justify-end col-start-3 gap-x-4 md:pr-2">
                    <button type="button" @click="save()"
                        class="inline-flex items-center gap-x-1.5 rounded-md bg-green-600 px-8 py-2 text-base font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Save
                    </button>
                    <button type="button" @click="cancel()"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-red-700 px-8 py-2 text-base font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Cancel
                    </button>
                </div>
            </div>
            <div v-if="addObjective === true || is_edit === true" class="grid grid-cols-3 gap-2">
                <div class="w-full">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Topic </label>
                        <select @change="setTopicEnums(stepForm.type)" v-model="stepForm.type" id="type" name="type"  class="block rounded-md w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="type" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(topic, id) in topics_enum">{{ topic }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.type">{{ stepForm.errors.type }}</p>
                    </div>
                </div>
                <div class="w-full">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="objective" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Objective </label>
                        <select id="objective" name="objective" v-model="stepForm.objective"
                                class="block rounded-md w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="objective" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(objective, id) in objectives_enum">{{ objective }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.objective">{{ stepForm.errors.objective }}</p>
                    </div>
                </div>
                <div class="w-full">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="what_for" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> What For? </label>
                        <select id="what_for" name="what_for"  v-model="stepForm.what_for" class="block rounded-md w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="what_for" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(what_for, id) in what_for_enum">{{ what_for }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.what_for">{{ stepForm.errors.what_for }}</p>
                    </div>
                </div>

                <div class="w-full col-start-2">
                    <!-- show if objective_type - Other -->
                    <div v-if="parseInt(stepForm.objective) === 99" class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="objective_custom" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Custom Topic </label>
                        <textarea rows="3" name="objective_custom" id="objective_custom" v-model="stepForm.objective_custom"
                        class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.objective_custom">{{ stepForm.errors.objective_custom }}</p>
                    </div>
                    <!-- show to input objective amount -->
                    <div v-if="parseInt(stepForm.type) === 2 && parseInt(stepForm.objective) === 1" class="mt-0 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="objective_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Amount </label>
                        <input type="currency" name="objective_amount" id="objective_amount"
                               :value="stepForm.objective_custom"
                               @change="formatAmount($event, 'objective_custom')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />

                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.objective_custom">{{ stepForm.errors.objective_custom }}</p>
                    </div>
                    <div v-if="parseInt(stepForm.type) === 3 && [0,1].includes(parseInt(stepForm.objective))" class="mt-0 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="objective_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Amount </label>
                        <input type="currency" name="objective_amount" id="objective_amount"
                               :value="stepForm.objective_custom"
                               @change="formatAmount($event, 'objective_custom')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.objective_custom">{{ stepForm.errors.objective_custom }}</p>
                    </div>
                    <div v-if="parseInt(stepForm.type) === 8 && [1,2].includes(parseInt(stepForm.objective))" class="mt-0 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="objective_years_age" v-if="parseInt(stepForm.objective) === 1"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Years </label>
                        <label for="objective_years_age" v-if="parseInt(stepForm.objective) === 2"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Age </label>
                        <input type="number" min="0" max="100" name="objective_years_age" id="objective_years_age"
                           v-model="stepForm.objective_custom"
                           class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="" />
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.objective_custom">{{ stepForm.errors.objective_custom }}</p>
                    </div>
                </div>
                <div class="w-full">
                    <!-- show if what_for_type - Other -->
                    <div v-if="parseInt(stepForm.what_for) === 99" class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="what_for_custom" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Custom Topic </label>
                        <textarea rows="3" name="what_for_custom" id="what_for_custom"
                          v-model="stepForm.what_for_custom"
                          class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.what_for_custom">{{ stepForm.errors.what_for_custom }}</p>
                    </div>
                    <!-- show to input what_for amount / years -->
                    <div v-if="parseInt(stepForm.type) === 2 && [0,1].includes(parseInt(stepForm.what_for))" class="mt-0 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="what_for_custom" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Years </label>
                        <input type="number" min="0" max="100" name="what_for_custom" id="what_for_custom"
                           v-model="stepForm.what_for_custom"
                           class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="" />
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.what_for_custom">{{ stepForm.errors.what_for_custom }}</p>
                    </div>
                </div>
            </div>
            <DraggableTable
                :key="draggableKey"
                :topics="props.formData.enums.topics"
                :primaryTypes="props.formData.enums.recom_objective_types"
                :objectives="stepForm.objectives"
                @get-objective-to-edit="getObjectiveToEdit"
                @remove-objective="removeObjective"
                @re-order-objectives="reOrderObjectives"
            ></DraggableTable>
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
