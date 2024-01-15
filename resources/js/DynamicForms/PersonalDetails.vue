<script setup>
//FACTFIND:// you need to make one of these for every step
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import VueDatePicker from "@vuepic/vue-datepicker";

import '@vuepic/vue-datepicker/dist/main.css'
import {onMounted, ref, watch} from "vue";

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
                last_name: null,
                title: null,
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

let dateRef = ref();
function saveDate(value){
    dateRef.value = value;
    stepForm.date_of_birth = value;
    autosaveT(stepForm,props.formData.submit_url)
}

onMounted(()=>{
    dateRef.value = props.formData.model.date_of_birth;
})

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    first_name: props.formData.model.first_name,
    last_name: props.formData.model.last_name,
    title: props.formData.model.title,
    date_of_birth: props.formData.model.date_of_birth,
    gender: props.formData.model.gender
})


</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="md:grid  md:grid-cols-6 md:items-start">
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="title" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Title</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.title" id="unit" name="title"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="title" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(title, id) in formData.enums.titles">{{ title }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.title">{{ stepForm.errors.title }}</p>
                </div>


                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="first_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> First Name </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.first_name" type="text" name="first_name" id="first_name"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="First Name" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.first_name">{{ stepForm.errors.first_name }}</p>
                </div>

                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="last_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Last Name </label>
                    <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.last_name" type="text" name="last_name" id="last_name"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md  bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Last Name" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.last_name">{{ stepForm.errors.last_name }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="last_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Date of Birth </label>
                    <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input  @update:model-value="saveDate" class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" :model-value="dateRef" name="date_of_birth" id="date_of_birth"  placeholder="dd/mm/yyyy"/>
                    </div>

                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.date_of_birth">{{ stepForm.errors.date_of_birth }}</p>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="gender" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Gender</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.gender" id="unit" name="gender"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="gender" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(gender, id) in formData.enums.genders">{{ gender }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.gender">{{ stepForm.errors.gender }}</p>
                </div>



                <!--                if(array_key_exists('gender',$person) && $person['gender'] != null)-->
<!--                {-->
<!--                $data['gender'] =  array_flip(config('enums.client.gender'))[$person['gender']] ;-->
<!--                }-->
<!--                if(array_key_exists('maritalStatus',$person) && $person['maritalStatus'] != null)-->
<!--                {-->
<!--                $data['marital_status'] = array_flip(config('enums.client.marital_status'))[$person['maritalStatus']];-->
<!--                }-->
<!--                if(array_key_exists('NationalityCountry',$person) && $person['NationalityCountry']['name'] != null)-->
<!--                {-->
<!--                $data['nationality'] = array_flip(config('enums.client.nationality'))[$person['NationalityCountry']['name']];-->
<!--                }-->
<!--                if(array_key_exists('salutation',$person) && $person['salutation'] != null)-->
<!--                {-->
<!--                $data['salutation'] = $person['salutation'];-->
<!--                }-->
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
