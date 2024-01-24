<script setup>
//FACTFIND:// you need to make one of these for every step
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";

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
                is_in_good_health: null,
                health_details: null,
                has_life_expectancy_concerns: null,
                life_expectancy_details: null,
                medical_conditions: null,
                smoker: null,
                smoked_in_last_12_months: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    is_in_good_health: props.formData.model.is_in_good_health,
    health_details: props.formData.model.health_details,
    has_life_expectancy_concerns: props.formData.model.has_life_expectancy_concerns,
    life_expectancy_details: props.formData.model.life_expectancy_details,
    medical_conditions: props.formData.model.medical_conditions,
    smoker: props.formData.model.smoker,
    smoked_in_last_12_months: props.formData.model.smoked_in_last_12_months
})

</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 h-36">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Do you consider yourself to be in good health?</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.is_in_good_health" type="radio" id="true" :value="true" :checked="stepForm.is_in_good_health == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.is_in_good_health" type="radio" id="false" :value="false" :checked="stepForm.is_in_good_health == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.is_in_good_health">{{ stepForm.errors.is_in_good_health }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div v-if="stepForm.is_in_good_health == false">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">If no, provide details</label>
                        <div class="mt-2">
                            <textarea @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.health_details" rows="3" name="health_details" id="health_details" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 h-36">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Do you have any concerns regarding your life expectancy?</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.has_life_expectancy_concerns" type="radio" id="true" :value="true" :checked="stepForm.has_life_expectancy_concerns == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.has_life_expectancy_concerns" type="radio" id="false" :value="false" :checked="stepForm.has_life_expectancy_concerns == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.has_life_expectancy_concerns">{{ stepForm.errors.has_life_expectancy_concerns }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div v-if="stepForm.has_life_expectancy_concerns == true">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">If yes, please explain why</label>
                        <div class="mt-2">
                            <textarea @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.life_expectancy_details" rows="3" name="life_expectancy_details" id="life_expectancy_details" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Any medical conditions</label>
                        <div class="mt-2">
                            <textarea @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.medical_conditions" rows="3" name="medical_conditions" id="medical_conditions" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="smoker" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Are you a smoker?</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.smoker" id="unit" name="smoker" class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="smoker" :value="null">-</option>
                        <option id="smoker" :value="1">Yes</option>
                        <option id="smoker" :value="0">No</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.smoker">{{ stepForm.errors.smoker }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Have you smoked in the last 12 months?</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.smoked_in_last_12_months" type="radio" id="true" :value="true" :checked="stepForm.smoked_in_last_12_months == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.smoked_in_last_12_months" type="radio" id="false" :value="false" :checked="stepForm.smoked_in_last_12_months == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.smoked_in_last_12_months">{{ stepForm.errors.smoked_in_last_12_months }}</p>
                </div>
            </div>
        </div>
    </dynamic-form-wrapper>
</template>

<style scoped>
    .aaron-datepicker{
        --dp-background-color: rgb(49 63 167 / var(--tw-bg-opacity));
        --dp-text-color: rgb(236 245 255 / var(--tw-text-opacity));
        --dp-border-radius: 6px;
        --dp-border-color: rgb(70 84 190 / var(--tw-ring-opacity));
    }

</style>
