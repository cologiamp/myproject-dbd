<script setup>
//FACTFIND:// you need to make one of these for every step
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";

import '@vuepic/vue-datepicker/dist/main.css'
import {onBeforeMount, watch} from "vue";

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

})

onBeforeMount(()=>{
    for (const [item, value] of Object.entries(props.formData.model)) {

        stepForm[item] = {};
        stepForm[item]['is_in_good_health'] = value.is_in_good_health;
        stepForm[item]['health_details'] = value.health_details;
        stepForm[item]['has_life_expectancy_concerns'] = value.has_life_expectancy_concerns;
        stepForm[item]['life_expectancy_details'] = value.life_expectancy_details;
        stepForm[item]['medical_conditions'] = value.medical_conditions;
        stepForm[item]['smoker'] = value.smoker;
        stepForm[item]['smoked_in_last_12_months'] = value.smoked_in_last_12_months;
    }


})
async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    for (const [item, value] of Object.entries(props.formData.model)) {

        stepForm[item] = {};
        stepForm[item]['is_in_good_health'] = value.is_in_good_health;
        stepForm[item]['health_details'] = value.health_details;
        stepForm[item]['has_life_expectancy_concerns'] = value.has_life_expectancy_concerns;
        stepForm[item]['life_expectancy_details'] = value.life_expectancy_details;
        stepForm[item]['medical_conditions'] = value.medical_conditions;
        stepForm[item]['smoker'] = value.smoker;
        stepForm[item]['smoked_in_last_12_months'] = value.smoked_in_last_12_months;
    }
}


</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="grid gap-2 md:grid md:items-start md:gap-y-8 md:gap-x-4" :class="'md:grid-cols-'+ (Object.keys(props.formData.model).length)">
                <div v-if="stepForm" :class="Object.keys(props.formData.model).length > 1 ? 'bg-aaron-850' : ''" class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4 rounded-md p-2" v-for="(model,key,index) in props.formData.model" >
                    <h4 v-if="Object.keys(props.formData.model).length > 1" class="col-span-6 text-xl font-bold pt-2"> Client {{index + 1}} </h4>



                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 h-36">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Do you consider yourself to be in good health?</label>
                        <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                            <input @change="autosaveLocally()" v-model="stepForm[key].is_in_good_health" type="radio" id="true" :value="true" :checked="stepForm[key].is_in_good_health == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                            <input @change="autosaveLocally()" v-model="stepForm[key].is_in_good_health" type="radio" id="false" :value="false" :checked="stepForm[key].is_in_good_health == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                        </div>
                    </div>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <div v-if="stepForm[key].is_in_good_health == false">
                            <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">If no, provide details</label>
                            <div class="mt-2">
                                <textarea @change="autosaveLocally()" v-model="stepForm[key].health_details" rows="3" name="health_details" id="health_details" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 h-36">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Do you have any concerns regarding your life expectancy?</label>
                        <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                            <input @change="autosaveLocally()" v-model="stepForm[key].has_life_expectancy_concerns" type="radio" id="true" :value="true" :checked="stepForm[key].has_life_expectancy_concerns == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                            <input @change="autosaveLocally()" v-model="stepForm[key].has_life_expectancy_concerns" type="radio" id="false" :value="false" :checked="stepForm[key].has_life_expectancy_concerns == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                        </div>
                    </div>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <div v-if="stepForm[key].has_life_expectancy_concerns == true">
                            <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">If yes, please explain why</label>
                            <div class="mt-2">
                                <textarea @change="autosaveLocally()" v-model="stepForm[key].life_expectancy_details" rows="3" name="life_expectancy_details" id="life_expectancy_details" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2">
                        <div>
                            <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Any medical conditions</label>
                            <div class="mt-2">
                                <textarea @change="autosaveLocally()" v-model="stepForm[key].medical_conditions" rows="3" name="medical_conditions" id="medical_conditions" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950  shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="smoker" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Are you a smoker?</label>
                        <select @change="autosaveLocally()" v-model="stepForm[key].smoker" id="unit" name="smoker" class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="smoker" :value="null">-</option>
                            <option id="smoker" :value="1">Yes</option>
                            <option id="smoker" :value="0">No</option>
                        </select>
                    </div>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2" v-show="!stepForm[key].smoker">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Have you smoked in the last 12 months?</label>
                        <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                            <input @change="autosaveLocally()" v-model="stepForm[key].smoked_in_last_12_months" type="radio" id="true" :value="true" :checked="stepForm[key].smoked_in_last_12_months == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                            <input @change="autosaveLocally()" v-model="stepForm[key].smoked_in_last_12_months" type="radio" id="false" :value="false" :checked="stepForm[key].smoked_in_last_12_months == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                            <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                        </div>
                    </div>
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
    .bg-aaron-850{
        background-color: #3C4AB3;
    }
</style>
