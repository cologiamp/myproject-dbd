<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";

import {onMounted, ref, watch} from "vue";
import {usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";
import {changeToCurrency} from "@/currency.js";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})
const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                fee_basis: []
            },
            model: {
                is_ethical_investor: null,
                risk_profile: null,
                previously_invested_amount: null,
                fee_basis: null,
                fee_basis_discount: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

function formatAmount(e, dataField) {
    stepForm[dataField] = '';
    stepForm[dataField] = changeToCurrency(e.target.value);

    autosaveLocally()
}

onMounted(()=>{
})

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    is_ethical_investor: props.formData.model.is_ethical_investor,
    risk_profile: props.formData.model.risk_profile,
    previously_invested_amount: props.formData.model.previously_invested_amount,
    fee_basis: props.formData.model.fee_basis,
    fee_basis_discount: props.formData.model.fee_basis_discount
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.is_ethical_investor = props.formData.model.is_ethical_investor;
    stepForm.risk_profile = props.formData.model.risk_profile;
    stepForm.previously_invested_amount = props.formData.model.previously_invested_amount;
    stepForm.fee_basis = props.formData.model.fee_basis;
    stepForm.fee_basis_discount = props.formData.model.fee_basis_discount;
}


</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="is_ethical_investor" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Is this an Ethical Investor?</label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.is_ethical_investor" type="radio" id="true" :value="true" :checked="stepForm.is_ethical_investor == true" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()" v-model="stepForm.is_ethical_investor" type="radio" id="false" :value="false" :checked="stepForm.is_ethical_investor == false" class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.is_ethical_investor">{{ stepForm.errors.is_ethical_investor }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <!-- To Do: add [Risk Profile] enums when available -->
                    <label for="risk_profile" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Risk Profile</label>
                    <select @change="autosaveLocally()" v-model="stepForm.risk_profile" id="unit" name="risk_profile"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="title" :value="null">-</option>
                        <!--<option :id="id" :value="id" v-for="(title, id) in formData.enums.titles">{{ risk_profile }}</option>-->
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.risk_profile">{{ stepForm.errors.risk_profile }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="previously_invested_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Previously Invested Amount </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="previously_invested_amount" id="previously_invested_amount"
                           :value="stepForm.previously_invested_amount"
                           @change="formatAmount($event, 'previously_invested_amount')"
                           class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.previously_invested_amount">{{ stepForm.errors.previously_invested_amount }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="fee_basis" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Fee Basis</label>
                    <select @change="autosaveLocally()" v-model="stepForm.fee_basis" id="fee_basis" name="fee_basis"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="title" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(fee_basis, id) in formData.enums.fee_basis">{{ fee_basis }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.fee_basis">{{ stepForm.errors.fee_basis }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <div v-if="stepForm.fee_basis == 1">
                        <label for="previously_invested_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Fee Basis Discount </label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input type="currency" name="fee_basis_discount" id="fee_basis_discount"
                               :value="stepForm.fee_basis_discount"
                               @change="formatAmount($event, 'fee_basis_discount')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.fee_basis_discount">{{ stepForm.errors.fee_basis_discount }}</p>
                    </div>
                </div>
            </div>
        </div>
    </dynamic-form-wrapper>
</template>
