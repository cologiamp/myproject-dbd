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
    additional_contributions: props.formData.model.additional_contributions,
    in_specie_transfers: props.formData.model.in_specie_transfers,
    if_experience_self_select: props.formData.model.if_experience_self_select,
    if_experience_lifestyle: props.formData.model.if_experience_lifestyle,
    if_experience_advisory: props.formData.model.if_experience_advisory,
    if_experience_discretionary: props.formData.model.if_experience_discretionary,
    is_explained: props.formData.model.is_explained,
    preferred_option: props.formData.model.preferred_option,
    preferred_explanation: props.formData.model.preferred_explanation,
    wide_range_of_assets: props.formData.model.wide_range_of_assets,
    include_exclude_specifics: props.formData.model.include_exclude_specifics,
    require_flexibility: props.formData.model.require_flexibility,
    retirement_vs_legacy: props.formData.model.retirement_vs_legacy,
    retirement_vs_legacy_specifics: props.formData.model.retirement_vs_legacy_specifics,
    dependents_suffer: props.formData.model.dependents_suffer,
    iht_concerns: props.formData.model.iht_concerns,
})


</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="flex flex-col justify-center w-full lg:w-6/12 mx-auto">
            <div class="mb-4 mt-4 w-full">
                <h1 class="text-2xl pb-4">Future Contributions</h1>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Do you wish any future pension arrangements to be capable of receiving additional regular contributions?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.additional_contributions"
                               type="radio" id="true" :value="true"
                               :checked="stepForm.additional_contributions == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.additional_contributions" type="radio" id="false" :value="false"
                               :checked="stepForm.additional_contributions == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.additional_contributions">{{
                            stepForm.errors.additional_contributions }}</p>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Do you wish any future pension arrangements to be capable of receiving in specie transfers arising from an Approved All Employee Share Scheme?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.in_specie_transfers" type="radio" id="true" :value="true"
                               :checked="stepForm.in_specie_transfers == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.in_specie_transfers" type="radio" id="false" :value="false"
                               :checked="stepForm.in_specie_transfers == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.in_specie_transfers">{{
                            stepForm.errors.in_specie_transfers }}</p>
                </div>
            </div>

            <div class="mb-4 mt-4 w-full">
                <h1 class="text-2xl pb-4">Investment flexibility</h1>


                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label for="if_experience_self_select" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Self-selecting investments</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.if_experience_self_select" id="unit" name="if_experience_self_select" class="block rounded-md w-6/12 border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="if_experience_self_select" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(if_experience_self_select, id) in formData.enums.if_experience_self_select">{{ if_experience_self_select }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.if_experience_self_select">{{ stepForm.errors.if_experience_self_select }}</p>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label for="if_experience_lifestyle" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Lifestyle investment strategies</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.if_experience_lifestyle" id="unit" name="if_experience_lifestyle"  class="block rounded-md w-6/12 border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="if_experience_lifestyle" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(if_experience_lifestyle, id) in formData.enums.if_experience_lifestyle">{{ if_experience_lifestyle }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.if_experience_lifestyle">{{ stepForm.errors.if_experience_lifestyle }}</p>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label for="if_experience_advisory" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Advisory investment services</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.if_experience_advisory" id="unit" name="if_experience_advisory"  class="block rounded-md w-6/12 border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="if_experience_advisory" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(if_experience_advisory, id) in formData.enums.if_experience_advisory">{{ if_experience_advisory }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.if_experience_advisory">{{ stepForm.errors.if_experience_advisory }}</p>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label for="if_experience_discretionary" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Discretionary managed investment services</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.if_experience_discretionary" id="unit" name="if_experience_discretionary"  class="block rounded-md w-6/12 border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="if_experience_discretionary" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(if_experience_discretionary, id) in formData.enums.if_experience_discretionary">{{ if_experience_discretionary }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.if_experience_discretionary">{{ stepForm.errors.if_experience_discretionary }}</p>
                </div>


                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Irrespective of your knowledge and experience in respect of the above approaches to investment, please confirm your Adviser has explained and you now understand the differences
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.is_explained" type="radio" id="true" :value="true"
                               :checked="stepForm.is_explained == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.is_explained" type="radio" id="false" :value="false"
                               :checked="stepForm.is_explained == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.if_experience_self_select">{{
                            stepForm.errors.if_experience_self_select }}</p>
                </div>


                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label for="preferred_option" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Having understood the investment options available within various pension arrangements, what is your preference? My preference is...</label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.preferred_option" id="unit" name="preferred_option"  class="block rounded-md w-6/12 border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="preferred_option" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(preferred_option, id) in formData.enums.preferred_option">{{ preferred_option }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.preferred_option">{{ stepForm.errors.preferred_option }}</p>
                </div>


                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Please briefly explain why this would be your preferred option </label>
                        <div class="mt-2">
                            <textarea @change="autosaveT(stepForm, props.formData.submit_url)" v-model="stepForm.preferred_explanation" rows="3" name="preferred_explanation" id="preferred_explanation" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>


                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Do you wish a recommended pension scheme to have a wide range of financial assets available?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.wide_range_of_assets" type="radio" id="true" :value="true"
                               :checked="stepForm.wide_range_of_assets == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.wide_range_of_assets" type="radio" id="false" :value="false"
                               :checked="stepForm.wide_range_of_assets == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.wide_range_of_assets">{{
                            stepForm.errors.wide_range_of_assets }}</p>
                </div>


                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                            Do you wish to include or exclude any particular types of investments, individual holdings, sectors or geographical areas?
                        </label>
                        <div class="mt-2">
                            <textarea @change="autosaveT(stepForm, props.formData.submit_url)" v-model="stepForm.include_exclude_specifics" rows="3" name="include_exclude_specifics" id="include_exclude_specifics" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>






            </div>

            <div class="mb-4 mt-4 w-full">
                <h1 class="text-2xl pb-4">Need for benefits </h1>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Do you require flexibility as to when and how you may take your retirement benefits from your pension funds including the availability of both annuities and flexi-access drawdown?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.require_flexibility" type="radio" id="true" :value="true"
                               :checked="stepForm.require_flexibility == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.require_flexibility" type="radio" id="false" :value="false"
                               :checked="stepForm.require_flexibility == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.require_flexibility">{{
                            stepForm.errors.require_flexibility }}</p>
                </div>
            </div>


            <div class="mb-4 mt-4 w-full">
                <h1 class="text-2xl pb-4">Death benefits</h1>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label for="retirement_vs_legacy" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        The primary purpose of a pension fund is to provide you with a source of income throughout retirement. However, if other sources of income are available, or the fund is surplus to your needs, it can potentially be passed on to a beneficiary of your choice.
                    </label>
                    <select @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.retirement_vs_legacy" id="unit" name="retirement_vs_legacy"  class="block rounded-md w-6/12 border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="retirement_vs_legacy" :value="null">-</option>
                        <option :id="id" :value="id" v-for="(retirement_vs_legacy, id) in formData.enums.retirement_vs_legacy">{{ retirement_vs_legacy }}</option>
                    </select>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.retirement_vs_legacy">{{ stepForm.errors.retirement_vs_legacy }}</p>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Please explain your preference</label>
                        <div class="mt-2">
                            <textarea @change="autosaveT(stepForm, props.formData.submit_url)" v-model="stepForm.retirement_vs_legacy_specifics" rows="3" name="retirement_vs_legacy_specifics" id="retirement_vs_legacy_specifics" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2"> Who might suffer financially when you die?</label>
                        <div class="mt-2">
                            <textarea @change="autosaveT(stepForm, props.formData.submit_url)" v-model="stepForm.dependents_suffer" rows="3" name="dependents_suffer" id="preferred_explanation" class="block w-full rounded-md border-0 py-1.5 text-aaron-50 bg-aaron-950 shadow-sm ring-1 ring-inset ring-aaron-500 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>


                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        If future inheritance tax is a concern, is it important any recommended pension scheme has the facility for residual funds to potentially be passed down the generations remaining within a pension wrapper?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.iht_concerns" type="radio" id="true" :value="true"
                               :checked="stepForm.iht_concerns == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="stepForm.iht_concerns" type="radio" id="false" :value="false"
                               :checked="stepForm.iht_concerns == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.iht_concerns">{{
                            stepForm.errors.iht_concerns }}</p>
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
