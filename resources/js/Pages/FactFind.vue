<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FormWell from "@/Components/FormWell.vue";
import Tabs from "@/Components/Tabs.vue";
import Tab from "@/Components/Tab.vue";
import {Link} from "@inertiajs/vue3";

defineProps({
    title: String,
    breadcrumbs: Array,
    tabs: Object
});

function syncToIo()
{
    alert('clicked');
}

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">

        <div class="sections flex flex-col">
                <Tabs :tabTitles="tabs">
                    <Tab v-for="(tab, index) in tabs" v-bind:key="index" :tab="tab" :tabIndex="index"></Tab>
                </Tabs>
        </div>
    </AppLayout>
</template>
