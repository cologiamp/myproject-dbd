<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormWell from "@/Components/FormWell.vue";

import {QuestionMarkCircleIcon} from "@heroicons/vue/24/solid/index.js";

defineProps({
    title: String,
    breadcrumbs: Array,
    contentTitle: String
});

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <form-well>
            <h3 class="text-aaron-50 text-2xl">{{contentTitle}}</h3>
        </form-well>
        <div class="overflow-hidden bg-aaron-950 pt-5 rounded-lg ">
            <div class="px-4 py-5 sm:p-6 w-full grid  grid-cols-1 md:grid-cols-2 lg:grid-cols-3">

                <a href="fact-find" class="p-8 h-64 bg-pink-700 rounded-2xl items-center justify-center flex">
                    <div class="flex items-center flex-col">
                        <question-mark-circle-icon class="h-32 w-32 flex-1"></question-mark-circle-icon>
                        <div class="text-3xl">Fact Find</div>
                    </div>

                </a>
            </div>
        </div>


    </AppLayout>
</template>
