<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import {calculateCapacityForLoss} from "@/calculateRiskAssesment.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";

import {onMounted, ref, watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";
import {ArrowRightIcon} from "@heroicons/vue/24/solid/index.js";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})

const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
            },
            model: {
                capacity_data: {
                    investment_length: null,
                    standard_of_living: null,
                    emergency_funds: null
                },
                risk_outcome_id: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

const stepForm = useForm({
    id: props.formData.model.capacity_data.id,
    type: props.formData.model.capacity_data.type,
    investment_length: props.formData.model.capacity_data.investment_length,
    standard_of_living: props.formData.model.capacity_data.standard_of_living,
    emergency_funds: props.formData.model.capacity_data.emergency_funds,
    capacity_for_loss_investment: null
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.id = props.formData.model.capacity_data.id;
    stepForm.type = props.formData.model.capacity_data.type;
    stepForm.investment_length = props.formData.model.capacity_data.investment_length;
    stepForm.standard_of_living = props.formData.model.capacity_data.standard_of_living;
    stepForm.emergency_funds = props.formData.model.capacity_data.emergency_funds;
    stepForm.capacity_for_loss_investment = null;
}

function submitAssessment() {
    let total = stepForm.investment_length + stepForm.standard_of_living + stepForm.emergency_funds;

    calculateCapacityForLoss(total, stepForm.type, props.formData.model.risk_outcome_id);
    // autosaveLocally();
}

onMounted(()=>{
})

</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <form-errors :errors="usePage().props.errors"/>
        <div class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
            <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                <label v-if="stepForm.type === 0" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                    How long do you feel you can make this investment?
                </label>
                <label v-if="stepForm.type === 1" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                    When do you intend to take this pension?
                </label>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.investment_length"
                           :checked="stepForm.investment_length === 1"
                           type="radio" id="less" :value="1"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label v-if="stepForm.type === 0" for="less" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Less than 5 years
                    </label>
                    <label v-if="stepForm.type === 1" for="less" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Short-term (less than 5 years)
                    </label>
                </div>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.investment_length"
                           :checked="stepForm.investment_length === 4"
                           type="radio" id="between" :value="4"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label v-if="stepForm.type === 0" for="between" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Between 5 and 10 years
                    </label>
                    <label v-if="stepForm.type === 1" for="between" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Medium-term (5 to 10 years)
                    </label>
                </div>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.investment_length"
                           :checked="stepForm.investment_length === 8"
                           type="radio" id="over" :value="8"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label v-if="stepForm.type === 0" for="over" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Over 10 years
                    </label>
                    <label v-if="stepForm.type === 1" for="over" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Long-term (10+ years)
                    </label>
                </div>
                <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.investment_length">{{ stepForm.errors.investment_length }}</p>
            </div>
            <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                <label v-if="stepForm.type === 0" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                    How much would your standard of living be affected if this investment were to perform below your expectations?
                </label>
                <label v-if="stepForm.type === 1" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                    How much of this pension could you stand to lose without having a significant impact on your future standard of living?
                </label>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.standard_of_living"
                           :checked="stepForm.standard_of_living === 1"
                           type="radio" id="significant-impact" :value="1"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label v-if="stepForm.type === 0"  for="significant-impact" class="ml-2 block text-sm font-medium leading-6 text-white">
                        It would have a significant impact. I cannot afford for this investment to not meet my objectives
                    </label>
                    <label v-if="stepForm.type === 1"  for="significant-impact" class="ml-2 block text-sm font-medium leading-6 text-white">
                        None or very limited losses
                    </label>
                </div>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.standard_of_living"
                           :checked="stepForm.standard_of_living === 6"
                           type="radio" id="standard-of-living" :value="6"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label v-if="stepForm.type === 0"  for="standard-of-living" class="ml-2 block text-sm font-medium leading-6 text-white">
                        It would cause me to reassess my standard of living and make some cut-backs
                    </label>
                    <label v-if="stepForm.type === 1"  for="standard-of-living" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Small / medium losses could be tolerated
                    </label>
                </div>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.standard_of_living"
                           :checked="stepForm.standard_of_living === 10"
                           type="radio" id="alternative-investments" :value="10"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label v-if="stepForm.type === 0"  for="alternative-investments" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Not much as I have alternative investments / savings to maintain my lifestyle
                    </label>
                    <label v-if="stepForm.type === 1" for="alternative-investments" class="ml-2 block text-sm font-medium leading-6 text-white">
                        Large losses would have a low impact on future lifestyle
                    </label>                </div>
                <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.standard_of_living">{{ stepForm.errors.standard_of_living }}</p>
            </div>
            <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                <label v-if="stepForm.type === 0" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                    If you needed emergency funds, would you consider taking them from this investment?
                </label>
                <label v-if="stepForm.type === 1" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                    If you were 55 or over and needed sudden access to a lump sum or income, how likely is it that you would need to take this pension?
                </label>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.emergency_funds"
                           :checked="stepForm.emergency_funds === 1"
                           type="radio" id="no-alternatives" :value="1"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label v-if="stepForm.type === 0" for="no-alternatives" class="ml-2 block text-sm font-medium leading-6 text-white">
                        I have no alternatives. I would almost certainly need access to this investment
                    </label>
                    <label v-if="stepForm.type === 1" for="no-alternatives" class="ml-2 block text-sm font-medium leading-6 text-white">
                        It is very likely I would take these benefits as I do not have any savings or investments to draw on and I realised that this may have a detrimental effect on my retirement income.
                    </label>
                </div>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.emergency_funds"
                           :checked="stepForm.emergency_funds === 6"
                           type="radio" id="take-funds" :value="6"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label v-if="stepForm.type === 0" for="take-funds" class="ml-2 block text-sm font-medium leading-6 text-white">
                        I would consider taking funds from this investment if necessary
                    </label>
                    <label v-if="stepForm.type === 1" for="take-funds" class="ml-2 block text-sm font-medium leading-6 text-white">
                        I would take further advice on whether it was in my best interests to take benefits
                    </label>
                </div>
                <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                    <input @change="autosaveLocally()" v-model="stepForm.emergency_funds"
                           :checked="stepForm.emergency_funds === 10"
                           type="radio" id="other-savings" :value="10"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                    <label v-if="stepForm.type === 0" for="other-savings" class="ml-2 block text-sm font-medium leading-6 text-white">
                        No, I have other savings that I can use for emergencies
                    </label>
                    <label v-if="stepForm.type === 1" for="other-savings" class="ml-2 block text-sm font-medium leading-6 text-white">
                        I have other savings and investments which I can use for most needs
                    </label>
                </div>
                <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.emergency_funds">{{ stepForm.errors.emergency_funds }}</p>
            </div>
            <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2 flex justify-center">
                <button type="button" @click="submitAssessment()"
                    class="inline-flex items-center gap-x-1.5 rounded-md bg-sage px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#00b49d] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Submit Assessment
                </button>
            </div>
        </div>
    </dynamic-form-wrapper>
</template>
