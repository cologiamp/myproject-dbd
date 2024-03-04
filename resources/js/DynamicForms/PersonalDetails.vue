<script setup>
//FACTFIND:// you need to make one of these for every step
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import VueDatePicker from "@vuepic/vue-datepicker";

import '@vuepic/vue-datepicker/dist/main.css'
import {onBeforeMount, onMounted, ref, watch} from "vue";
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
                titles: []
            },
            model: {
                first_name: null,
                middle_name: null,
                last_name: null,
                poa_name: null,
                title: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

function saveDate(value,key){

    stepForm[key].date_of_birth = value;
    autosaveLocally()
}

onBeforeMount(()=>{
    for (const [item, value] of Object.entries(props.formData.model)) {

        stepForm[item] = {};
        stepForm[item]['first_name'] = value.first_name;
        stepForm[item]['middle_name'] = value.middle_name;
        stepForm[item]['last_name'] = value.last_name;
        stepForm[item]['title'] = value.title;
        stepForm[item]['salutation'] = value.salutation;
        stepForm[item]['date_of_birth'] = value.date_of_birth;
        stepForm[item]['gender'] = value.gender;
        stepForm[item]['marital_status'] = value.marital_status;
        stepForm[item]['nationality'] = value.nationality;
        stepForm[item]['ni_number'] = value.ni_number;
        stepForm[item]['country_of_domicile'] = value.country_of_domicile;
        stepForm[item]['country_of_residence'] = value.country_of_residence;
        stepForm[item]['valid_will'] = value.valid_will;
        stepForm[item]['will_up_to_date'] = value.will_up_to_date;
        stepForm[item]['poa_granted'] = value.poa_granted;
        stepForm[item]['poa_name'] = value.poa_name;
    }

})

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{

})
async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    for (const [item, value] of Object.entries(props.formData.model)) {

        stepForm[item] = {};
        stepForm[item]['first_name'] = value.first_name;
        stepForm[item]['middle_name'] = value.middle_name;
        stepForm[item]['last_name'] = value.last_name;
        stepForm[item]['title'] = value.title;
        stepForm[item]['salutation'] = value.salutation;
        stepForm[item]['date_of_birth'] = value.date_of_birth;
        stepForm[item]['gender'] = value.gender;
        stepForm[item]['marital_status'] = value.marital_status;
        stepForm[item]['nationality'] = value.nationality;
        stepForm[item]['ni_number'] = value.ni_number;
        stepForm[item]['country_of_domicile'] = value.country_of_domicile;
        stepForm[item]['country_of_residence'] = value.country_of_residence;
        stepForm[item]['valid_will'] = value.valid_will;
        stepForm[item]['will_up_to_date'] = value.will_up_to_date;
        stepForm[item]['poa_granted'] = value.poa_granted;
        stepForm[item]['poa_name'] = value.poa_name;
    }
}


</script>

<template>

    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="grid gap-2 md:grid md:items-start md:gap-y-8 md:gap-x-4" :class="'md:grid-cols-'+ (Object.keys(props.formData.model).length)">
                <div v-if="stepForm" :class="Object.keys(props.formData.model).length > 1 ? 'bg-aaron-850' : ''" class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4 rounded-md p-2" v-for="(model,key,index) in props.formData.model" >
                    <h4 v-if="Object.keys(props.formData.model).length > 1" class="col-span-6 text-xl font-bold pt-2"> Client {{index + 1}} </h4>

                    <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2  sm:mt-0 md:pr-2">
                        <label for="title" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Title</label>
                        <select @change="autosaveLocally()" v-model="stepForm[key].title" id="unit" name="title"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="title" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(title, id) in props.formData.enums.titles">{{ title }}</option>
                        </select>
                    </div>
                    <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2  sm:mt-0 md:pr-2 ">
                        <label for="first_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> First Name </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" v-model="stepForm[key].first_name" type="text" name="first_name" id="first_name"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="First Name" />
                        </div>
                    </div>

                    <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2 sm:mt-0 md:pr-2">
                        <label for="middle_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Middle Name(s) </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" v-model="stepForm[key].middle_name" type="text" name="middle_name" id="middle_name"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Middle Name" />
                        </div>
                    </div>

                    <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2 sm:mt-0 md:pr-2">
                        <label for="last_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Last Name </label>
                        <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" v-model="stepForm[key].last_name" type="text" name="last_name" id="last_name"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md  bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Last Name" />
                        </div>
                    </div>


                    <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2 sm:mt-0 md:pr-2">
                        <label for="salutation" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> What should we call you? </label>
                        <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" v-model="stepForm[key].salutation" type="text" name="salutation" id="salutation"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md  bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Salutation" />
                        </div>
                    </div>

                    <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2 sm:mt-0 md:pr-2">
                        <label for="last_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Date of Birth </label>
                        <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                            <VueDatePicker text-input  @update:model-value="saveDate($event,key)" class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" :model-value="stepForm[key].date_of_birth" name="date_of_birth" id="date_of_birth"  placeholder="dd/mm/yyyy"/>
                        </div>
                    </div>

                    <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2 sm:mt-0 md:pr-2">
                        <label for="gender" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Gender</label>
                        <select @change="autosaveLocally()" v-model="stepForm[key].gender" id="unit" name="gender"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="gender" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(gender, id) in formData.enums.genders">{{ gender }}</option>
                        </select>
                    </div>

                    <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2 sm:mt-0 md:pr-2">
                        <label for="marital_status" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Marital Status</label>
                        <select @change="autosaveLocally()" v-model="stepForm[key].marital_status" id="unit" name="marital_status"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="marital_status" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(marital_status, id) in formData.enums.marital_statuses">{{ marital_status }}</option>
                        </select>
                    </div>

                    <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2 sm:mt-0 md:pr-2">
                        <label for="nationality" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Nationality</label>
                        <select @change="autosaveLocally()" v-model="stepForm[key].nationality" id="unit" name="nationality"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="nationality" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(nationality, id) in formData.enums.nationalities">{{ nationality }}</option>
                        </select>
                    </div>

                    <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2 sm:mt-0 md:pr-2">
                        <label for="ni_number" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> National Insurance Number </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" v-model="stepForm[key].ni_number" type="text" name="ni_number" id="ni_number"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="National Insurance Number" />
                        </div>
                    </div>

                    <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2 sm:mt-0 md:pr-2">
                        <label for="country_of_domicile" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Country of Domicile</label>
                        <select @change="autosaveLocally()" v-model="stepForm[key].country_of_domicile" id="unit" name="country_of_domicile"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="country_of_domicile" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(domicile, id) in formData.enums.country_of_domiciles">{{ domicile }}</option>
                        </select>
                    </div>

                    <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)"  class="mt-2 sm:mt-0 md:pr-2">
                        <label for="country_of_residence" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Country of Residence</label>
                        <select @change="autosaveLocally()" v-model="stepForm[key].country_of_residence" id="unit" name="country_of_residence"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="country_of_residence" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(residence, id) in formData.enums.country_of_residences">{{ residence }}</option>
                        </select>
                    </div>

                    <div class="col-span-6 grid grid-cols-6 rounded-md bg-aaron-950 pt-2 p-4 ">
                        <h4 class="col-span-6 text-xl font-bold pt-2"> Will </h4>
                        <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2 sm:mt-0 md:pr-2">
                            <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Do you have a valid will?</label>
                            <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                                <input @change="autosaveLocally()" v-model="stepForm[key].valid_will" type="radio" id="true" :value="true" :checked="stepForm[key].valid_will == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                                <input @change="autosaveLocally()" v-model="stepForm[key].valid_will" type="radio" id="false" :value="false" :checked="stepForm[key].valid_will == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                            </div>
                        </div>
                        <div :class="'col-span-'+ (Object.keys(props.formData.model).length * 3)" class="mt-2 sm:mt-0 md:pr-2">
                            <div v-if="stepForm[key].valid_will == true">
                                <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Is it up to date?</label>
                                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                                    <input @change="autosaveLocally()" v-model="stepForm[key].will_up_to_date" type="radio" id="true" :value="true" :checked="stepForm[key].will_up_to_date == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                    <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                                    <input @change="autosaveLocally()" v-model="stepForm[key].will_up_to_date" type="radio" id="false" :value="false" :checked="stepForm[key].will_up_to_date == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                    <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-6 grid grid-cols-6 rounded-md bg-aaron-950 pt-2 p-4">
                        <h4 class="col-span-6 text-xl font-bold pt-2"> Power of Attorney </h4>
                        <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                            <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Power of Attorney Granted?</label>
                            <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                                <input @change="autosaveLocally()" v-model="stepForm[key].poa_granted" type="radio" id="true" :value="true" :checked="stepForm[key].poa_granted == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                                <input @change="autosaveLocally()" v-model="stepForm[key].poa_granted" type="radio" id="false" :value="false" :checked="stepForm[key].poa_granted == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                                <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                            </div>
                        </div>

                        <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                            <div v-if="stepForm[key].poa_granted == true">
                                <label for="poa_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Attorney Name </label>
                                <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                                    <input @change="autosaveLocally()" v-model="stepForm[key].poa_name" type="text" name="poa_name" id="poa_name"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Attorney Name" />
                                </div>
                            </div>
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
