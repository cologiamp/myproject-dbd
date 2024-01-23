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
                address: null,
                phone_number: null,
                email_address: null
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
    stepForm.addresses[0].date_from = value;
    autosaveT(stepForm,props.formData.submit_url)
}

onMounted(()=>{
    dateRef.value = props.formData.model.addresses[0].date_from;
})

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    client_id: props.formData.model.client_id,
    addresses: props.formData.model.addresses,
    phone_number: props.formData.model.phone_number,
    email_address: props.formData.model.email_address
})


</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div v-for="(address, index) in stepForm.addresses" class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="line_1" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Address Line 1 </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="address.address_line_1" type="text" name="address_line_1" id="address_line_1"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Address Line 1"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.address_line_1">{{ stepForm.errors.address_line_1 }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="line_2" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Address Line 2 </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="address.address_line_2" type="text" name="address_line_2" id="address_line_2"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Address Line 2"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.address_line_2">{{ stepForm.errors.address_line_2 }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="city" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> City / Town </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="address.city" type="text" name="city" id="city" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="City / Town"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.city">{{ stepForm.errors.city }}</p>
                </div><div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="county" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> County </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="address.county" type="text" name="county" id="county"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="County"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.county">{{ stepForm.errors.county }}</p>
                </div><div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="postcode" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Postcode </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="address.postcode" type="text" name="postcode" id="postcode"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Postcode"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.postcode">{{ stepForm.errors.postcode }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="country" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Country</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="address.country" id="country" name="country" class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="country" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(country, id) in formData.enums.countries">{{ country }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.country">{{ stepForm.errors.country }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="residency_status" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Ownership</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="address.residency_status" id="residency_status" name="residency_status" class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="residency_status" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(residency_status, id) in formData.enums.residency_status">{{ residency_status }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.residency_status">{{ stepForm.errors.residency_status }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="date_from" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Date From </label>
                    <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @update:model-value="saveDate" class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" :model-value="dateRef" name="date_from" id="date_from"  placeholder="dd/mm/yyyy"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.date_from">{{ stepForm.errors.date_from }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="phone_number" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Phone Number </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.phone_number" type="text" name="phone_number" id="phone_number"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Phone Number"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.phone_number">{{ stepForm.errors.phone_number }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="email_address" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Email Address </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.email_address" type="text" name="email_address" id="email_address"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Email Address"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.email_address">{{ stepForm.errors.email_address }}</p>
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
