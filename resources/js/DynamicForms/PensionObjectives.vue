<script setup>
//FACTFIND:// you need to make one of these for every step
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

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
                titles: []
            },
            model: {
                intended_retirement: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});




const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    intended_retirement: props.formData.model.intended_retirement,
    intended_benefits_drawn: props.formData.model.intended_benefits_drawn,
    income_option: props.formData.model.income_option,
    notes: props.formData.model.notes,
    lifetime_allowance_protection: props.formData.model.lifetime_allowance_protection,
});

function changeCheck() {

    //alert(stepForm.lifetime_allowance_protection);

    //set all is_primary to false/uncheck
    /*
    Object.entries(stepForm.lifetime_allowance_protection).forEach(owner => {
        const [key, value] = owner;
        //alert([key, value]);
        //value['lifetime_allowance_protection'] = false;
    });
    */
    autosaveT(stepForm,props.formData.submit_url)
}
</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="mb-12">
                <h1>General</h1>
                <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="intended_retirement" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> How long until you intend to retire? (years)</label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.intended_retirement" type="number" name="intended_retirement" id="intended_retirement" min="0" max="100" step="1" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="1" />
                        </div>
                    </div>
                </div>

                <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="intended_benefits_drawn" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> How many years until you expect to draw benefits?</label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.intended_benefits_drawn" type="number" name="intended_benefits_drawn" id="intended_benefits_drawn" min="0" max="100" step="1" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="1" />
                        </div>
                    </div>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="income_option" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Which retirement income option do your favour? </label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.income_option" id="unit" name="income_option"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="income_option" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(income_option, id) in formData.enums.income_options">{{ income_option }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.income_option">{{ stepForm.errors.income_option }}</p>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Notes</label>
                        <div class="mt-2">
                            <textarea @change="autosaveT(stepForm, props.formData.submit_url)" v-model="stepForm.notes" rows="3" name="notes" id="notes" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h1>HMRC Lifetime Allowance protection</h1>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="first_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Which HMRC Lifetime Allowance protection(s) do you hold?</label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <v-select
                            class="block rounded-md w-full border-0 bg-aaron-950 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"
                            multiple v-model="stepForm.lifetime_allowance_protection"
                            :options="formData.enums.lifetime_allowance_protection"
                            @option:selected="changeCheck()"
                            @option:deselected="changeCheck()"
                        />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.lifetime_allowance_protection">{{ stepForm.errors.lifetime_allowance_protection }}</p>
                </div>


            </div>


        </div>
    </dynamic-form-wrapper>
</template>

<style scoped>
>>> {
    --vs-controls-color: red;

    --vs-dropdown-bg: #313fa7;
    --vs-dropdown-color: black;
    --vs-dropdown-option-color: $bg-aaron-700;

    --vs-selected-bg: #313fa7;
    --vs-selected-color: #eeeeee;

    --vs-search-input-color: #eeeeee;

    --vs-dropdown-option--active-bg: #0b0f28;
    --vs-dropdown-option--active-color: #eeeeee;
}
</style>
