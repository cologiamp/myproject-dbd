<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormWell from "@/Components/FormWell.vue";
import draggable from "vuedraggable";

import {
    ArrowRightIcon
} from "@heroicons/vue/24/solid/index.js";
import Swal from "sweetalert2";
import {onMounted, ref} from "vue";
import {isMobile} from "@/mobileCheck.js";
import {ArrowsUpDownIcon, PencilSquareIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    title: String,
    breadcrumbs: Array,
    contentTitle: String,
    clientId: String,
    strategy_reports: Array,
});

const stratReportRef = ref({});
const emit = defineEmits(['removeReport'])


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

function removeReport(id) {
    emit('removeReport', id)
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
                        <div class="p-4 mt-4">

                            <div class="mt-8 flow-root">
                                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-500">
                                            <thead class="bg-aaron-700">
                                            <tr>
                                                <th scope="col" class="p-3"></th>
                                                <th scope="col" class="px-3 py-3.5 text-left font-semibold">Date</th>
                                                <th scope="col" class="px-3 py-3.5 text-left font-semibold">File</th>
                                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0"></th>
                                            </tr>
                                            </thead>
                                            <draggable v-if="stratReportRef.length > 0" v-model="stratReportRef" tag="tbody" item-key="name" @change="updateOrder" class="divide-y divide-gray-500">
                                                <template #item="{ element }">
                                                    <tr>
                                                        <td class="px-3 py-4">
                                                            <!--
                                                            <a href="#" class="hover:text-indigo-900">
                                                                <ArrowsUpDownIcon class="w-5 h-5"></ArrowsUpDownIcon>
                                                            </a>
                                                            -->
                                                        </td>
                                                        <td scope="row" class="text-sm px-3 py-4">{{ element.date }}</td>
                                                        <td class="text-sm px-3 py-4">
                                                            <a target="_blank" :href="element.file">
                                                                {{ element.file }}
                                                            </a>
                                                        </td>
                                                        <td class="relative text-sm py-4 pl-3 pr-4 text-right sm:pr-0">
                                                            <a href="#" @click="removeReport(element.id)">
                                                                <TrashIcon class="w-5 h-5"></TrashIcon>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </draggable>
                                            <tbody v-else>
                                            <tr class="text-center">
                                                <td colspan="7" class="text-sm px-3 py-4 text-center">
                                                    No objectives data.
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <rawDisplayer class="col-3" :value="list" title="List" />
                            </div>

                        </div>
                    </div>
                </div>
        </div>


    </AppLayout>
</template>
