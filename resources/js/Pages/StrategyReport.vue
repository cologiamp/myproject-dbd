<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormWell from "@/Components/FormWell.vue";

import {
    ArrowRightIcon
} from "@heroicons/vue/24/solid/index.js";
import Swal from "sweetalert2";
import {onMounted, ref} from "vue";
import {isMobile} from "@/mobileCheck.js";

const props = defineProps({
    title: String,
    breadcrumbs: Array,
    contentTitle: String,
    clientId: String,
    strategy_reports: Array,
});

const stratReportRef = ref({});

onMounted( ()=> {
    stratReportRef.value = props.strategy_reports.data;
});

function generateReport(){
    axios.get('/api/client/' + props.clientId + '/generate-pdf').then(response => {
        console.log(response.data.strategy_reports);
        stratReportRef.value = response.data.strategy_reports;
    }).catch(error => {
        console.log(error)
        Swal.fire({
            title: 'Error: Something failed. Please try again later.',
            text: error.response.data.message,
      });
 });
}

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <form-well>
            <h3 class="text-aaron-50 text-2xl px-4 md:p-0">{{contentTitle}}</h3>
        </form-well>
        <div class="overflow-hidden bg-aaron-950 pt-5 rounded-lg ">
                <div class="bg-aaron-900 shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-base font-semibold leading-6 text-aaron-50">Generate new Strategy Report</h3>
                        <div class="mt-5">
                            <div class="rounded-md text-aaron-50 bg-aaron-700 px-6 py-5 sm:flex sm:items-start sm:justify-between">
                                <button type="button" @click="generateReport"
                                        class="float-right mr-3 inline-flex items-center gap-x-1.5 rounded-md bg-aaron-400 px-10 py-8 text-lg font-semibold text-white shadow-sm hover:bg-[#0098bc] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    GENERATE <ArrowRightIcon class="w-12 h-8" />
                                </button>
                            </div>
                        </div>
                        <div class="p-4 mt-4 bg-aaron-400">
                            <ul class="p-4 bg-aaron-950" v-for="report in stratReportRef">
                                <li class="bg-pink-700 text-aaron-50 p-5">
                                    {{report}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
        </div>


    </AppLayout>
</template>
