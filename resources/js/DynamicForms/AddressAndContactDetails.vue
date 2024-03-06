<script setup>
//FACTFIND:// you need to make one of these for every step
import {autoS, autosaveT, delay} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import VueDatePicker from "@vuepic/vue-datepicker";

import '@vuepic/vue-datepicker/dist/main.css'
import {computed, onBeforeMount, onMounted, ref, watch} from "vue";
import {PlusCircleIcon, XCircleIcon} from "@heroicons/vue/24/solid/index.js";
import FormErrors from "@/Components/FormErrors.vue";
import { DateTime } from "luxon";

const emit = defineEmits(['autosaveStateChange'])
watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})
const ownersNotBoth = computed(() => {
    if(props.formData.enums.owners)
    {
        console.log(props.formData.enums.owners)
        return Object.entries(props.formData.enums.owners).slice(0, -1)
    }
    else{
        return [];
    }
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
                mobile_number: null,
                email_address: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

let dateRef = ref();
const filteredEntries = ref({});
const remove = ['addresses','client_id'];


async function saveDate(index,value){
    dateRef.value = value;
    stepForm.addresses[index].date_from = value;
    await autosaveLocally()


    let now = DateTime.now().minus({years: 1});
    let then = DateTime.fromISO(value)
    if(then > now)
    {
        await delay(1000); //workaround to prevent it being autosaved away
        //less than one year since date
        addAddress();
    }
}

function addAddress() {
    stepForm.addresses.push({
        address_id: null,
        address_line_1: null,
        address_line_2: null,
        city: null,
        country: null,
        county: null,
        date_from: null,
        postcode: null,
        residency_status: null
    });
}

function removeAddress(index) {
    if(stepForm.addresses[index].address_id != null)
    {
        axios.delete('/api/addresses/'+ stepForm.addresses[index].address_id).then(function (response){
            console.log(response.data)
        }).catch(function (e){
            console.log(e)
        });
    }
    stepForm.addresses.splice(index, 1);
}

onMounted(()=>{
    if(Object.keys(props.formData.model.addresses).length > 0)
    {
        dateRef.value = props.formData.model.addresses[0].date_from;
    }
})



const stepForm = useForm({
    client_id: props.formData.model.client_id,
    addresses: props.formData.model.addresses,
})

onBeforeMount(()=>{

    filteredEntries.value = Object.keys(props.formData.model)
        .filter(key => !remove.includes(key))
        .reduce((obj, key) => {
            obj[key] = props.formData.model[key];
            return obj;
        }, {});
    for (const [item, value] of Object.entries(filteredEntries.value)) {

        stepForm[item] = {};
        stepForm[item]['mobile_number'] = value.mobile_number;
        stepForm[item]['phone_number'] = value.phone_number;
        stepForm[item]['email_address'] = value.email_address;
    }
})

function resetOwnerPercentages(index)
{
    if(Object.keys(filteredEntries.value).length > 1)
    {
        if( stepForm.addresses[index].owner != 'Both')
        {
            let enteredPercent = stepForm.addresses[index].percent_ownership[stepForm.addresses[index].owner];
            stepForm.addresses[index].percent_ownership = {};
            stepForm.addresses[index].percent_ownership[stepForm.addresses[index].owner] = enteredPercent;
            autosaveLocally();
        }
    }
}

function autosavePercentOwnership()
{
    if(Object.keys(filteredEntries.value).length > 1)
    {
        stepForm.addresses = Object.keys(stepForm.addresses)
            .reduce((obj, key) => {
                var mutate = stepForm.addresses[key];

                if(Object.keys(mutate.percent_ownership).length === 1)
                {
                    // Object.keys(mutate.percent_ownership)[0]
                    if(Object.keys(mutate.percent_ownership)[0] == Object.keys(filteredEntries.value)[0])
                    {
                        let percent = mutate.percent_ownership[Object.keys(filteredEntries.value)[0]];
                        if(percent < 100)
                        {
                            mutate.percent_ownership[Object.keys(filteredEntries.value)[1]] = 100 - percent;
                        }
                        else{
                            mutate.percent_ownership[Object.keys(filteredEntries.value)[1]] = 0;
                        }
                    }
                    else{
                        let percent = mutate.percent_ownership[Object.keys(filteredEntries.value)[1]];
                        if(percent < 100)
                        {
                            mutate.percent_ownership[Object.keys(filteredEntries.value)[1]] = 100 - percent;
                        }
                        else{
                            mutate.percent_ownership[Object.keys(filteredEntries.value)[1]] = 0;
                        }
                    }
                }

                obj[key] = mutate;
                return obj;
            }, {});
    }
    autosaveLocally();
}

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.client_id = props.formData.model.client_id;
    stepForm.addresses = props.formData.model.addresses;
    filteredEntries.value = Object.keys(props.formData.model)
        .filter(key => !remove.includes(key))
        .reduce((obj, key) => {
            obj[key] = props.formData.model[key];
            return obj;
        }, {});
    for (const [item, value] of Object.entries(filteredEntries.value)) {
        stepForm[item] = {};
        stepForm[item]['mobile_number'] = value.mobile_number;
        stepForm[item]['phone_number'] = value.phone_number;
        stepForm[item]['email_address'] = value.email_address;
    }

}

</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <label class="font-bold mt-3" >Contact Details</label>
            <div class="grid gap-2 md:grid md:items-start md:gap-y-8 md:gap-x-4" :class="'md:grid-cols-'+ (Object.keys(filteredEntries).length)">
                <div v-if="stepForm" :class="Object.keys(filteredEntries).length > 1 ? 'bg-aaron-850' : ''" class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4 rounded-md p-2" v-for="(model,key,index) in filteredEntries" >
                    <h4 v-if="(Object.keys(filteredEntries).length > 1)" class="col-span-6 text-xl font-bold pt-2"> Client {{index + 1}} </h4>
                    <div :class="'col-span-'+ (Object.keys(filteredEntries).length * 3)" class="mt-2  sm:mt-0 md:pr-2">
                        <label for="phone_number" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Phone Number </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" v-model="stepForm[key].phone_number" type="text" name="phone_number" id="phone_number"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Phone Number"/>
                        </div>
                    </div>
                    <div :class="'col-span-'+ (Object.keys(filteredEntries).length * 3)" class="mt-2  sm:mt-0 md:pr-2">
                        <label for="mobile_number" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Mobile Number </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" v-model="stepForm[key].mobile_number" type="text" name="mobile_number" id="mobile_number"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Mobile Number"/>
                        </div>
                    </div>
                    <div :class="'col-span-'+ (Object.keys(filteredEntries).length * 3)" class="mt-2  sm:mt-0 md:pr-2">
                        <label for="email_address" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Email Address </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" v-model="stepForm[key].email_address" type="text" name="email_address" id="email_address"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Email Address"/>
                        </div>
                    </div>
                </div>
            </div>

            <div v-for="(address, index) in stepForm.addresses" class="grid pt-4 gap-2 md:grid md:grid-cols-6  md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold mt-2" v-if="index === 0">Primary Address</label>
                    <label class="font-bold" v-else>Address {{ index + 1 }}</label>
                    <button type="button" v-if="index !== 0" @click="removeAddress(index)"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Remove Address
                    </button>
                </div>

                <div class="md:mt-0 md:pr-2 md:col-span-3" >
                    <label for="line_1" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Address Line 1 </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="address.address_line_1" type="text" name="address_line_1" id="address_line_1"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Address Line 1"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.address_line_1">{{ stepForm.errors.address_line_1 }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="line_2" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Address Line 2 </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="address.address_line_2" type="text" name="address_line_2" id="address_line_2"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Address Line 2"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.address_line_2">{{ stepForm.errors.address_line_2 }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="city" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> City / Town </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="address.city" type="text" name="city" id="city" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="City / Town"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.city">{{ stepForm.errors.city }}</p>
                </div><div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="county" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> County </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="address.county" type="text" name="county" id="county"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="County"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.county">{{ stepForm.errors.county }}</p>
                </div><div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="postcode" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Postcode </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="autosaveLocally()" v-model="address.postcode" type="text" name="postcode" id="postcode"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Postcode"/>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.postcode">{{ stepForm.errors.postcode }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="country" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Country</label>
                    <select @change="autosaveLocally()" v-model="address.country" id="country" name="country" class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="country" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(country, id) in  props.formData.enums.countries">{{ country }}</option>
                    </select>
                </div>

                <div class="col-span-6 grid grid-cols-6 rounded-md bg-aaron-950 pt-2 p-4 ">
                    <h4 class="col-span-6 text-xl font-bold pt-2"> Ownership </h4>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="residency_status" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Ownership Status</label>
                        <select @change="autosaveLocally()" v-model="address.residency_status" id="residency_status" name="residency_status" class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="residency_status" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(residency_status, id) in formData.enums.residency_status">{{ residency_status }}</option>
                        </select>
                    </div>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="owner"
                               class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Owner</label>
                        <select @change="resetOwnerPercentages(index)" v-model="address.owner"
                                id="owner" name="owner"
                                class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="owner" :value="null">-</option>
                            <option :id="id" :value="id"  v-for="(owner, id) in formData.enums.owners">
                                {{owner}}
                            </option>
                        </select>
                    </div>
                    <div class="col-span-6 grid grid-cols-6 rounded-md bg-aaron-950 pt-2 p-4" >

                    </div>
                    <div v-if="address.owner === 'Both'" class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2" v-for="(owner,io) in ownersNotBoth">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">{{owner[1]}} (%)</label>
                        <div class="mt-2 w-full">
                            <input @change="autosavePercentOwnership()" placeholder="%" v-model="address.percent_ownership[owner[0]]" type="number" min="0" max="100" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none  w-full">
                        </div>
                    </div>
<!--                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2" v-else-if="address.owner">-->
<!--                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">{{formData.enums.owners[address.owner]}} (%)</label>-->
<!--                        <div class="flex shadow-sm rounded-md   focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">-->
<!--                            <input @change="autosaveLocally"  v-model="address.percent_ownership[address.owner]" value="100" type="number" min="0" max="100" class="cursor-not-allowed block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-aaron-800 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"  />-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="date_from" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Date From </label>
                    <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveDate(index,address.date_from)"
                                       class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy"
                                       v-model="address.date_from" name="date_from" id="date_from"
                                       placeholder="dd/mm/yyyy"/>
                    </div>
                 </div>
            </div>
            <div class="grid gap-2 md:grid-cols-4 md:gap-y-8 md:gap-x-4 pr-2 pt-6">
                <button type="button" @click="addAddress"
                        class="inline-flex  items-center gap-x-1.5 rounded-md
                         bg-indigo-600 px-3 py-2 text-sm font-semibold text-white col-start-4
                         shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2
                         focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <PlusCircleIcon class="w-6 h-6" />Add Address
                </button>
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
