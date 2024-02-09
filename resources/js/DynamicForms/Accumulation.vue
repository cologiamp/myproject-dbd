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
                //Ignacio: write me
                intended_retirement: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});




const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    intended_retirement: props.formData.model.intended_retirement,
    intended_benefits_drawn: props.formData.model.intended_benefits_drawn,
    //Ignacio: write me
    income_option: props.formData.model.income_option,
    notes: props.formData.model.notes,
    lifetime_allowance_protection: props.formData.model.lifetime_allowance_protection,
})


</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="mb-12">
                <h1 class="ignacio-write-me">Accumulation</h1>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Do you wish any future pension arrangements to be capable of receiving additional regular contributions?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="if_experience_self_select" type="radio" id="true" :value="true"
                               :checked="if_experience_self_select == true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveT(stepForm,props.formData.submit_url)"
                               v-model="if_experience_self_select" type="radio" id="false" :value="false"
                               :checked="if_experience_self_select == false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.if_experience_self_select">{{
                            stepForm.errors.if_experience_self_select }}</p>
                </div>

                <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="first_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> When do you expect to draw benefits?</label>
                        <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveT(stepForm,props.formData.submit_url)" v-model="stepForm.intended_benefits_drawn" type="number" name="intended_benefits_drawn" id="intended_benefits_drawn" min="0" max="100" step="1" class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="1" />
                        </div>
                    </div>
                </div>


            </div>
            <!--                IGNACIO: WRITE ME-->
            <div class="mb-12">
                <h1 class="ignacio-write-me">HMRC Lifetime Allowance protection</h1>

                <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <div class="flex items-center pt-6">
                            <div class="flex h-6 items-center">
                                <input id="lifetime_allowance_protection" name="lifetime_allowance_protection" type="checkbox" @change="changeCheck(index)" class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                            </div>
                            <div class="ml-3">
                                <label for="lifetime_allowance_protection" class="text-sm font-medium leading-6 text-aaron-50">Option 1 Lifetime Allowance Protection</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-2 md:grid md:grid-cols-6 md:items-start md:gap-y-8 md:gap-x-4 mb-6">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <div class="flex items-center pt-6">
                            <div class="flex h-6 items-center">
                                <input id="is_primary" name="is_primary" type="checkbox" @change="changeCheck(index)" class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400" />
                            </div>
                            <div class="ml-3">
                                <label for="is_primary" class="text-sm font-medium leading-6 text-aaron-50">Option 2 Lifetime Allowance Protection</label>
                            </div>
                        </div>
                    </div>
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
