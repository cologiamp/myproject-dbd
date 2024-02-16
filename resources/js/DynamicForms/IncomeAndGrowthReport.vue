<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {useForm} from "laravel-precognition-vue-inertia";

import {onMounted, watch} from "vue";
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
                report_for: [],
                report_type: [],
                regular_cash_duration: []
            },
            model: {
                report_for: null,
                report_type: null,
                isa_allowance_used: null,
                cgt_allowance_used: null,
                net_income_required: null,
                regular_cash_required: null,
                regular_cash_duration: null
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

    autosaveT(stepForm,props.formData.submit_url);
}

onMounted(()=>{
})

const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    id: props.formData.model.id,
    report_for: props.formData.model.report_for,
    report_type: props.formData.model.report_type,
    isa_allowance_used: props.formData.model.isa_allowance_used,
    cgt_allowance_used: props.formData.model.cgt_allowance_used,
    net_income_required: props.formData.model.net_income_required,
    regular_cash_required: props.formData.model.regular_cash_required,
    regular_cash_duration: props.formData.model.regular_cash_duration
})

</script>

<template>

    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="report_for" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Report For</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.report_for" id="report_for" name="report_for"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="report_for" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(owner, id) in formData.enums.report_for">{{ owner }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.report_for">{{ stepForm.errors.report_for }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="risk_profile" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Report Type</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.report_type" id="report_type" name="report_type"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="report_for" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(type, id) in formData.enums.report_type">{{ type }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.report_type">{{ stepForm.errors.report_type }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="isa_allowance_used" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> How much ISA Allowance has been used this tax year? </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="isa_allowance_used" id="isa_allowance_used"
                               :value="stepForm.isa_allowance_used"
                               @change="formatAmount($event, 'isa_allowance_used')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.isa_allowance_used">{{ stepForm.errors.isa_allowance_used }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="cgt_allowance_used" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> How much CGT Allowance has been used this tax year? </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="cgt_allowance_used" id="cgt_allowance_used"
                               :value="stepForm.cgt_allowance_used"
                               @change="formatAmount($event, 'cgt_allowance_used')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.cgt_allowance_used">{{ stepForm.errors.cgt_allowance_used }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="net_income_required" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Net Income Required </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="net_income_required" id="net_income_required"
                               :value="stepForm.net_income_required"
                               @change="formatAmount($event, 'net_income_required')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.net_income_required">{{ stepForm.errors.net_income_required }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                    <label for="regular_cash_required" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Regular Cash Withdrawal </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input type="currency" name="regular_cash_required" id="regular_cash_required"
                               :value="stepForm.regular_cash_required"
                               @change="formatAmount($event, 'regular_cash_required')"
                               class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.regular_cash_required">{{ stepForm.errors.regular_cash_required }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="regular_cash_duration" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Regular Cash Withdrawal Interval</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.regular_cash_duration" id="regular_cash_duration" name="regular_cash_duration"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="regular_cash_duration" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(duration, id) in formData.enums.regular_cash_duration">{{ duration }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.regular_cash_duration">{{ stepForm.errors.regular_cash_duration }}</p>
                </div>
            </div>
        </div>
    </dynamic-form-wrapper>
</template>
