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

            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});




const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    known_income_required: props.formData.model.known_income_required,
    prefer_flexibility: props.formData.model.prefer_flexibility,
    what_age_annuity: props.formData.model.what_age_annuity,
    proportion_of_total_funds: props.formData.model.proportion_of_total_funds,
    spouse_income_proportion: props.formData.model.spouse_income_proportion,
    spouse_lump_sum_death: props.formData.model.spouse_lump_sum_death,
    maximise_lifetime: props.formData.model.maximise_lifetime,
    no_spouse: props.formData.model.no_spouse,
    spouse_details: props.formData.model.spouse_details,
    tax_free_lump_sum_preference: props.formData.model.tax_free_lump_sum_preference,
    tax_free_lump_sum_value: props.formData.model.tax_free_lump_sum_value,
    lump_sum_death_benefits: props.formData.model.lump_sum_death_benefits,
})


</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="mb-12">
                <h1>Income options</h1>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Do you require a secure and known income that cannot go down in amount?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.known_income_required"
                               type="radio" id="true" :value="true"
                               :checked="stepForm.known_income_required == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.known_income_required" type="radio" id="false" :value="false"
                               :checked="stepForm.known_income_required == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.known_income_required">{{
                            stepForm.errors.known_income_required }}</p>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Would you prefer flexibility in the way your savings are used to provide you with income?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.prefer_flexibility" type="radio" id="true" :value="true"
                               :checked="stepForm.prefer_flexibility == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.prefer_flexibility" type="radio" id="false" :value="false"
                               :checked="stepForm.prefer_flexibility == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.prefer_flexibility">{{
                            stepForm.errors.prefer_flexibility }}</p>
                </div>

                <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="what_age_annuity" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> If annuity purchase is not your preferred option at this time, at what age might you consider purchasing an annuity?</label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.what_age_annuity" type="number" name="what_age_annuity" id="what_age_annuity" min="0" max="100" step="1" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="1" />
                        </div>
                    </div>
                </div>

            </div>

            <div class="mb-12">
                <h1>Sources of pension income</h1>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="proportion_of_total_funds" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        What proportion of your overall retirement income do the funds under discussion represent?
                    </label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.proportion_of_total_funds" id="unit" name="proportion_of_total_funds"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="proportion_of_total_funds" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(proportion_of_total_funds, id) in formData.enums.proportion_of_total_funds">{{ proportion_of_total_funds }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.proportion_of_total_funds">{{ stepForm.errors.proportion_of_total_funds }}</p>
                </div>

            </div>

            <div class="mb-12">
                <h1>Spouse/Partner's Pensions</h1>


                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Is it important that on your death your pension funds provide an income for your spouse/civil partner?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.spouse_income_proportion" type="radio" id="true" :value="true"
                               :checked="stepForm.spouse_income_proportion == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.spouse_income_proportion" type="radio" id="false" :value="false"
                               :checked="stepForm.spouse_income_proportion == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.spouse_income_proportion">{{
                            stepForm.errors.spouse_income_proportion }}</p>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="spouse_lump_sum_death" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> If so, at what level?</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.spouse_lump_sum_death" id="unit" name="spouse_lump_sum_death"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="spouse_lump_sum_death" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(spouse_lump_sum_death, id) in formData.enums.spouse_lump_sum_death">{{ spouse_lump_sum_death }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.spouse_lump_sum_death">{{ stepForm.errors.spouse_lump_sum_death }}</p>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Do you wish to maximise the benefits payable to you during your lifetime as your spouse/civil partner have sufficient income from elsewhere?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.maximise_lifetime" type="radio" id="true" :value="true"
                               :checked="stepForm.maximise_lifetime == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.maximise_lifetime" type="radio" id="false" :value="false"
                               :checked="stepForm.maximise_lifetime == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.maximise_lifetime">{{
                            stepForm.errors.maximise_lifetime }}</p>
                </div>


                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Do you have no spouse/civil partner and expect to remain single?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.no_spouse" type="radio" id="true" :value="true"
                               :checked="stepForm.no_spouse == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.no_spouse" type="radio" id="false" :value="false"
                               :checked="stepForm.no_spouse == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.no_spouse">{{
                            stepForm.errors.no_spouse }}</p>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Notes </label>
                        <div class="mt-2">
                            <textarea @change="autosaveT(stepForm, props.formData.submit_url)" v-model="stepForm.spouse_details" rows="3" name="spouse_details" id="spouse_details" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mb-12">
                <h1>Tax free cash lump sum </h1>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="if_experience_advisory" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Most pension schemes allow the option for you to exchange part of your taxable annual pension for a tax free lump sum and a reduced annual pension. </label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.tax_free_lump_sum_preference" id="unit" name="tax_free_lump_sum_preference"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="tax_free_lump_sum_preference" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(tax_free_lump_sum_preference, id) in formData.enums.tax_free_lump_sum_preference">{{ tax_free_lump_sum_preference }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.tax_free_lump_sum_preference">{{ stepForm.errors.tax_free_lump_sum_preference }}</p>
                </div>

                <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="tax_free_lump_sum_value" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> If so, please state how much (£)</label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.tax_free_lump_sum_value" type="currency" name="tax_free_lump_sum_value" id="tax_free_lump_sum_value" min="0" max="100" step="1" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="1" />
                        </div>
                    </div>
                </div>


            </div>

            <div class="mb-12">
                <h1>Lump sum death benefits </h1>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label for="if_experience_advisory" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Is providing lump sum death benefits for your spouse, civil partner and / or dependants important to you? </label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.lump_sum_death_benefits" id="unit" name="lump_sum_death_benefits"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="lump_sum_death_benefits" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(lump_sum_death_benefits, id) in formData.enums.lump_sum_death_benefits">{{ lump_sum_death_benefits }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.lump_sum_death_benefits">{{ stepForm.errors.lump_sum_death_benefits }}</p>
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
