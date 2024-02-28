<script setup>
//FACTFIND:// you need to make one of these for every step
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import VueDatePicker from "@vuepic/vue-datepicker";

import '@vuepic/vue-datepicker/dist/main.css'
import {watch} from "vue";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                report_versions: [],
                retirement_statuses: [],
                objective_types: []
            },
            model: {
                id: null,
                report_version: null,
                retirement_status: null,
                objective_type: null,
                next_meeting_date: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    id: props.formData.model.id,
    report_version: props.formData.model.report_version,
    retirement_status: props.formData.model.retirement_status,
    objective_type: props.formData.model.objective_type,
    next_meeting_date: props.formData.model.next_meeting_date,
});

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.id = props.formData.model.id;
    stepForm.report_version = props.formData.model.report_version;
    stepForm.retirement_status = props.formData.model.retirement_status;
    stepForm.objective_type = props.formData.model.objective_type;
    stepForm.next_meeting_date = props.formData.model.next_meeting_date;
}

</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="flex flex-col justify-center w-full md:w-6/12 mx-auto">
            <div class="mb-4 mt-4 w-full">
                <h1 class="text-2xl pb-4">General</h1>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label for="report_version" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Holistic Report Version </label>
                    <select @change="autosaveLocally()" v-model="stepForm.report_version" id="report_version" name="report_version"  class="block rounded-md w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="report_version" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(report_version, id) in formData.enums.report_versions">{{ report_version }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.report_version">{{ stepForm.errors.report_version }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label for="retirement_status" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Retirement Status </label>
                    <select @change="autosaveLocally()" v-model="stepForm.retirement_status" id="retirement_status" name="report_version"  class="block rounded-md w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="retirement_status" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(retirement_status, id) in formData.enums.retirement_statuses">{{ retirement_status }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.retirement_status">{{ stepForm.errors.retirement_status }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label for="objective_type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Retirement Status </label>
                    <select @change="autosaveLocally()" v-model="stepForm.objective_type" id="unit" name="objective_type"  class="block rounded-md w-full border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="objective_type" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(retirement_status, id) in formData.enums.objective_types">{{ retirement_status }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.objective_type">{{ stepForm.errors.objective_type }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label for="next_meeting_date" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Date of Next Meeting </label>
                    <VueDatePicker text-input class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy"
                       v-model="stepForm.next_meeting_date" @update:model-value="autosaveLocally()"
                       name="next_meeting_date" id="next_meeting_date"
                       placeholder="dd/mm/yyyy"/>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.next_meeting_date">{{ stepForm.errors.next_meeting_date }}</p>
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
