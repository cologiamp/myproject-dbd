<script setup>
import {autoS, autosaveT} from "@/autosave.js";
import {calculateRiskProfile} from "@/calculateRiskAssesment.js";
import DynamicFormWrapper from "@/Components/DynamicFormWrapper.vue";

import {onMounted, watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import FormErrors from "@/Components/FormErrors.vue";
import ConcernRatingTable from "@/Components/ConcernRatingTable.vue";
import {InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})

const props = defineProps({
    formData: {
        type: Object,
        default: {
            enums: {
                risk_assessment_volatility: []
            },
            model: {
                comfort_fluctuate_market: null,
                day_to_day_volatility: null,
                short_term_volatility: null,
                medium_term_volatility: null,
                volatility_behaviour: null,
                long_term_volatility: null,
                time_in_market: null,
                risk_outcome_id: null,
                capacity_inv_score: null,
                capacity_pension_score: null
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    errors: Object,
    sidebarItemsLength: Number
});

const stepForm = useForm({
    id: props.formData.model.id,
    comfort_fluctuate_market: props.formData.model.comfort_fluctuate_market,
    day_to_day_volatility: props.formData.model.day_to_day_volatility,
    short_term_volatility: JSON.parse(props.formData.model.short_term_volatility),
    medium_term_volatility: props.formData.model.medium_term_volatility,
    volatility_behaviour: props.formData.model.volatility_behaviour,
    long_term_volatility: props.formData.model.long_term_volatility,
    time_in_market: props.formData.model.time_in_market,
    capacity_inv_score: props.formData.model.capacity_inv_score,
    capacity_pension_score: props.formData.model.capacity_pension_score
})

async function autosaveLocally(){
    props.formData.model = await autosaveT(stepForm,props.formData.submit_url)
    stepForm.id = props.formData.model.id;
    stepForm.comfort_fluctuate_market = props.formData.model.comfort_fluctuate_market;
    stepForm.day_to_day_volatility = props.formData.model.day_to_day_volatility;
    stepForm.short_term_volatility = JSON.parse(props.formData.model.short_term_volatility);
    stepForm.medium_term_volatility = props.formData.model.medium_term_volatility;
    stepForm.volatility_behaviour = props.formData.model.volatility_behaviour;
    stepForm.long_term_volatility = props.formData.model.long_term_volatility;
    stepForm.time_in_market = props.formData.model.time_in_market;
    stepForm.capacity_inv_score = props.formData.model.capacity_inv_score;
    stepForm.capacity_pension_score = props.formData.model.capacity_pension_score;
}

function setRating(data) {
    JSON.stringify(data)
    autosaveLocally()
}

function submitAssessment() {
    calculateRiskProfile(stepForm, props.formData.model.risk_outcome_id, props.sidebarItemsLength)
}

function isSubmitDisabled() {
    if (stepForm.comfort_fluctuate_market != null && stepForm.day_to_day_volatility != null &&
        stepForm.medium_term_volatility != null && stepForm.volatility_behaviour != null &&
        stepForm.long_term_volatility != null && stepForm.time_in_market != null && isRatingSet(stepForm.short_term_volatility) === true &&
        (stepForm.capacity_inv_score != null || stepForm.capacity_pension_score != null)) {
        return false;
    }
    return true;
}

function isRatingSet(rangeData) {
    let flag = true;
    Object.entries(rangeData).forEach(data => {
        const [key, item] = data;
        if (item.value === null) {
            flag = false;
        }
    });

    return flag;
}

onMounted(()=>{
})

</script>

<template>
    <form-errors :errors="usePage().props.errors"/>
    <dynamic-form-wrapper :saving="autoS">
        <div class="form-row flex-1">
            <form-errors :errors="usePage().props.errors"/>
            <div class="grid gap-2 mb-6 md:grid md:grid-cols-6 md:items-start md:gap-y-4 md:gap-x-4 border-b-2 border-aaron-500 pb-12 last-of-type:border-b-0 last-of-type:pb-0">
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Markets fluctuate, which means that the value of investments can fall as well as rise. Are you comfortable with an investment that may fluctuate in value?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.comfort_fluctuate_market"
                               :checked="stepForm.comfort_fluctuate_market === true"
                               type="radio" id="true" :value="true"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="true" class="ml-2 block text-sm font-medium leading-6 text-white">Yes</label>
                        <input @change="autosaveLocally()" v-model="stepForm.comfort_fluctuate_market"
                               :checked="stepForm.comfort_fluctuate_market === false"
                               type="radio" id="false" :value="false"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="false" class="ml-2 block text-sm font-medium leading-6 text-white">No</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.comfort_fluctuate_market">{{ stepForm.errors.comfort_fluctuate_market }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Volatility means how quickly and to what extent an assets price may change. Higher risk assets can experience more extensive price changes, whereas lower risk assets can experience less extensive price changes.
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.day_to_day_volatility"
                               :checked="stepForm.day_to_day_volatility === 0"
                               type="radio" id="a" :value="0"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="a" class="ml-2 block text-sm font-medium leading-6 text-white">A</label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.day_to_day_volatility"
                               :checked="stepForm.day_to_day_volatility === 1"
                               type="radio" id="b" :value="1"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="b" class="ml-2 block text-sm font-medium leading-6 text-white">B</label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.day_to_day_volatility"
                               :checked="stepForm.day_to_day_volatility === 2"
                               type="radio" id="c" :value="2"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="c" class="ml-2 block text-sm font-medium leading-6 text-white">C</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.day_to_day_volatility">{{ stepForm.errors.day_to_day_volatility }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        How concerned would you be if the value of your investments fell by the following amounts in any one year (% fall)?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <ConcernRatingTable
                            :stepFormData="stepForm.short_term_volatility"
                            :enumItem="formData.enums.risk_assessment_volatility"
                            @set-rating="setRating"
                        ></ConcernRatingTable>

                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.short_term_volatility">{{ stepForm.errors.short_term_volatility }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        Investing involves a compromise between the risk you are prepared to accept for the degree of return you hope to achieve. Historically, investments with higher capital returns have generally been associated with a greater risk of capital loss. Conversely, investments that carry a lower risk of capital loss have historically yielded lower capital returns.

                        Given that a more volatile portfolio of investments may experience a wider range of gains and losses, which of the following lines would you be most comfortable with, knowing that performance anywhere within that range?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.medium_term_volatility"
                               :checked="stepForm.medium_term_volatility === 0"
                               type="radio" id="a" :value="0"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="a" class="ml-2 block text-sm font-medium leading-6 text-white">A</label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.medium_term_volatility"
                               :checked="stepForm.medium_term_volatility === 1"
                               type="radio" id="b" :value="1"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="b" class="ml-2 block text-sm font-medium leading-6 text-white">B</label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.medium_term_volatility"
                               :checked="stepForm.medium_term_volatility === 2"
                               type="radio" id="c" :value="2"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="c" class="ml-2 block text-sm font-medium leading-6 text-white">C</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.medium_term_volatility">{{ stepForm.errors.medium_term_volatility }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        If my portfolio of investments dropped in value by 20% within any given period, I would take that as a good time to
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.volatility_behaviour"
                               :checked="stepForm.volatility_behaviour === 0"
                               type="radio" id="sell-them" :value="0"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="sell-them" class="ml-2 block text-sm font-medium leading-6 text-white">
                            Sell them
                        </label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.volatility_behaviour"
                               :checked="stepForm.volatility_behaviour === 1"
                               type="radio" id="do-nothing" :value="1"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="do-nothing" class="ml-2 block text-sm font-medium leading-6 text-white">
                            Do nothing
                        </label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.volatility_behaviour"
                               :checked="stepForm.volatility_behaviour === 2"
                               type="radio" id="buy-more" :value="2"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="buy-more" class="ml-2 block text-sm font-medium leading-6 text-white">
                            Buy more stocks and shares
                        </label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.medium_term_volatility">{{ stepForm.errors.volatility_behaviour }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        A longer-term strategy is about ‘time in the market’, given that highly volatile assets may experience significant lows, and may do so for a sustained period before they recover sufficiently to provide a higher return.  If you take a long-term view, even during a downturn, a more volatile investment should still provide positive returns.

                        Which of the following are you most comfortable with?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.long_term_volatility"
                               :checked="stepForm.long_term_volatility === 0"
                               type="radio" id="a" :value="0"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="a" class="ml-2 block text-sm font-medium leading-6 text-white">A</label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.long_term_volatility"
                               :checked="stepForm.long_term_volatility === 1"
                               type="radio" id="b" :value="1"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="b" class="ml-2 block text-sm font-medium leading-6 text-white">B</label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.long_term_volatility"
                               :checked="stepForm.long_term_volatility === 2"
                               type="radio" id="c" :value="2"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="c" class="ml-2 block text-sm font-medium leading-6 text-white">C</label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.long_term_volatility">{{ stepForm.errors.long_term_volatility }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2">
                    <label class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">
                        If you didn’t require access to your invested capital for at least 10 years in the future, for how long would you be prepared to see your invested capital go down in value in consecutive years before you decided to take it out of the markets and cash it in?
                    </label>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.time_in_market"
                               :checked="stepForm.time_in_market === 0"
                               type="radio" id="first-year" :value="0"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="first-year" class="ml-2 block text-sm font-medium leading-6 text-white">
                            Cash in if there was any loss in value in the first year
                        </label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.time_in_market"
                               :checked="stepForm.time_in_market === 1"
                               type="radio" id="two-year" :value="1"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="two-year" class="ml-2 block text-sm font-medium leading-6 text-white">
                            Within 2 years
                        </label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.time_in_market"
                               :checked="stepForm.time_in_market === 2"
                               type="radio" id="three-year" :value="2"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="three-year" class="ml-2 block text-sm font-medium leading-6 text-white">
                            Within 3 years
                        </label>
                    </div>
                    <div class="pt-1 flex items-center space-x-4 space-y-0 md:mt-0 md:pr-2 md:col-span-2">
                        <input @change="autosaveLocally()" v-model="stepForm.time_in_market"
                               :checked="stepForm.time_in_market === 3"
                               type="radio" id="more" :value="3"
                               class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                        <label for="more" class="ml-2 block text-sm font-medium leading-6 text-white">
                            3 years or more
                        </label>
                    </div>
                    <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.time_in_market">{{ stepForm.errors.time_in_market }}</p>
                </div>
                <div class="mt-2 sm:col-span-6 sm:mt-0 md:pr-2 py-2 flex justify-center">
                    <button type="button" @click="submitAssessment()"
                            :disabled="isSubmitDisabled()"
                            class="inline-flex items-center gap-x-1.5 rounded-md bg-sage px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#00b49d] disabled:bg-slate-300 disabled:text-slate-700 disabled:cursor-not-allowed">
                        Submit Assessment
                    </button>
                </div>
                <div class="sm:col-span-6 sm:mt-0 flex justify-center gap-x-2 text-red-500"
                     v-if="isSubmitDisabled()">
                    <InformationCircleIcon class="w-5 h-5"></InformationCircleIcon>
                    <span class="text-xs text-center">Please fill out the all necessary fields here and Investment Risk Assessment or <br/>Pension Risk Assessment fields before assessment submission</span>
                </div>
            </div>
        </div>
    </dynamic-form-wrapper>
</template>
