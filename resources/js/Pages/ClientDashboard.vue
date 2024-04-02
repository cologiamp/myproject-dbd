<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormWell from "@/Components/FormWell.vue";
import {Link} from "@inertiajs/vue3";
import {QuestionMarkCircleIcon, ShieldExclamationIcon, GlobeAsiaAustraliaIcon, BanknotesIcon} from "@heroicons/vue/24/solid/index.js";
import Swal from "sweetalert2";
import { Switch } from '@headlessui/vue'
import {onMounted, ref} from "vue";
import {isMobile} from "@/mobileCheck.js";

const props = defineProps({
    title: String,
    breadcrumbs: Array,
    contentTitle: String,
    clientId: String,
    clientComplete: Boolean
});

let completeToggle = ref(false);

function syncToIo()
{
    axios.post('/client/' + props.clientId + '/commit-to-io').then(() => {
        Swal.fire({
            title: 'Success!',
            text: "Client synced to IO.",
            showCancelButton: true,
            confirmButtonText: "Good",
        })
    }).catch(error => {
        Swal.fire({
            title: 'Error!',
            text: error.response.data.message,
            showCancelButton: true,
            confirmButtonText: "Fine",
        })
    });
}



function toggleClientComplete() {
    axios.post('/api/toggle-complete/' +  props.clientId)
}


onMounted( ()=> {
    console.log(props.clientComplete);
    completeToggle.value = props.clientComplete
});


</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <form-well>
            <h3 class="text-aaron-50 w-[100%] text-2xl px-4 md:p-0">{{contentTitle}}</h3>
            <div class="relative right-8 text-center">
                <div class="pr-6">
                    <label for="adviser_hub_access" class="block text-sm font-medium leading-6 text-aaron-50 switch">Complete?</label>
                    <Switch @click="toggleClientComplete" v-model="completeToggle" :class="[completeToggle ? 'bg-green-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2']">
                        <span class="sr-only">Use setting</span>
                        <span aria-hidden="true" :class="[completeToggle ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                    </Switch>
                </div>
            </div>
        </form-well>
        <div class="overflow-hidden bg-aaron-950 pt-5 rounded-lg ">

            <div class="px-4 py-5 sm:p-6 w-full grid  grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" >

                <a href="fact-find" class="p-8 h-64 bg-pink-700 rounded-2xl items-center justify-center flex">
                    <div class="flex items-center flex-col">
                        <question-mark-circle-icon class="h-32 w-32 flex-1"></question-mark-circle-icon>
                        <div class="text-3xl">Fact Find</div>
                    </div>
                </a>
                <Link href="pension-objectives" class="p-8 h-64 bg-green-700 rounded-2xl items-center justify-center flex">
                    <div class="flex items-center flex-col">
                        <banknotes-icon class="h-32 w-32 flex-1"></banknotes-icon>
                        <div class="text-3xl">Pension Objectives</div>
                    </div>
                </Link>
                <a href="risk-assessment" class="p-8 h-64 bg-aaron-800 rounded-2xl items-center justify-center flex">
                    <div class="flex items-center flex-col">
                        <ShieldExclamationIcon class="h-32 w-32 flex-1"></ShieldExclamationIcon>
                        <div class="text-3xl">Risk Assessment</div>
                    </div>
                </a>
                <a @click="syncToIo()" href="#" class="p-8 h-64 bg-orange-700 rounded-2xl items-center justify-center flex">
                    <div class="flex items-center flex-col">
                        <globe-asia-australia-icon class="h-32 w-32 flex-1"></globe-asia-australia-icon>
                        <div class="text-3xl">Submit to IO</div>
                    </div>
                </a>
            </div>
        </div>


    </AppLayout>
</template>
