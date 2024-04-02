<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormWell from "@/Components/FormWell.vue";
import {Link} from "@inertiajs/vue3";
import {QuestionMarkCircleIcon, ShieldExclamationIcon, GlobeAsiaAustraliaIcon, BanknotesIcon, ChartBarIcon} from "@heroicons/vue/24/solid/index.js";
import Swal from "sweetalert2";

const props = defineProps({
    title: String,
    breadcrumbs: Array,
    contentTitle: String,
    clientId: String,
});

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

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <form-well>
            <h3 class="text-aaron-50 text-2xl px-4 md:p-0">{{contentTitle}}</h3>
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
                <a href="risk-assessment" class="p-8 h-64 bg-aaron-400 rounded-2xl items-center justify-center flex">
                    <div class="flex items-center flex-col">
                        <ShieldExclamationIcon class="h-32 w-32 flex-1"></ShieldExclamationIcon>
                        <div class="text-3xl">Risk Assessment</div>
                    </div>
                </a>
                <Link href="risk-profile-questionnaire" class="p-8 h-64 bg-yellow-600 rounded-2xl items-center justify-center flex">
                    <div class="flex items-center flex-col text-center">
                        <ChartBarIcon class="h-32 w-32 flex-1"></ChartBarIcon>
                        <div class="text-3xl">Risk Profile Questionnaire</div>
                    </div>
                </Link>
                <a @click="syncToIo()" href="#" class="p-8 h-64 bg-orange-500 rounded-2xl items-center justify-center flex">
                    <div class="flex items-center flex-col">
                        <globe-asia-australia-icon class="h-32 w-32 flex-1"></globe-asia-australia-icon>
                        <div class="text-3xl">Submit to IO</div>
                    </div>
                </a>
            </div>
        </div>


    </AppLayout>
</template>
