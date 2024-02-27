<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";

import {onMounted, watch} from "vue";
import {usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";
import {changeToCurrency} from "@/currency.js";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                types: []
            },
            model: {
                pr_items: [{
                    id: null,
                    pension_recommendation_id: null,
                    type: null,
                    value: null,
                    is_percentage: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

onMounted(()=>{
})

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    pr_items: props.formData.model.pr_items
})

async function autosaveLocally(index){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.pr_items = props.formData.model.pr_items
}

function removeItem(index) {
    if(stepForm.pr_items[index].id != null) {
        axios.delete('/api/pr-items/'+ stepForm.pr_items[index].id).then(function (response){
            console.log(response.data)
        }).catch(function (e){
            console.log(e)
        });
    }

    stepForm.pr_items.splice(index, 1);
}

function addItem() {
    stepForm.pr_items.push(
        {
            pension_recommendation_id: null,
            type: null,
            value: '0',
            percentage: '0',
            is_percentage: false
        }
    );
}

function formatAmount(e, index) {
    stepForm.pr_items[index].value = '';
    stepForm.pr_items[index].value = changeToCurrency(e.target.value);
    autosaveLocally()
}

function formatPercentage(e, index) {
    stepForm.pr_items[index].percentage = '';
    stepForm.pr_items[index].percentage = e.target.value + '%';
    autosaveLocally()
}

function setPercentage(index) {
    if (stepForm.pr_items[index].is_percentage === true && stepForm.pr_items[index].percentage == null) {
        stepForm.pr_items[index].percentage = '0'
    }
    if (stepForm.pr_items[index].is_percentage === false && stepForm.pr_items[index].value != null) {
        stepForm.pr_items[index].value = stepForm.pr_items[index].value + '00'
    }
    autosaveLocally()
}

</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div v-if="stepForm.pr_items.length > 0" class="px-4 sm:px-6 lg:px-8 mb-6">
                <div class="flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-2">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="1" class="py-3 pl-4 pr-0 text-left font-medium tracking-wide sm:pl-0">Type</th>
                                        <th scope="col" colspan="1" class="py-3 pl-4 pr-0 text-left font-medium tracking-wide">Value</th>
                                        <th scope="col" colspan="1" class="py-3 pl-4 pr-0 text-left font-medium tracking-wide">Percentage</th>
                                        <th scope="col" class="px-0 py-3 text-left text-sm font-medium tracking-wide">Is percentage</th>
                                        <th scope="col" class="px-0 py-3 text-left text-sm font-medium tracking-wide"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 ">
                                    <tr v-for="(pr_item, index) in stepForm.pr_items">
                                        <td class="w-1/4 whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-0">
                                            <select @change="autosaveLocally()" v-model="pr_item.type" id="type" name="type"
                                                class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                                                <option id="type" :value="null">-</option>
                                                <option :id="id" :value="id" v-for="(type, id) in formData.enums.types">{{ type }}</option>
                                            </select>
                                            <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.belongs_to">{{
                                                    stepForm.errors.belongs_to }}</p>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <input type="currency" name="value" id="value"
                                               :value="pr_item.value" @change="formatAmount($event, index)" :disabled="pr_item.is_percentage"
                                                class="w-36 block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-gray-500 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <input type="text" name="percentage" id="percentage"
                                               :value="pr_item.percentage" @change="formatPercentage($event, index)" :disabled="pr_item.is_percentage == false"
                                                class="w-24 block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-gray-500 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="%" />
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-6 text-sm flex justify-center items-center">
                                            <input id="is_percentage" v-model="pr_item.is_percentage" @change="setPercentage(index)" name="is_percentage" type="checkbox"
                                               class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400 disabled:bg-gray-500 disabled:cursor-not-allowed disabled:hover:bg-gray-500" />
                                        </td>
                                        <td class="whitespace-nowrap px-0 py-6 text-sm w-6">
                                            <button type="button" @click="removeItem(index)">
                                                <XCircleIcon class="w-6 h-6" />
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" @click="addItem()"
                class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add Item
            </button>
        </div>
    </dynamic-form-wrapper>
</template>
