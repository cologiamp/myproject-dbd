<script setup>
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";
import {autoS, autosaveT} from "@/autosave.js";
import {useForm, usePage} from "@inertiajs/vue3";
import {XCircleIcon} from "@heroicons/vue/24/solid/index.js";
import {changeToCurrency} from "@/currency.js";
import {computed, onBeforeMount, ref} from "vue";

const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
            },
            model: {
                useExpenditure: true,
                total: null,
                expenditures: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
});

const stepForm = useForm({
    useExpenditure: props.formData.model.useExpenditure,
    total: props.formData.model.total,
    expenditures: props.formData.model.expenditures,
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.useExpenditure = props.formData.model.useExpenditure;
    stepForm.total = props.formData.model.total;
    stepForm.expenditures = props.formData.model.expenditures;
    // formatAmountOnload()
    calculateTotals();
}

function formatTotal()
{
    stepForm.total = changeToCurrency(stepForm.total);
    saveTotal();
}
function saveTotal()
{
    autosaveLocally();
}

let calculatedTotals = ref([]);
let calculatedTotal = computed(() => {
    return calculatedTotals.value.reduce((partialSum, a) => partialSum + a, 0);
})
function calculateTotals(){
    if(stepForm.expenditures && stepForm.expenditures.length > 0 && props && props.formData)
    {
        calculatedTotals.value = stepForm.expenditures.map(function (item){
            if(item.gross_amount !== null)
            {
                return item.amount;
            }
            return 0;
        })
    }
    else{
        calculatedTotals.value = [];
    }
}

onBeforeMount(() => {
    calculateTotals();
})

</script>

<template>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <div class="rounded-md bg-red-50 p-4 mb-2" v-if="usePage().props.errors && Object.keys(usePage().props.errors).length > 0">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800" v-if="Object.keys(usePage().props.errors).length === 1">There was an error identified</h3>
                        <h3 class="text-sm font-medium text-red-800" v-else>There were {{Object.keys(usePage().props.errors).length}} errors identified</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul role="list" class="list-disc space-y-1 pl-5">
                                <li v-for="(key, value) in usePage().props.errors">
                                    {{key}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 grid grid-cols-6 rounded-md bg-aaron-950 pt-2 mb-4 p-4">
                <h4 class="col-span-6 text-xl font-bold pt-2"> Expenditure Total </h4>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3" v-if="stepForm.useExpenditure === false">
                    <label for="total" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Total: </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input v-model="stepForm.total" @change="formatTotal"
                            class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.total">{{ stepForm.errors.total }}</p>
                </div>
                <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3" v-else>
                    <label for="total" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Total: </label>
                    <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                        <input disabled
                            :value="changeToCurrency(calculatedTotal.toString())"
                            class="disabled block ring-1 disabled:bg-gray-400 ring-inset flex-1 border-0 rounded-md py-1.5 pl-2
                                placeholder:text-aaron-950 focus:ring-0 sm:text-sm sm:leading-6 shadow-none" placeholder="£" />
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.total">{{ stepForm.errors.total }}</p>
                </div>
                <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                    <div class="flex items-center pt-11">
                        <div class="flex h-6 items-center">
                            <input :disabled="stepForm.expenditures.length > 0" id="use_expenditure" name="use_expenditure" type="checkbox"
                               v-model="stepForm.useExpenditure"
                               class="h-6 w-6 rounded border-gray-300 text-aaron-400 focus:ring-aaron-400 disabled:bg-gray-500 disabled:cursor-not-allowed disabled:hover:bg-gray-500" />
                        </div>
                        <div class="ml-3">
                            <label for="is_primary" class="text-sm font-medium leading-6 text-aaron-50">Collect Expenditure Details</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </dynamic-form-wrapper>
</template>
