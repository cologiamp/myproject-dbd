<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormWell from "@/Components/FormWell.vue";

import {QuestionMarkCircleIcon, LightBulbIcon, GlobeAsiaAustraliaIcon} from "@heroicons/vue/24/solid/index.js";
import Swal from "sweetalert2";

const props = defineProps({
    title: String,
    breadcrumbs: Array,
    contentTitle: String,
    clientId: String,
});

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <form-well>
            <h3 class="text-aaron-50 text-2xl px-4 md:p-0">{{contentTitle}}</h3>
        </form-well>
        <div class="overflow-hidden bg-aaron-950 pt-5 rounded-lg ">
                <div class="bg-aaron-900 shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-base font-semibold leading-6 text-aaron-50">Nothing to see here</h3>
                        <div class="mt-5">
                            <div class="rounded-md text-aaron-50 bg-aaron-700 px-6 py-5 sm:flex sm:items-start sm:justify-between">
                                <p>This is where you will view, download, generate and email the strategy report from, I expect</p>
                            </div>
                        </div>
                    </div>
                </div>
        </div>


    </AppLayout>
</template>
