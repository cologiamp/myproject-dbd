<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import { XCircleIcon } from '@heroicons/vue/24/solid';

import {onMounted, watch, ref} from "vue";
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
                cta_base_costs_available: null,
                cta_sell_to_cgt_exemption: null,
                cta_sell_all: null,
                cta_sell_set_amount: null,
                isa_transfer_exit_penalty_not_ascertained: null,
                isa_transfer_exit_penalty_ascertained: null,
                investment_bonds_managed_funds: null,
                investment_bonds_with_profits: null,
                investment_bonds_chargeable_gain_not_calculated: null,
                investment_bonds_exit_penalty_not_ascertained: null,
                investment_bonds_exit_penalty_ascertained: null,
                investment_bonds:[{
                    provider: null,
                    initial_investment: null,
                    surrender_value: null,
                    withdrawals: null,
                    total_gain: null,
                    complete_years_held: null,
                    top_slice: null
                }]
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

function formatAmount(e, index, dataField) {
    stepForm.investment_bonds[index][dataField] = '';
    stepForm.investment_bonds[index][dataField] = changeToCurrency(e.target.value);

    autosaveLocally();
}

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    id: props.formData.model.id,
    cta_base_costs_availability: props.formData.model.cta_base_costs_availability,
    cta_base_costs_available: props.formData.model.cta_base_costs_available,
    cta_sell_to_cgt_exemption: props.formData.model.cta_sell_to_cgt_exemption,
    cta_sell_all: props.formData.model.cta_sell_all,
    cta_sell_set_amount_check: props.formData.model.cta_sell_set_amount_check,
    cta_sell_set_amount: props.formData.model.cta_sell_set_amount,
    dta_base_costs_availability: props.formData.model.dta_base_costs_availability,
    dta_base_costs_available: props.formData.model.dta_base_costs_available,
    dta_sell_to_cgt_exemption: props.formData.model.dta_sell_to_cgt_exemption,
    dta_sell_all: props.formData.model.dta_sell_all,
    dta_sell_set_amount_check: props.formData.model.dta_sell_set_amount_check,
    dta_sell_set_amount: props.formData.model.dta_sell_set_amount,
    isa_transfer_exit_penalty_not_ascertained: props.formData.model.isa_transfer_exit_penalty_not_ascertained,
    isa_transfer_exit_penalty_ascertained_check: props.formData.model.isa_transfer_exit_penalty_ascertained_check,
    isa_transfer_exit_penalty_ascertained: props.formData.model.isa_transfer_exit_penalty_ascertained,
    investment_bonds_managed_funds: props.formData.model.investment_bonds_managed_funds,
    investment_bonds_with_profits: props.formData.model.investment_bonds_with_profits,
    investment_bonds_chargeable_gain_not_calculated: props.formData.model.investment_bonds_chargeable_gain_not_calculated,
    investment_bonds_exit_penalty_not_ascertained: props.formData.model.investment_bonds_exit_penalty_not_ascertained,
    investment_bonds_exit_penalty_ascertained_check: props.formData.model.investment_bonds_exit_penalty_ascertained_check,
    investment_bonds_exit_penalty_ascertained: props.formData.model.investment_bonds_exit_penalty_ascertained,
    investment_bonds: props.formData.model.investment_bonds
})

const chargeable_gain = ref(false);

function checkTextInNotes(inputName) {
    switch(inputName) {
        case 'cta_base_costs_available':
            stepForm.cta_base_costs_availability = stepForm.cta_base_costs_available.length > 0
            autosaveLocally()
            break;
        case 'cta_sell_set_amount':
            stepForm.cta_sell_set_amount_check = stepForm.cta_sell_set_amount.length > 0
            autosaveLocally()
            break;
        case 'dta_base_costs_available':
            stepForm.dta_base_costs_availability = stepForm.dta_base_costs_available.length > 0
            autosaveLocally()
            break;
        case 'dta_sell_set_amount':
            stepForm.dta_sell_set_amount_check = stepForm.dta_sell_set_amount.length > 0
            autosaveLocally()
            break;
        case 'isa_transfer_exit_penalty_ascertained':
            stepForm.isa_transfer_exit_penalty_ascertained_check = stepForm.isa_transfer_exit_penalty_ascertained.length > 0
            autosaveLocally()
            break;
        case 'investment_bonds_exit_penalty_not_ascertained':
            chargeable_gain.value = stepForm.investment_bonds_exit_penalty_not_ascertained.length > 0
            autosaveLocally()
            break;
        case 'investment_bonds_exit_penalty_ascertained':
            stepForm.investment_bonds_exit_penalty_ascertained_check = stepForm.investment_bonds_exit_penalty_ascertained.length > 0
            autosaveLocally()
            break;
        default:
            break;
    }
}

function setTextInNotes(inputName) {
    switch(inputName) {
        case 'cta_base_costs_availability':
            if (stepForm.cta_base_costs_availability === false) {
                stepForm.cta_base_costs_available = ''
                autosaveLocally();
            }
            break;
        case 'cta_sell_set_amount_check':
            if (stepForm.cta_sell_set_amount_check === false) {
                stepForm.cta_sell_set_amount = ''
                autosaveLocally();
            }
            break;
        case 'dta_base_costs_availability':
            if (stepForm.dta_base_costs_availability === false) {
                stepForm.dta_base_costs_available = ''
                autosaveLocally();
            }
            break;
        case 'dta_sell_set_amount_check':
            if (stepForm.dta_sell_set_amount_check === false) {
                stepForm.dta_sell_set_amount = ''
                autosaveLocally();
            }
            break;
        case 'isa_transfer_exit_penalty_ascertained_check':
            if (stepForm.isa_transfer_exit_penalty_ascertained_check === false) {
                stepForm.isa_transfer_exit_penalty_ascertained = ''
                autosaveLocally();
            }
            break;
        case 'investment_bonds_exit_penalty_ascertained_check':
            if (stepForm.investment_bonds_exit_penalty_ascertained_check === false) {
                stepForm.investment_bonds_exit_penalty_ascertained = ''
                autosaveLocally();
            }
            break;
        default:
            break;
    }
}

function addInvestmentBond() {
    stepForm.investment_bonds.push({
        provider: null,
        initial_investment: null,
        surrender_value: null,
        withdrawals: null,
        total_gain: null,
        complete_years_held: null,
        top_slice: null
    });
}

function removeInvestmentBond(index) {
    stepForm.investment_bonds.splice(index, 1);
    autosaveLocally();
}

onMounted(()=>{
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.id = props.formData.model.id,
    stepForm.cta_base_costs_availability = props.formData.model.cta_base_costs_availability,
    stepForm.cta_base_costs_available = props.formData.model.cta_base_costs_available,
    stepForm.cta_sell_to_cgt_exemption = props.formData.model.cta_sell_to_cgt_exemption,
    stepForm.cta_sell_all = props.formData.model.cta_sell_all,
    stepForm.cta_sell_set_amount_check = props.formData.model.cta_sell_set_amount_check,
    stepForm.cta_sell_set_amount = props.formData.model.cta_sell_set_amount,
    stepForm.dta_base_costs_availability = props.formData.model.dta_base_costs_availability,
    stepForm.dta_base_costs_available = props.formData.model.dta_base_costs_available,
    stepForm.dta_sell_to_cgt_exemption = props.formData.model.dta_sell_to_cgt_exemption,
    stepForm.dta_sell_all = props.formData.model.dta_sell_all,
    stepForm.dta_sell_set_amount_check = props.formData.model.dta_sell_set_amount_check,
    stepForm.dta_sell_set_amount = props.formData.model.dta_sell_set_amount,
    stepForm.isa_transfer_exit_penalty_not_ascertained = props.formData.model.isa_transfer_exit_penalty_not_ascertained,
    stepForm.isa_transfer_exit_penalty_ascertained_check = props.formData.model.isa_transfer_exit_penalty_ascertained_check,
    stepForm.isa_transfer_exit_penalty_ascertained = props.formData.model.isa_transfer_exit_penalty_ascertained,
    stepForm.investment_bonds_managed_funds = props.formData.model.investment_bonds_managed_funds,
    stepForm.investment_bonds_with_profits = props.formData.model.investment_bonds_with_profits,
    stepForm.investment_bonds_chargeable_gain_not_calculated = props.formData.model.investment_bonds_chargeable_gain_not_calculated,
    stepForm.investment_bonds_exit_penalty_not_ascertained = props.formData.model.investment_bonds_exit_penalty_not_ascertained,
    stepForm.investment_bonds_exit_penalty_ascertained_check = props.formData.model.investment_bonds_exit_penalty_ascertained_check,
    stepForm.investment_bonds_exit_penalty_ascertained = props.formData.model.investment_bonds_exit_penalty_ascertained,
    stepForm.investment_bonds =  props.formData.model.investment_bonds
}
</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="flex flex-row justify-between md:col-span-6 md:pr-2 items-center">
                <label class="font-bold text-white text-lg">Collective Taxable Assets (E.g Unit Trusts)</label>
            </div>
            <div class="border-b-2 pb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4 mb-6">
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-8">
                        <div class="flex h-6 items-center">
                            <input id="cta_base_costs_availability" name="cta_base_costs_availability" type="checkbox"
                                   v-model="stepForm.cta_base_costs_availability" @change="setTextInNotes('cta_base_costs_availability')"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="cta_base_costs_availability" class="text-sm font-medium leading-6 text-aaron-50">Are the base costs available</label>
                        </div>
                    </div>
                    <div v-if="stepForm.cta_base_costs_availability" class="mt-4">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Base Costs: Notes</label>
                        <div class="mt-2">
                            <textarea @change="checkTextInNotes('cta_base_costs_available')" rows="3" name="cta_base_costs_available" id="cta_base_costs_available" v-model="stepForm.cta_base_costs_available" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-4">
                        <div class="flex h-6 items-center">
                            <input id="cta_sell_to_cgt_exemption" name="cta_sell_to_cgt_exemption" type="checkbox"
                                   v-model="stepForm.cta_sell_to_cgt_exemption" @change="autosaveLocally()"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="cta_sell_to_cgt_exemption" class="text-sm font-medium leading-6 text-aaron-50">Sell to CGT Excemption? </label>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-4">
                        <div class="flex h-6 items-center">
                            <input id="cta_sell_all" name="cta_sell_all" type="checkbox"
                                   v-model="stepForm.cta_sell_all" @change="autosaveLocally()"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="cta_sell_all" class="text-sm font-medium leading-6 text-aaron-50">Sell all? </label>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-1">
                        <div class="flex h-6 items-center">
                            <input id="cta_sell_set_amount_check" name="cta_sell_set_amount_check" type="checkbox"
                                   v-model="stepForm.cta_sell_set_amount_check" @change="setTextInNotes('cta_sell_set_amount_check')"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="cta_sell_set_amount_check" class="text-sm font-medium leading-6 text-aaron-50">Sell set amount? </label>
                        </div>
                    </div>
                    <div v-if="stepForm.cta_sell_set_amount_check" class="mt-4">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Sell Set Amount: Notes</label>
                        <div class="mt-2">
                            <textarea @change="checkTextInNotes('cta_sell_set_amount')" rows="3" name="cta_sell_set_amount" id="cta_sell_set_amount" v-model="stepForm.cta_sell_set_amount" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row justify-between md:col-span-6 md:pr-2 items-center">
                <label class="font-bold text-white text-lg">Direct Taxable Assets (E.g Shares)</label>
            </div>
            <div class="border-b-2 pb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4 mb-6">
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-8">
                        <div class="flex h-6 items-center">
                            <input id="dta_base_costs_availability" name="dta_base_costs_availability" type="checkbox"
                                   v-model="stepForm.dta_base_costs_availability" @change="setTextInNotes('dta_base_costs_availability')"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="dta_base_costs_availability" class="text-sm font-medium leading-6 text-aaron-50">Are the base costs available</label>
                        </div>
                    </div>
                    <div v-if="stepForm.dta_base_costs_availability" class="mt-4">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Base Costs: Notes</label>
                        <div class="mt-2">
                            <textarea @change="checkTextInNotes('dta_base_costs_available')" rows="3" name="dta_base_costs_available" id="dta_base_costs_available" v-model="stepForm.dta_base_costs_available" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-4">
                        <div class="flex h-6 items-center">
                            <input id="dta_sell_to_cgt_exemption" name="dta_sell_to_cgt_exemption" type="checkbox"
                                   v-model="stepForm.dta_sell_to_cgt_exemption" @change="autosaveLocally()"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="dta_sell_to_cgt_exemption" class="text-sm font-medium leading-6 text-aaron-50">Sell to CGT Excemption? </label>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-4">
                        <div class="flex h-6 items-center">
                            <input id="dta_sell_all" name="dta_sell_all" type="checkbox"
                                   v-model="stepForm.dta_sell_all" @change="autosaveLocally()"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="dta_sell_all" class="text-sm font-medium leading-6 text-aaron-50">Sell all? </label>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-1">
                        <div class="flex h-6 items-center">
                            <input id="dta_sell_set_amount_check" name="dta_sell_set_amount_check" type="checkbox"
                                   v-model="stepForm.dta_sell_set_amount_check" @change="setTextInNotes('dta_sell_set_amount_check')"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="dta_sell_set_amount_check" class="text-sm font-medium leading-6 text-aaron-50">Sell set amount? </label>
                        </div>
                    </div>
                    <div v-if="stepForm.dta_sell_set_amount_check" class="mt-4">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Sell Set Amount: Notes</label>
                        <div class="mt-2">
                            <textarea @change="checkTextInNotes('dta_sell_set_amount')" rows="3" name="dta_sell_set_amount" id="dta_sell_set_amount" v-model="stepForm.dta_sell_set_amount" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row justify-between md:col-span-6 md:pr-2 items-center">
                <label class="font-bold text-white text-lg">ISA Transfers</label>
            </div>
            <div class="border-b-2 pb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4  mb-6">
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-8">
                        <div class="flex h-6 items-center">
                            <input id="isa_transfer_exit_penalty_not_ascertained" name="is_primary" type="checkbox"
                                   v-model="stepForm.isa_transfer_exit_penalty_not_ascertained" @change="autosaveLocally()"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="isa_transfer_exit_penalty_not_ascertained" class="text-sm font-medium leading-6 text-aaron-50">Exit penalty - not ascertained</label>
                        </div>
                    </div>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-8">
                        <div class="flex h-6 items-center">
                            <input id="isa_transfer_exit_penalty_ascertained_check" name="isa_transfer_exit_penalty_ascertained_check" type="checkbox"
                                   v-model="stepForm.isa_transfer_exit_penalty_ascertained_check" @change="setTextInNotes('isa_transfer_exit_penalty_ascertained_check')"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="isa_transfer_exit_penalty_ascertained_check" class="text-sm font-medium leading-6 text-aaron-50">Exit penalty - ascertained</label>
                        </div>
                    </div>
                </div>
                <div v-if="stepForm.isa_transfer_exit_penalty_ascertained_check" class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Exit penalty - Notes</label>
                        <div class="mt-2">
                            <textarea rows="3" name="cta_base_costs_available" id="cta_base_costs_available"
                              v-model="stepForm.isa_transfer_exit_penalty_ascertained" @change="checkTextInNotes('isa_transfer_exit_penalty_ascertained')"
                              class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row justify-between md:col-span-6 md:pr-2 items-center">
                <label class="font-bold text-white text-lg">Investment Bonds</label>
            </div>
            <div class="md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4  mb-6">
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-8">
                        <div class="flex h-6 items-center">
                            <input id="investment_bonds_managed_funds" name="investment_bonds_managed_funds" type="checkbox"
                                   v-model="stepForm.investment_bonds_managed_funds" @change="autosaveLocally()"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="investment_bonds_managed_funds" class="text-sm font-medium leading-6 text-aaron-50">Managed funds (including unitised WP)</label>
                        </div>
                    </div>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-8">
                        <div class="flex h-6 items-center">
                            <input id="investment_bonds_with_profits" name="investment_bonds_with_profits" type="checkbox"
                                   v-model="stepForm.investment_bonds_with_profits" @change="autosaveLocally()"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="investment_bonds_with_profits" class="text-sm font-medium leading-6 text-aaron-50">With Profits (traditional)</label>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-4">
                        <div class="flex h-6 items-center">
                            <input id="investment_bonds_chargeable_gain_not_calculated" name="investment_bonds_chargeable_gain_not_calculated" type="checkbox"
                                   v-model="stepForm.investment_bonds_chargeable_gain_not_calculated" @change="autosaveLocally()"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="investment_bonds_chargeable_gain_not_calculated" class="text-sm font-medium leading-6 text-aaron-50">Chargeable gain NOT calculated</label>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-4">
                        <div class="flex h-6 items-center">
                            <input id="chargeable_gain" name="chargeable_gain" type="checkbox"
                                   v-model="chargeable_gain" @change="setTextInNotes('chargeable_gain')"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="chargeable_gain" class="text-sm font-medium leading-6 text-aaron-50">Chargeable gain</label>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-4">
                        <div class="flex h-6 items-center">
                            <input id="investment_bonds_exit_penalty_not_ascertained" name="investment_bonds_exit_penalty_not_ascertained" type="checkbox"
                                   v-model="stepForm.investment_bonds_exit_penalty_not_ascertained" @change="autosaveLocally()"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="investment_bonds_exit_penalty_not_ascertained" class="text-sm font-medium leading-6 text-aaron-50">Exit penalty - not ascertained</label>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-4">
                        <div class="flex h-6 items-center">
                            <input id="investment_bonds_exit_penalty_ascertained_check" name="investment_bonds_exit_penalty_ascertained_check" type="checkbox"
                                   v-model="stepForm.investment_bonds_exit_penalty_ascertained_check" @change="setTextInNotes('investment_bonds_exit_penalty_ascertained_check')"
                                   class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                        </div>
                        <div class="ml-3">
                            <label for="investment_bonds_exit_penalty_ascertained_check" class="text-sm font-medium leading-6 text-aaron-50">Exit penalty - ascertained</label>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2">
                    <div v-if="stepForm.investment_bonds_exit_penalty_ascertained_check">
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Exit penalty - Notes</label>
                        <div class="mt-2">
                            <textarea rows="3" name="investment_bonds_exit_penalty_ascertained" id="investment_bonds_exit_penalty_ascertained" v-model="stepForm.investment_bonds_exit_penalty_ascertained" @change="checkTextInNotes('investment_bonds_exit_penalty_ascertained')"
                              class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div v-for="(investment_bond, index) in stepForm.investment_bonds" class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="md:col-span-6 flex flex-row justify-between">
                    <label class="font-bold">Investment Bonds {{ index + 1 }}</label>
                    <button type="button" @click="removeInvestmentBond(index)"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-red-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <XCircleIcon class="w-4 h-4" />Remove Investment Bond
                    </button>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="provider" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Provider </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="text" name="provider" id="provider"
                               v-model="investment_bond.provider" @change="autosaveLocally()"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Provider" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.provider">{{ stepForm.errors.provider }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="gross_amount" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Initial investment </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="initial_investment" id="initial_investment"
                               :value="investment_bond.initial_investment"
                               @change="formatAmount($event, index, 'initial_investment')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.initial_investment">{{ stepForm.errors.initial_investment }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="surrender_value" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Surrender Value </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="surrender_value" id="surrender_value"
                               :value="investment_bond.surrender_value"
                               @change="formatAmount($event, index, 'surrender_value')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.surrender_value">{{ stepForm.errors.surrender_value }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="withdrawals" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Withdrawals </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="withdrawals" id="withdrawals"
                               :value="investment_bond.withdrawals"
                               @change="formatAmount($event, index, 'withdrawals')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.withdrawals">{{ stepForm.errors.withdrawals }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="total_gain" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Total Gain </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="total_gain" id="total_gain"
                               :value="investment_bond.total_gain"
                               @change="formatAmount($event, index, 'total_gain')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.total_gain">{{ stepForm.errors.total_gain }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="top_slice" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Top Slice </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="top_slice" id="top_slice"
                               :value="investment_bond.top_slice"
                               @change="formatAmount($event, index, 'top_slice')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.top_slice">{{ stepForm.errors.top_slice }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="complete_years_held" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> No. of complete years held </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="number" name="complete_years_held" id="complete_years_held"
                               v-model="investment_bond.complete_years_held"
                               @change="autosaveLocally()"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.complete_years_held">{{ stepForm.errors.complete_years_held }}</p>
                </div>
            </div>
            <button type="button" @click="addInvestmentBond"
                    class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <PlusCircleIcon class="w-6 h-6" />Add Investment Bond
            </button>
        </div>
    </dynamic-form-wrapper>
</template>
