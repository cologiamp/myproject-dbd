<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormWell from "@/Components/FormWell.vue";
import {Link} from "@inertiajs/vue3";
import {QuestionMarkCircleIcon, ShieldExclamationIcon, GlobeAsiaAustraliaIcon, BanknotesIcon} from "@heroicons/vue/24/solid/index.js";
import Swal from "sweetalert2";

const props = defineProps({
    title: String,
    breadcrumbs: Array,
    contentTitle: String,
    clientId: String,
    clientComplete: Boolean
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

function toggleClientComplete(event, clientID) {
    axios.post('/api/toggle-complete/' + clientID, {
        'complete': event.target.checked
    })
}

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <form-well>
            <h3 class="text-aaron-50 text-2xl px-4 md:p-0">{{contentTitle}}</h3>
        </form-well>
        <div class="overflow-hidden bg-aaron-950 pt-5 rounded-lg ">
            <div>
                <div class="overflow-hidden bg-aaron-900 sm:rounded-lg ">
                    <div class="px-1 py-1 sm:p-2 flex flex-row-reverse">
                        <slot name="loading"></slot>
                    </div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input @change="toggleClientComplete($event, props.clientId)" v-model="props.clientComplete" type="checkbox" class="sr-only peer" checked>
                        <span class="ms-3 text-sm font-medium text-white dark:text-gray-300">Client Complete</span>
                        <div class="relative text-right table-cell w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>

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
