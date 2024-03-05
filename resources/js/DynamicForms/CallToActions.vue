<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import 'vue-select/dist/vue-select.css';
import DraggableTable from "@/Components/CallToActionTable.vue";
import {PlusCircleIcon} from '@heroicons/vue/24/solid';

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
                call_to_action_types: []
            },
            model: {
                actions: []
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    actions: props.formData.model.actions,
});

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.actions = props.formData.model.actions;

    forceRerender();
}

const draggableKey = ref(0);
const forceRerender = () => {
    draggableKey.value += 1;
};

const is_edit = ref(false);
const addAction = ref(false);

async function getActionToEdit(actionId) {
    is_edit.value = true;
    addAction.value = false;

    axios.get('/api/strategy-actions/'+ actionId).then(function (response){
        stepForm.id = response.data.id
        stepForm.strategy_report_recommendation_id = response.data.strategy_report_recommendation_id
        stepForm.call_to_action = parseInt(response.data.call_to_action)
        stepForm.call_to_action_custom = response.data.call_to_action_custom
    }).catch(function (e){
        console.log(e)
    });

    return stepForm;
}

onBeforeMount(() => {
    addAction.value = false;
    is_edit.value = false;
})

function addActionData() {
    addAction.value = true
    resetData();
}

function resetData() {
    delete stepForm.id
    delete stepForm.strategy_report_recommendation_id
    delete stepForm.call_to_action
    delete stepForm.call_to_action_custom
}

function removeAction(id) {
    let actionIndex = null;

    Object.entries(stepForm.actions).forEach(action => {
        const [key, value] = action;

        if (value.id === id) {
            actionIndex = key
        }
    });

    if(stepForm.actions[actionIndex].id != null) {
        axios.delete('/api/strategy-actions/'+ stepForm.actions[actionIndex].id).then(function (response){
            console.log(response.data)
            stepForm.actions.splice(actionIndex, 1)
            autosaveLocally()
        }).catch(function (e){
            console.log(e)
        });
    }
}

function reOrderActions(actions) {
    resetData();
    stepForm.actions = actions
    autosaveLocally()
}

function cancel() {
    is_edit.value = false;
    addAction.value = false;
    resetData();
}

function save() {
    autosaveLocally();

    is_edit.value = false;
    addAction.value = false;
    resetData();
}

</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div v-if="addAction === false && is_edit === false">
                <div class="w-full flex items-center justify-end">
                    <button type="button" @click="addActionData"
                            class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <PlusCircleIcon class="w-6 h-6" />Add Action
                    </button>
                </div>
            </div>
            <div v-if="addAction === true || is_edit === true">
                <div class="mt-4 w-full">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="call_to_action" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> In the next report we will show you how </label>
                        <select id="call_to_action" name="call_to_action" v-model="stepForm.call_to_action"
                            class="block rounded-md min-w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="type" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(type, id) in formData.enums.call_to_action_types">{{ type }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.call_to_action">{{ stepForm.errors.call_to_action }}</p>
                    </div>
                </div>
                <div class="mb-4 w-full col-start-2">
                    <div v-if="parseInt(stepForm.call_to_action) === 99" class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                        <label for="call_to_action_custom" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Custom action </label>
                        <textarea rows="3" name="call_to_action_custom" id="call_to_action_custom"
                                  v-model="stepForm.call_to_action_custom"
                                  class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.call_to_action_custom">{{ stepForm.errors.call_to_action_custom }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 w-full flex items-center justify-end col-start-3 gap-x-4 md:pr-2">
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
            <DraggableTable
                :key="draggableKey"
                :types="props.formData.enums.call_to_action_types"
                :actions="stepForm.actions"
                @get-action-to-edit="getActionToEdit"
                @remove-action="removeAction"
                @re-order-actions="reOrderActions"
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
