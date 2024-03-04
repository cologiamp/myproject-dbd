<script setup>
//FACTFIND:// you need to make one of these for every step
import {__error, autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import VueDatePicker from "@vuepic/vue-datepicker";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';
import {changeToCurrency} from "@/currency.js";
import '@vuepic/vue-datepicker/dist/main.css'
import {computed, onMounted, ref, watch} from "vue";
import FormErrors from "@/Components/FormErrors.vue";

const emit = defineEmits(['autosaveStateChange'])


watch(autoS, (newValue, oldValue) => {
    emit('autosaveStateChange', newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                asset_types: [],
                owners: []
            },
            model: {
                fixed_assets: [{
                    id: null,
                    owner: null,
                    percent_ownership: [],
                    asset_type: null,
                    description: null,
                    purchased_at: null,
                    original_value: null,
                    current_value: null,
                    is_retained: null,
                    retained_value: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

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
// let dateRef = ref();


function formatAmount(e, index, dataField) {
    stepForm.fixed_assets[index][dataField] = '';
    stepForm.fixed_assets[index][dataField] = changeToCurrency(e.target.value);
    autosaveLocally()
}

function saveDate(index, value) {
    stepForm.fixed_assets[index].purchased_at = value;
    autosaveLocally();
}

function addAsset() {
    if(Object.keys(props.formData.enums.owners).length === 1)
    {
        let owner = Object.keys(props.formData.enums.owners)[0];

        stepForm.fixed_assets.push({
            id: null,
            owner: owner,
            asset_type: null,
            percent_ownership: {
                [owner]:100
            },
            description: null,
            original_value: null,
            current_value: null,
            retained_value: null,
            purchased_at: null,
            is_retained: null
        });
    }
    else{
        stepForm.fixed_assets.push({
            id: null,
            owner: null,
            asset_type: null,
            percent_ownership: {},
            description: null,
            original_value: null,
            current_value: null,
            retained_value: null,
            purchased_at: null,
            is_retained: null
        });
    }
}


// onMounted(() => {
//     dateRef.value = props.formData.model.born_at;
// })

const stepForm = useForm({
    fixed_assets: props.formData.model.fixed_assets
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.fixed_assets = props.formData.model.fixed_assets;
}

function removeAsset(index) {
    console.log(stepForm.fixed_assets[index]);
    if(stepForm.fixed_assets[index].id != null)
    {
        axios.delete('/api/assets/'+ stepForm.fixed_assets[index].id).then(function (response){
            console.log(response.data)
        }).catch(function (e){
            console.log(e)
        });
    }
    stepForm.fixed_assets.splice(index, 1);
}


function resetOwnerPercentages(index)
{
    if( stepForm.fixed_assets[index].owner != 'Both')
    {
        let enteredPercent = stepForm.fixed_assets[index].percent_ownership[stepForm.fixed_assets[index].owner];
        if(!enteredPercent)
        {
            enteredPercent = 100;
        }
        stepForm.fixed_assets[index].percent_ownership = {};
        stepForm.fixed_assets[index].percent_ownership[stepForm.fixed_assets[index].owner] = enteredPercent;
        autosaveLocally();
    }
}



</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <form-errors :errors="usePage().props.errors"/>
            <div v-for="(asset, index) in stepForm.fixed_assets"
                 class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold">Asset {{ index + 1 }}</label>
                    <button type="button" @click="removeAsset(index)"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Delete Asset
                    </button>
                </div>
<!--                Asset Type-->
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="relationship_type"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Asset Type</label>
                    <select @change="autosaveLocally()" v-model="asset.asset_type"
                            id="asset_type" name="asset_type"
                            class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="asset_type" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(assetType, id) in formData.enums.asset_types">{{
                                assetType }}</option>
                    </select>
                </div>

                <div class="col-span-6 grid grid-cols-6 rounded-md bg-aaron-950 pt-2 p-4 ">
                    <h4 class="col-span-6 text-xl font-bold pt-2"> Ownership </h4>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="owner"
                               class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Owner</label>
                        <select @change="resetOwnerPercentages(index)" v-model="asset.owner"
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
                    <div v-if="asset.owner === 'Both'" class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2" v-for="(owner,io) in ownersNotBoth">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">{{owner[1]}} (%)</label>
                        <div class="mt-2 w-full">
                            <input @change="autosaveLocally" placeholder="%" v-model="asset.percent_ownership[owner[0]]" type="number" min="0" max="100" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none  w-full">
                        </div>
                    </div>
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2" v-else-if="asset.owner">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">{{formData.enums.owners[asset.owner]}} (%)</label>
                        <div class="flex shadow-sm rounded-md   focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">

                            <input @change="autosaveLocally"  v-model="asset.percent_ownership[asset.owner]" value="100" type="number" min="0" max="100" class="cursor-not-allowed block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-aaron-800 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"  />


                        </div>

                    </div>
                </div>



                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Description</label>
                    <div class="mt-2">
                        <textarea @change="autosaveLocally()" v-model="asset.description" rows="3" name="description" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="born_at"
                           class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2">Purchased date </label>
                    <div
                        class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md date-wrapper">
                        <VueDatePicker text-input @closed="saveDate(index, asset.purchased_at)"
                                       class="aaron-datepicker ring-aaron-600" dark utc format="dd/MM/yyyy" v-model="asset.purchased_at"
                                       name="purchased_at" id="purchased_at" placeholder="dd/mm/yyyy" />
                    </div>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Purchased for (£) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'original_value')" type="currency" name="original_value" id="original_value"
                               :value="asset.original_value"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Current Value (£) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'current_value')" type="currency" name="current_value" id="current_value"
                               :value="asset.current_value"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Is Retained?</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()"
                               v-model="asset.is_retained" type="radio" id="true" :value="true"
                               :checked="asset.is_retained == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()"
                               v-model="asset.is_retained" type="radio" id="false" :value="false"
                               :checked="asset.is_retained == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3" v-show="asset.is_retained">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Retained Value (£) </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input @change="formatAmount($event, index, 'retained_value')" type="currency" name="retained_value" id="retained_value"
                               :value="asset.retained_value"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                </div>
            </div>
            <button type="button" @click="addAsset"
                    class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add Asset
            </button>
        </div>
    </dynamic-form-wrapper></template>

<style scoped>.aaron-datepicker {
    --dp-background-color: rgb(49 63 167 / var(--tw-bg-opacity));
    --dp-text-color: rgb(236 245 255 / var(--tw-text-opacity));
    --dp-border-radius: 6px;
    --dp-border-color: rgb(70 84 190 / var(--tw-ring-opacity));
}</style>
