<script setup>
import { ref, computed, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import { ArrowLongRightIcon, ArrowLongLeftIcon } from '@heroicons/vue/24/solid';

const formStep = ref(0); // 0 = Front Cover, Questionnaire starts at 1

// On pages with graphs, reset the slide counter just in case the page is revisited
watch(formStep, async (newStep, oldStep) => {
    switch (newStep) {
        case 2:
            graphCounterQ2.value = 0;
            break;
        case 4:
            graphCounterQ4.value = 0;
            break;
        case 6:
            graphCounterQ6.value = 0;
            break;
    }

})
const progressPercentage = computed(() => {
    return (formStep.value / 7) * 100;
})
const nextStep = () => {
    formStep.value++;
};
const prevStep = () => {
    formStep.value--;
};

function formatMoney(amount) {
    return new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP' }).format(amount);
}

// Q1

const formQ1 = useForm({
    risk_tolerance: '',
});
const q1Options = [
    { id: 1, label: 'Yes', value: true },
    { id: 2, label: 'No', value: false },
]
const submitQ1 = () => {
    formQ1.post(route('client.risk.q1.submit'), {
        onSuccess: () => formStep.value++,
        preserveScroll: false
    })
};

// Q2

const formQ2 = useForm({
    volatility: '',
});
const q2Options = [
    { id: 1, label: 'A - Cautious', value: 0 },
    { id: 2, label: 'B - Balanced', value: 1 },
    { id: 3, label: 'C - Adventurous', value: 2 },
]
const graphCollectionQ2 = document.getElementsByClassName("q2-graph-data");
const graphCounterQ2 = ref(0);
const submitQ2 = () => {
    formQ2.post(route('client.risk.q2.submit'), {
        onSuccess: () => formStep.value++,
        preserveScroll: false
    })
};

// Q3

const formQ3 = useForm({
    amount_invested: 20000,
    concern5percent: '1',
    concern10percent: '1',
    concern20percent: '1',
    concern30percent: '1',
    concern40percent: '1',
    concern50percent: '1',
});
const q3Options = [5,10,20,30,40,50];
const reduction = [];
reduction[5] = computed(() => (formQ3.amount_invested * 0.05));
reduction[10] = computed(() => (formQ3.amount_invested * 0.10));
reduction[20] = computed(() => (formQ3.amount_invested * 0.20));
reduction[30] = computed(() => (formQ3.amount_invested * 0.30));
reduction[40] = computed(() => (formQ3.amount_invested * 0.40));
reduction[50] = computed(() => (formQ3.amount_invested * 0.50));
const submitQ3 = () => {
    formQ3.post(route('client.risk.q3.submit'), {
        onSuccess: () => formStep.value++,
        preserveScroll: false
    })
};


// Q4

const formQ4 = useForm({
    comfortable_range: '',
});
const q4Options = [
    { id: 1, label: 'A - Low', value: 0 },
    { id: 2, label: 'B - Medium', value: 1 },
    { id: 3, label: 'C - High', value: 2 },
]
const graphCollectionQ4 = document.getElementsByClassName("q4-graph-data");
const graphCounterQ4 = ref(0);
const submitQ4 = () => {
    formQ4.post(route('client.risk.q4.submit'), {
        onSuccess: () => formStep.value++,
        preserveScroll: false
    })
};


// Q5

const formQ5 = useForm({
    value_drop_behaviour: '',
});
const q5Options = [
    { id: 1, label: 'Sell them', value: 0 },
    { id: 2, label: 'Do nothing and hold in anticipation of a recovery', value: 1 },
    { id: 3, label: 'Invest more into my portfolio', value: 2 },
]
const submitQ5 = () => {
    formQ5.post(route('client.risk.q5.submit'), {
        onSuccess: () => formStep.value++,
        preserveScroll: false
    })
};


// Q6

const formQ6 = useForm({
    comfortable_volatility: '',
});
const q6Options = [
    { id: 1, label: 'A - Low', value: 0 },
    { id: 2, label: 'B - Medium', value: 1 },
    { id: 3, label: 'C - High', value: 2 },
]
const graphCollectionQ6 = document.getElementsByClassName("q6-graph-data");
const graphCounterQ6 = ref(0);
const submitQ6 = () => {
    formQ6.post(route('client.risk.q6.submit'), {
        onSuccess: () => formStep.value++,
        preserveScroll: false
    })
};


// Q7

const formQ7 = useForm({
    cash_in_behaviour: '',
});
const q7Options = [
    { id: 1, label: 'Within 1 year', value: 0 },
    { id: 2, label: 'Within 2 years', value: 1 },
    { id: 3, label: 'Within 3 years', value: 2 },
    { id: 4, label: '3 years or more', value: 3 },
]
const submitQ7 = () => {
    formQ7.post(route('client.risk.q7.submit'), {
        onSuccess: () => formStep.value = 0,
        preserveScroll: false
    })
};

// Arrow key navigation of graph datasets
document.onkeydown = function (e) {
    switch (formStep.value) {
        case 2:
            // Graph 1 (Question 2)
            if(e.key === 'ArrowRight' && graphCounterQ2.value < graphCollectionQ2.length){
                graphCollectionQ2[graphCounterQ2.value].setAttribute("transition-style", "in:wipe:right");
                graphCollectionQ2[graphCounterQ2.value].classList.remove("hidden");
                graphCollectionQ2[graphCounterQ2.value].classList.add("block");
                graphCounterQ2.value++;
            } else if(e.key === 'ArrowLeft' && graphCounterQ2.value > 0){
                graphCounterQ2.value--;
                graphCollectionQ2[graphCounterQ2.value].setAttribute("transition-style", "out:wipe:left");
                setTimeout(() => {
                    graphCollectionQ2[graphCounterQ2.value].classList.remove("block");
                    graphCollectionQ2[graphCounterQ2.value].classList.add("hidden");
                }, 2500);
            }
            break;
        case 4:
            // Graph 2 (Question 4)
            if(e.key === 'ArrowRight' && graphCounterQ4.value < graphCollectionQ4.length){
                graphCollectionQ4[graphCounterQ4.value].setAttribute("transition-style", "in:wipe:right");
                graphCollectionQ4[graphCounterQ4.value].classList.remove("hidden");
                graphCollectionQ4[graphCounterQ4.value].classList.add("block");
                graphCounterQ4.value++;
            } else if(e.key === 'ArrowLeft' && graphCounterQ4.value > 0){
                graphCounterQ4.value--;
                graphCollectionQ4[graphCounterQ4.value].setAttribute("transition-style", "out:wipe:left");
                setTimeout(() => {
                    graphCollectionQ4[graphCounterQ4.value].classList.remove("block");
                    graphCollectionQ4[graphCounterQ4.value].classList.add("hidden");
                }, 2500);
            }
            break;
        case 6:
            // Graph 3 (Question 6)
            if(e.key === 'ArrowRight' && graphCounterQ6.value < graphCollectionQ6.length){
                graphCollectionQ6[graphCounterQ6.value].setAttribute("transition-style", "in:wipe:right");
                graphCollectionQ6[graphCounterQ6.value].classList.remove("hidden");
                graphCollectionQ6[graphCounterQ6.value].classList.add("block");
                graphCounterQ6.value++;
            } else if(e.key === 'ArrowLeft' && graphCounterQ6.value > 0){
                graphCounterQ6.value--;
                graphCollectionQ6[graphCounterQ6.value].setAttribute("transition-style", "out:wipe:left");
                setTimeout(() => {
                    graphCollectionQ6[graphCounterQ6.value].classList.remove("block");
                    graphCollectionQ6[graphCounterQ6.value].classList.add("hidden");
                }, 2500);
            }
    }

};
</script>

<template>
    <div class="LotaGrotesqueAlt1Regular">
        <Head title="Risk Profile Questionnaire" />

        <header class="bg-white py-6">
            <div :class="[formStep === 0 ? 'flex justify-end' : '', 'mx-auto max-w-6xl']">
                <img src="https://www.wealthatwork.co.uk/mywealth/wp-content/themes/my-wealth/images/my-wealth-website-logo.png" class="w-[200px]" alt="my wealth" />
            </div>
        </header>

        <main>
            <div v-if="formStep > 0 && formStep <= 7" class="bg-waw-dark-blue py-10">
                <div class="mx-auto max-w-5xl text-white">
                    <div class="w-full bg-[#01294E] rounded-xl h-3 mb-4">
                        <div class="bg-[#4BAAEE] h-3 rounded-xl transition-[width] duration-500" :style="'width:'+ progressPercentage + '%'"></div>
                    </div>
                    <p class="text-white font-sm mb-0">Question {{ formStep }} of 7</p>
                </div>
            </div>

            <!--<transition name="fade" mode="out-in">-->

            <!-- FRONT PAGE -->

            <div v-if="formStep === 0">

                <div class="mx-auto max-w-6xl py-20">

                    <div class="poppins-bold text-[120px] text-[#003a70] uppercase leading-[110px] mb-8">Your Risk <br>Profile</div>
                    <div class="mb-8"><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/gradient-blocks.png" alt="" class="w-full" /></div>
                    <p class="question text-right">Exploring your attitude to risk</p>

                    <div class="mt-10 flex justify-end">
                        <button type="button" @click="nextStep" class="waw-btn">
                            Next <ArrowLongRightIcon class="w-[24px] text-white" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Q1 -->

            <div v-else-if="formStep === 1">
                <div class="bg-waw-dark-blue pb-10 min-h-48">
                    <div class="mx-auto max-w-5xl text-white">
                        <h1 class="text-7xl lowercase bembo">Risk Tolerance.</h1>
                    </div>
                </div>

                <div class="mx-auto max-w-5xl py-20">
                    <div v-if="formQ1.errors.risk_tolerance" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
                        <strong class="font-bold">{{ formQ1.errors.risk_tolerance }}</strong>
                    </div>

                    <p class="std-text">Markets fluctuate, which means that the value of investments can fall as well as rise.</p>
                    <p class="question">Are you comfortable with an investment that may fluctuate in value?</p>

                    <form v-on:submit.prevent="submitQ1">
                        <RadioGroup v-model="formQ1.risk_tolerance">
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-y-6 sm:gap-x-4">
                                <RadioGroupOption as="template" v-for="option in q1Options" :key="option.id" :value="option.value" v-slot="{ active, checked }">
                                    <div :class="[active || checked ? 'border-[#003a70] ring-2 ring-[#003a70]' : 'border-[#cfcfcd]', 'radio-option']">
                                            <span :class="[checked ? 'bg-[#003a70] border-transparent' : 'bg-white border-[#a6a6a6]', active ? 'ring-2 ring-offset-2 ring-[#003a70]' : '', 'radio-option-circle']" aria-hidden="true">
                                                <span class="rounded-full bg-white w-3 h-3" />
                                            </span>
                                        <span class="ml-5 flex flex-col">
                                                <RadioGroupLabel as="span" :class="[checked ? 'text-[#003a70]' : 'text-[#003a70]', 'block text-2xl font-bold leading-12']">{{ option.label }}</RadioGroupLabel>
                                            </span>
                                    </div>
                                </RadioGroupOption>
                            </div>
                        </RadioGroup>

                        <div class="mt-10 flex justify-end">
                            <button class="waw-btn">
                                Next <ArrowLongRightIcon class="w-[24px] text-white" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Q2 -->

            <div v-else-if="formStep === 2">
                <div class="bg-waw-dark-blue pb-10 min-h-48">
                    <div class="mx-auto max-w-5xl text-white">
                        <h1 class="text-7xl lowercase bembo">Day to day volatility <br>of a portfolio of assets.</h1>
                    </div>
                </div>

                <div class="mx-auto max-w-5xl py-20">
                    <div v-if="formQ2.errors.volatility" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
                        <strong class="font-bold">{{ formQ2.errors.volatility }}</strong>
                    </div>

                    <p class="std-text">Volatility means how quickly and to what extent an assets price may change. Higher risk assets can experience more extensive price changes, whereas lower risk assets can experience less extensive price changes.</p>
                    <p class="question">Which of the following graphs are you most comfortable with?</p>

                    <div class="graph-wrapper mb-10">
                        <div><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/graph1-BACK.png" alt="" class="w-full" /></div>
                        <div class="q2-graph-data hidden" transition-style="in:wipe:right"><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/graph1-A.png" alt="" class="w-full" /></div>
                        <div class="q2-graph-data hidden" transition-style="in:wipe:right"><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/graph1-B.png" alt="" class="w-full" /></div>
                        <div class="q2-graph-data hidden" transition-style="in:wipe:right"><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/graph1-C.png" alt="" class="w-full" /></div>
                    </div>


                    <form v-on:submit.prevent="submitQ2" class="clear-both">
                        <RadioGroup v-model="formQ2.volatility">
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-y-6 sm:gap-x-4">
                                <RadioGroupOption as="template" v-for="option in q2Options" :key="option.id" :value="option.value" v-slot="{ active, checked }">
                                    <div :class="[active || checked ? 'border-[#003a70] ring-2 ring-[#003a70]' : 'border-[#cfcfcd]', 'radio-option']">
                                            <span :class="[checked ? 'bg-[#003a70] border-transparent' : 'bg-white border-[#a6a6a6]', active ? 'ring-2 ring-offset-2 ring-[#003a70]' : '', 'radio-option-circle']" aria-hidden="true">
                                                <span class="rounded-full bg-white w-3 h-3" />
                                            </span>
                                        <span class="ml-5 flex flex-col">
                                                <RadioGroupLabel as="span" :class="[checked ? 'text-[#003a70]' : 'text-[#003a70]', 'block text-2xl font-bold leading-12']">{{ option.label }}</RadioGroupLabel>
                                            </span>
                                    </div>
                                </RadioGroupOption>
                            </div>
                        </RadioGroup>

                        <div class="mt-10 flex justify-between">
                            <button type="button" class="waw-btn-alt" @click="prevStep">
                                <ArrowLongLeftIcon class="w-[24px] text-white" /> Previous
                            </button>
                            <button class="waw-btn">
                                Next <ArrowLongRightIcon class="w-[24px] text-white" />
                            </button>
                        </div>
                    </form>

                </div>

            </div>

            <!-- Q3 -->

            <div v-else-if="formStep === 3">
                <div class="bg-waw-dark-blue pb-10 min-h-48">
                    <div class="mx-auto max-w-5xl text-white">
                        <h1 class="text-7xl lowercase bembo">Level of concern due <br>to short term volatility.</h1>
                    </div>
                </div>

                <div class="mx-auto max-w-5xl py-20">
                    <div v-if="formQ3.errors.amount_invested" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
                        <strong class="font-bold">{{ formQ3.errors.amount_invested }}</strong>
                    </div>

                    <p class="question">How concerned would you be if the value of your investments fell by the following amounts in any one year (% fall)?</p>

                    <p class="text-3xl text-[#003a70] font-bold mb-0">Amount invested:</p>
                    <form v-on:submit.prevent="submitQ3">
                        <div class="flex flex-wrap items-stretch w-full md:w-1/4 relative h-15 bg-gray-100 border-0 items-center rounded-md mb-10">
                            <div class="flex -mr-px justify-center w-15">
                                <span class="flex items-center leading-normal bg-gray-200 px-3 border-0 rounded rounded-r-none text-2xl text-gray-700">&pound;</span>
                            </div>
                            <input
                                v-model="formQ3.amount_invested"
                                type="number"
                                class="flex-shrink flex-grow flex-auto leading-normal w-px flex-1 border-0 border-grey-light rounded rounded-l-none px-3 self-center relative text-xl outline-none bg-gray-50"
                                required
                            />
                        </div>

                        <div v-for="percentage in q3Options" class="flex border border-[#CFCFCD] rounded-3xl p-8 pb-12 mb-5">
                            <div class="w-1/4">
                                <div class="text-[#407EC9] text-md mb-2">{{ percentage }}%</div>
                                <div class="text-[#003a70] text-3xl font-bold">-{{ formatMoney(formQ3.amount_invested * (percentage/100)) }}</div>
                            </div>
                            <div class="flex flex-col space-y-2 p-2 w-3/4">
                                <div class="flex justify-between">
                                    <span>Unconcerned</span>
                                    <span>Very concerned</span>
                                </div>
                                <div class="custom-slider"><input v-model="formQ3['concern' + percentage + 'percent']" type="range" class="w-full" min="1" max="5" step="1"/></div>
                                <ul class="flex justify-between w-full px-[10px]">
                                    <li class="flex justify-center relative"><span class="absolute">1</span></li>
                                    <li class="flex justify-center relative"><span class="absolute">2</span></li>
                                    <li class="flex justify-center relative"><span class="absolute">3</span></li>
                                    <li class="flex justify-center relative"><span class="absolute">4</span></li>
                                    <li class="flex justify-center relative"><span class="absolute">5</span></li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-10 flex justify-between">
                            <button type="button" class="waw-btn-alt" @click="prevStep">
                                <ArrowLongLeftIcon class="w-[24px] text-white" /> Previous
                            </button>
                            <button class="waw-btn">
                                Next <ArrowLongRightIcon class="w-[24px] text-white" />
                            </button>
                        </div>
                    </form>

                </div>

            </div>


            <!-- Q4 -->

            <div v-else-if="formStep === 4">
                <div class="bg-waw-dark-blue pb-10 min-h-48">
                    <div class="mx-auto max-w-5xl text-white">
                        <h1 class="text-7xl lowercase bembo">Medium term gains/losses <br>due to volatility.</h1>
                    </div>
                </div>

                <div class="mx-auto max-w-5xl py-20">
                    <div v-if="formQ4.errors.comfortable_range" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
                        <strong class="font-bold">{{ formQ4.errors.comfortable_range }}</strong>
                    </div>

                    <p class="std-text">Investing involves a compromise between the risk you are prepared to accept for the degree of return you hope to achieve. Historically, investments with higher capital returns have generally been associated with a greater risk of capital loss. Conversely, investments that carry a lower risk of capital loss have historically yielded lower capital returns.</p>
                    <p class="question">Given that a more volatile portfolio of investments may experience a wider range of gains and losses, which of the following lines would you be most comfortable with, knowing that performance could be anywhere within that range?</p>

                    <div class="graph-wrapper mb-10">
                        <div><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/graph2-BACK.png" alt="" class="w-full" /></div>
                        <div class="q4-graph-data hidden" transition-style="in:wipe:right"><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/graph2-A.png" alt="" class="w-full" /></div>
                        <div class="q4-graph-data hidden" transition-style="in:wipe:right"><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/graph2-B.png" alt="" class="w-full" /></div>
                        <div class="q4-graph-data hidden" transition-style="in:wipe:right"><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/graph2-C.png" alt="" class="w-full" /></div>
                    </div>

                    <form v-on:submit.prevent="submitQ4">
                        <RadioGroup v-model="formQ4.comfortable_range">
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-y-6 sm:gap-x-4">
                                <RadioGroupOption as="template" v-for="option in q4Options" :key="option.id" :value="option.value" v-slot="{ active, checked }">
                                    <div :class="[active || checked ? 'border-[#003a70] ring-2 ring-[#003a70]' : 'border-[#cfcfcd]', 'radio-option']">
                                            <span :class="[checked ? 'bg-[#003a70] border-transparent' : 'bg-white border-[#a6a6a6]', active ? 'ring-2 ring-offset-2 ring-[#003a70]' : '', 'radio-option-circle']" aria-hidden="true">
                                                <span class="rounded-full bg-white w-3 h-3" />
                                            </span>
                                        <span class="ml-5 flex flex-col">
                                                <RadioGroupLabel as="span" :class="[checked ? 'text-[#003a70]' : 'text-[#003a70]', 'block text-2xl font-bold leading-12']">{{ option.label }}</RadioGroupLabel>
                                            </span>
                                    </div>
                                </RadioGroupOption>
                            </div>
                        </RadioGroup>

                        <div class="mt-10 flex justify-between">
                            <button type="button" class="waw-btn-alt" @click="prevStep">
                                <ArrowLongLeftIcon class="w-[24px] text-white" /> Previous
                            </button>
                            <button class="waw-btn">
                                Next <ArrowLongRightIcon class="w-[24px] text-white" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Q5 -->

            <div v-else-if="formStep === 5">
                <div class="bg-waw-dark-blue pb-10 min-h-48">
                    <div class="mx-auto max-w-5xl text-white">
                        <h1 class="text-7xl lowercase bembo">Your behaviour due to volatility.</h1>
                    </div>
                </div>

                <div class="mx-auto max-w-5xl py-20">
                    <div v-if="formQ5.errors.value_drop_behaviour" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
                        <strong class="font-bold">{{ formQ5.errors.value_drop_behaviour }}</strong>
                    </div>



                    <p class="question">If my portfolio of investments dropped in value by 20% within any given period, I would take that as a good time to:</p>

                    <form v-on:submit.prevent="submitQ5">
                        <RadioGroup v-model="formQ5.value_drop_behaviour">
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-1 gap-y-6 sm:gap-x-4">
                                <RadioGroupOption as="template" v-for="option in q5Options" :key="option.id" :value="option.value" v-slot="{ active, checked }">
                                    <div :class="[active || checked ? 'border-[#003a70] ring-2 ring-[#003a70]' : 'border-[#cfcfcd]', 'radio-option']">
                                            <span :class="[checked ? 'bg-[#003a70] border-transparent' : 'bg-white border-[#a6a6a6]', active ? 'ring-2 ring-offset-2 ring-[#003a70]' : '', 'radio-option-circle']" aria-hidden="true">
                                                <span class="rounded-full bg-white w-3 h-3" />
                                            </span>
                                        <span class="ml-5 flex flex-col">
                                                <RadioGroupLabel as="span" :class="[checked ? 'text-[#003a70]' : 'text-[#003a70]', 'block text-2xl font-bold leading-12']">{{ option.label }}</RadioGroupLabel>
                                            </span>
                                    </div>
                                </RadioGroupOption>
                            </div>
                        </RadioGroup>

                        <div class="mt-10 flex justify-between">
                            <button type="button" class="waw-btn-alt" @click="prevStep">
                                <ArrowLongLeftIcon class="w-[24px] text-white" /> Previous
                            </button>
                            <button class="waw-btn">
                                Next <ArrowLongRightIcon class="w-[24px] text-white" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Q6 -->

            <div v-else-if="formStep === 6">
                <div class="bg-waw-dark-blue pb-10 min-h-48">
                    <div class="mx-auto max-w-5xl text-white">
                        <h1 class="text-7xl lowercase bembo">Long term volatility.</h1>
                    </div>
                </div>

                <div class="mx-auto max-w-5xl py-20">
                    <div v-if="formQ6.errors.comfortable_volatility" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
                        <strong class="font-bold">{{ formQ6.errors.comfortable_volatility }}</strong>
                    </div>
                    <p class="std-text">A longer-term strategy is about ‘time in the market’, given that highly volatile assets may experience significant lows, and may do so for a sustained period before they recover sufficiently to provide a higher return. If you take a long-term view, even during a downturn, a more volatile investment should still provide positive returns.</p>
                    <p class="question">Which of the following are you most comfortable with?</p>

                    <div class="graph-wrapper mb-10">
                        <div><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/graph3-BACK.png" alt="" class="w-full" /></div>
                        <div class="q6-graph-data hidden" transition-style="in:wipe:right"><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/graph3-A.png" alt="" class="w-full" /></div>
                        <div class="q6-graph-data hidden" transition-style="in:wipe:right"><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/graph3-B.png" alt="" class="w-full" /></div>
                        <div class="q6-graph-data hidden" transition-style="in:wipe:right"><img src="https://d3a6n7gvbr88rj.cloudfront.net/adviser-hub/risk-profile-questionnaire/graph3-C.png" alt="" class="w-full" /></div>
                    </div>

                    <form v-on:submit.prevent="submitQ6">
                        <RadioGroup v-model="formQ6.comfortable_volatility">
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-y-6 sm:gap-x-4">
                                <RadioGroupOption as="template" v-for="option in q6Options" :key="option.id" :value="option.value" v-slot="{ active, checked }">
                                    <div :class="[active || checked ? 'border-[#003a70] ring-2 ring-[#003a70]' : 'border-[#cfcfcd]', 'radio-option']">
                                            <span :class="[checked ? 'bg-[#003a70] border-transparent' : 'bg-white border-[#a6a6a6]', active ? 'ring-2 ring-offset-2 ring-[#003a70]' : '', 'radio-option-circle']" aria-hidden="true">
                                                <span class="rounded-full bg-white w-3 h-3" />
                                            </span>
                                        <span class="ml-5 flex flex-col">
                                                <RadioGroupLabel as="span" :class="[checked ? 'text-[#003a70]' : 'text-[#003a70]', 'block text-2xl font-bold leading-12']">{{ option.label }}</RadioGroupLabel>
                                            </span>
                                    </div>
                                </RadioGroupOption>
                            </div>
                        </RadioGroup>

                        <div class="mt-10 flex justify-between">
                            <button type="button" class="waw-btn-alt" @click="prevStep">
                                <ArrowLongLeftIcon class="w-[24px] text-white" /> Previous
                            </button>
                            <button class="waw-btn">
                                Next <ArrowLongRightIcon class="w-[24px] text-white" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Q7 -->

            <div v-else-if="formStep === 7">
                <div class="bg-waw-dark-blue pb-10 min-h-48">
                    <div class="mx-auto max-w-5xl text-white">
                        <h1 class="text-7xl lowercase bembo">Your behaviour due to volatility.</h1>
                    </div>
                </div>

                <div class="mx-auto max-w-5xl py-20">
                    <div v-if="formQ7.errors.cash_in_behaviour" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
                        <strong class="font-bold">{{ formQ7.errors.cash_in_behaviour }}</strong>
                    </div>
                    <p class="std-text">If you didn’t require access to your invested capital for at least 10 years in the future, for how long would you be prepared to see your invested capital go down in value in consecutive years before you decided to take it out of the markets and cash it in?</p>
                    <p class="question">Cash in if there was any loss in value:</p>

                    <form v-on:submit.prevent="submitQ7">
                        <RadioGroup v-model="formQ7.cash_in_behaviour">
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-y-6 sm:gap-x-4">
                                <RadioGroupOption as="template" v-for="option in q7Options" :key="option.id" :value="option.value" v-slot="{ active, checked }">
                                    <div :class="[active || checked ? 'border-[#003a70] ring-2 ring-[#003a70]' : 'border-[#cfcfcd]', 'radio-option']">
                                            <span :class="[checked ? 'bg-[#003a70] border-transparent' : 'bg-white border-[#a6a6a6]', active ? 'ring-2 ring-offset-2 ring-[#003a70]' : '', 'radio-option-circle']" aria-hidden="true">
                                                <span class="rounded-full bg-white w-3 h-3" />
                                            </span>
                                        <span class="ml-5 flex flex-col">
                                                <RadioGroupLabel as="span" :class="[checked ? 'text-[#003a70]' : 'text-[#003a70]', 'block text-2xl font-bold leading-12']">{{ option.label }}</RadioGroupLabel>
                                            </span>
                                    </div>
                                </RadioGroupOption>
                            </div>
                        </RadioGroup>

                        <div class="mt-10 flex justify-between">
                            <button type="button" class="waw-btn-alt" @click="prevStep">
                                <ArrowLongLeftIcon class="w-[24px] text-white" /> Previous
                            </button>
                            <button class="waw-btn">
                                Finish <ArrowLongRightIcon class="w-[24px] text-white" />
                            </button>
                        </div>
                    </form>

                </div>
            </div>




            <!--</transition>-->

        </main>

    </div>


</template>

<style lang="scss">
@import 'https://unpkg.com/transition-style/transition.wipes.min.css';
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap');

/* Typography */
@font-face {
    font-family: 'Bembo';
    src: url('../../../public/fonts/Bembo.eot?#iefix') format('embedded-opentype'),  url('../../../public/fonts/Bembo.woff') format('woff'), url('../../../public/fonts/Bembo.ttf')  format('truetype'), url('../../../public/fonts/Bembo.svg#Bembo') format('svg');
}
@font-face {
    font-family: 'MetaOffc-Norm';
    src: url('../../../public/fonts/MetaOffc-Norm.eot?#iefix') format('embedded-opentype'),  url('../../../public/fonts/MetaOffc-Norm.woff') format('woff'), url('../../../public/fonts/MetaOffc-Norm.ttf')  format('truetype'), url('../../../public/fonts/MetaOffc-Norm.svg#MetaOffc-Norm') format('svg');
}
@font-face {
    font-family: 'MetaOffc-Bold';
    src: url('../../../public/fonts/MetaOffc-Bold.eot?#iefix') format('embedded-opentype'),  url('../../../public/fonts/MetaOffc-Bold.woff') format('woff'), url('../../../public/fonts/MetaOffc-Bold.ttf')  format('truetype'), url('../../../public/fonts/MetaOffc-Bold.svg#MetaOffc-Bold') format('svg');
}

@font-face {
    font-family: "LotaGrotesqueLight";
    src: url('../../../public/fonts/LotaGrotesqueLight.woff2') format('woff2'), url('../../../public/fonts/LotaGrotesqueLight.woff') format('woff');
}

@font-face {
    font-family: "LotaGrotesqueAlt1RegularItalic";
    src: url('../../../public/fonts/LotaGrotesqueAlt1RegularItalic.woff2') format('woff2'), url('../../../public/fonts/LotaGrotesqueAlt1RegularItalic.woff') format('woff');
}

@font-face {
    font-family: "LotaGrotesqueBold";
    src: url('../../../public/fonts/LotaGrotesqueBold.woff2') format('woff2'), url('../../../public/fonts/LotaGrotesqueBold.woff') format('woff');
}
@font-face {
    font-family: "LotaGrotesqueAlt1Regular";
    src: url('../../../public/fonts/LotaGrotesqueAlt1Regular.woff2') format('woff2'), url('../../../public/fonts/LotaGrotesqueAlt1Regular.woff') format('woff');
}
@font-face {
    font-family: "LotaGrotesqueLightItalic";
    src: url('../../../public/fonts/LotaGrotesqueLightItalic.woff2') format('woff2'), url('../../../public/fonts/LotaGrotesqueLightItalic.woff') format('woff');
}
@font-face {
    font-family: "LotaGrotesqueBoldItalic";
    src: url('../../../public/fonts/LotaGrotesqueBoldItalic.woff2') format('woff2'), url('../../../public/fonts/LotaGrotesqueBoldItalic.woff') format('woff');
}
@font-face {
    font-family: "LotaGrotesqueAlt3Black";
    src: url('../../../public/fonts/LotaGrotesqueAlt3Black.woff2') format('woff2'), url('../../../public/fonts/LotaGrotesqueAlt3Black.woff') format('woff');
}
.LotaGrotesqueAlt1Regular {
    font-family: LotaGrotesqueAlt1Regular;
}
.LotaGrotesqueBold {
    font-family: LotaGrotesqueBold;
}
.LotaGrotesqueLight {
    font-family: LotaGrotesqueLight;
}
.LotaGrotesqueAlt1RegularItalic {
    font-family: LotaGrotesqueAlt1RegularItalic;
}
.LotaGrotesqueBoldItalic {
    font-family: LotaGrotesqueBoldItalic;
}
.LotaGrotesqueLightItalic {
    font-family: LotaGrotesqueLightItalic;
}
.LotaGrotesqueAlt3Black {
    font-family: LotaGrotesqueAlt3Black;
}
.Bembo {
    font-family: Bembo;
}
.MetaOffice {
    font-family: 'MetaOffc-Norm';
}
.MetaOfficeBold {
    font-family: 'MetaOffc-Bold';
}

h1.bembo { font-family: Bembo, serif; }

.std-text {
    @apply text-xl text-[#003a70] mb-5;
}
.question {
    @apply text-3xl text-[#003a70] font-bold mb-12;
}
strong { font-family: LotaGrotesqueBold; }
.poppins-bold {
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
}

/* Colours */
.bg-waw-dark-blue { background-color: #003A70; }

/* Transition */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Miscellaneous */
html, body { background-color: #fff !important; }
.waw-btn {
    @apply hover:cursor-pointer inline-flex items-center justify-center gap-2 rounded-full py-6 px-10 bg-[#4BAAEE] hover:bg-[#3294DB] max-w-xs text-white font-bold duration-300
}
.waw-btn-alt {
    @apply hover:cursor-pointer inline-flex items-center justify-center gap-2 rounded-full py-6 px-10 bg-[#A6A6A6] hover:bg-[#999999] max-w-xs text-white font-bold duration-300
}
.radio-option {
    @apply relative flex cursor-pointer rounded-[2rem] border bg-white p-6 shadow-sm focus:outline-none;
}
.radio-option-circle {
    @apply mt-0.5 h-8 w-8 shrink-0 cursor-pointer rounded-full border-2 flex items-center justify-center;
}

/********** Range Input Styles **********/
.custom-slider {
    --trackHeight: 1rem;
    --thumbRadius: 2rem;
}

/* style the input element with type "range" */
.custom-slider input[type="range"] {
    position: relative;
    appearance: none;
    /* pointer-events: none; */
    border-radius: 999px;
    z-index: 0;
}

/* ::before element to replace the slider track */
.custom-slider input[type="range"]::before {
    content: "";
    position: absolute;
    width: var(--ProgressPercent, 100%);
    height: 100%;
    background: #cfcfcd;
    /* z-index: -1; */
    pointer-events: none;
    border-radius: 999px;
}

/* `::-webkit-slider-runnable-track` targets the track (background) of a range slider in chrome and safari browsers. */
.custom-slider input[type="range"]::-webkit-slider-runnable-track {
    appearance: none;
    background: #cfcfcd;
    height: var(--trackHeight);
    border-radius: 999px;
}

/* `::-moz-range-track` targets the track (background) of a range slider in Mozilla Firefox. */
.custom-slider input[type="range"]::-moz-range-track {
    appearance: none;
    background: #cfcfcd;
    height: var(--trackHeight);
    border-radius: 999px;
}

.custom-slider input[type="range"]::-webkit-slider-thumb {
    position: relative;
    width: var(--thumbRadius);
    height: var(--thumbRadius);
    margin-top: calc((var(--trackHeight) - var(--thumbRadius)) / 2);
    background: #407EC9;
    border-radius: 999px;
    pointer-events: all;
    appearance: none;
    z-index: 1;
}


/* Wipe transitions */

@keyframes wipe-in-right {
    from {
        clip-path: inset(0 100% 0 0);
    }
    to {
        clip-path: inset(0 0 0 0);
    }
}

[transition-style="in:wipe:right"] {
    animation: 2.5s cubic-bezier(.25, 1, .30, 1) wipe-in-right both;
}

@keyframes wipe-out-left {
    from {
        clip-path: inset(0 0 0 0);
    }
    to {
        clip-path: inset(0 100% 0 0);
    }
}

[transition-style="out:wipe:left"] {
    animation: 2.5s cubic-bezier(.25, 1, .30, 1) wipe-out-left both;
}

.graph-wrapper { display: grid; grid: [stack] 1fr / [stack] 1fr auto; margin-left: -3rem; }
.graph-wrapper > div { grid-area: stack; }

</style>
