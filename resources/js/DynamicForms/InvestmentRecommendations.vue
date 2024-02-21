<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";

import {onMounted, ref, watch} from "vue";
import {usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";
import {changeToCurrency} from "@/currency.js";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})

const cash_element = 2;
const treasured_stock_transfers = 3;

const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                item_types: [],
                owners: [],
                descriptions: []
            },
            model: {
                investment_recommendation_items: [{
                    type: null,
                    source_plan: null,
                    description: null,
                    stock_type: null,
                    number_of_units: null,
                    amount: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

function formatAmount(e, typeIndex, itemIndex, dataField) {
    stepForm.investment_recommendation_items[typeIndex][itemIndex][dataField] = '';
    stepForm.investment_recommendation_items[typeIndex][itemIndex][dataField] = changeToCurrency(e.target.value);

    autosaveLocally()
}

onMounted(()=>{
})

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    investment_recommendation_items: props.formData.model.investment_recommendation_items
})

async function autosaveLocally() {
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.investment_recommendation_items = props.formData.model.investment_recommendation_items;
}

function addItem(typeIndex) {
    if (stepForm.investment_recommendation_items[typeIndex]) {
        stepForm.investment_recommendation_items[typeIndex].push({
            type: parseInt(typeIndex),
            source_plan: null,
            description: null,
            stock_type: null,
            number_of_units: null,
            amount: null
        });
    } else {
        stepForm.investment_recommendation_items[typeIndex] = [{
            type: parseInt(typeIndex),
            source_plan: null,
            description: null,
            stock_type: null,
            number_of_units: null,
            amount: null
        }]
    }
}

function removeItem(typeIndex, itemIndex) {
    let itemId = stepForm.investment_recommendation_items[typeIndex][itemIndex]['id']
    let deleteURL = `/api/investment-recommendation-items/${ itemId }`;

    // delete item record
    if(itemId) {
        axios.delete(deleteURL).then(response=>{
        }).catch(error=>{
            Swal.fire({
                title: 'Error: Something failed. Please try again later.',
                text: error.response.data.message,
            })
        });
    }

    //remove item from props array
    stepForm.investment_recommendation_items[typeIndex].splice(itemIndex, 1);

    setTimeout(()=> {
            autosaveLocally();
        }
        ,500);

}


</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div v-for="(itemType, typeIndex) in formData.enums.item_types" class="border-b-2 mb-6 last-of-type:border-b-0 last-of-type:mb-10" :class="stepForm.investment_recommendation_items[typeIndex] ? 'pb-20' : 'pb-6'">
                <div class="flex flex-row justify-between md:col-span-6 md:pr-2 items-center">
                    <label class="font-bold text-white text-lg">{{ itemType }} </label>
                    <PlusCircleIcon v-if="!stepForm.investment_recommendation_items[typeIndex]" @click="addItem(typeIndex)" class="w-8 h-8 fill-indigo-600"/>
                </div>
                <div v-for="(item, itemIndex) in stepForm.investment_recommendation_items[typeIndex]"
                     class="grid gap-2 border-b-2 border-aaron-500 border-dashed pb-12 last-of-type:border-none last-of-type:border-b-0 last-of-type:pb-8 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                    <div class="flex flex-row justify-end md:col-span-3 md:pr-2"></div>
                    <div class="md:col-span-6 flex flex-row justify-between">
                        <label class="font-bold">{{ itemType }} {{ itemIndex + 1 }}</label>
                        <button type="button" @click="removeItem(typeIndex, itemIndex)"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <XCircleIcon class="w-4 h-4" />Remove Item
                        </button>
                    </div>
                    <div v-if="typeIndex != treasured_stock_transfers" class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                        <label for="source_plan" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Source Plan(s) </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" type="text" name="source_plan" id="source_plan" v-model="item.source_plan" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Source Plan(s)" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.source_plan">{{ stepForm.errors.source_plan }}</p>
                    </div>
                    <div v-if="![treasured_stock_transfers, cash_element].includes(typeIndex)" class="mt-2 md:col-span-3 sm:mt-0 md:pr-2">
                        <label for="description" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Description</label>
                        <select @change="autosaveLocally()" id="description" name="description"
                                v-model="item.description"
                                class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-aaron-700 disabled:text-aaron-50 disabled:border-slate-200 disabled:shadow-none">
                            <option id="description" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(description, id) in formData.enums.descriptions">{{ description }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.description">{{ stepForm.errors.description }}</p>
                    </div>

                    <!-- Type: treasured_stock_transfers start -->
                    <div v-if="typeIndex == treasured_stock_transfers" class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                        <label for="stock_type" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Stock Type </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" type="text" name="stock_type" id="stock_type" v-model="item.stock_type" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Stock Type" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.stock_type">{{ stepForm.errors.stock_type }}</p>
                    </div>
                    <div v-if="typeIndex == treasured_stock_transfers" class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                        <label for="number_of_units" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Number of Units </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveLocally()" type="text" name="number_of_units" id="number_of_units" v-model="item.number_of_units" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Number of Units" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.number_of_units">{{ stepForm.errors.number_of_units }}</p>
                    </div>
                    <!-- Type: treasured_stock_transfers end -->

                    <div class="mt-2 md:col-span-3 sm:mt-0 md:pr-2">
                        <label for="item_owner" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Owner</label>
                        <select @change="autosaveLocally()" id="item_owner" name="item_owner"
                                v-model="item.owner"
                                class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-aaron-700 disabled:text-aaron-50 disabled:border-slate-200 disabled:shadow-none">
                            <option id="item_owner" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(owner, id) in formData.enums.owners">{{ owner }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.item_owner">{{ stepForm.errors.item_owner }}</p>
                    </div>
                    <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                        <label for="amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Amount </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input type="currency" name="amount" id="amount"
                               :value="item.amount" @change="formatAmount($event, typeIndex, itemIndex, 'amount')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.amount">{{ stepForm.errors.amount }}</p>
                    </div>
                </div>
                <button type="button" @click="addItem(typeIndex)" v-if="stepForm.investment_recommendation_items[typeIndex]"
                        class="float-right inline-flex items-center gap-x-1.5 mb-10 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <PlusCircleIcon class="w-6 h-6" />Add {{ itemType }} Item
                </button>
            </div>
        </div>
    </dynamic-form-wrapper>
</template>
