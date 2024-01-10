<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FormWell from "@/Components/FormWell.vue";
import Tabs from "@/Components/Tabs.vue";
import Tab from "@/Components/Tab.vue";
import DropDownMenu from "@/Components/DropDownMenu.vue";
import DropDownItem from "@/Components/DropDownItem.vue";
import {Link} from "@inertiajs/vue3";


defineProps({
    title: String,
    breadcrumbs: Array,
    tabs: Object
});

function changeSection(section)
{
    //axios.get();
}

function changeStep(section,step)
{

}

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <div class="sections flex flex-col">
            <div class="hidden md:block">
                <Tabs :tabTitles="tabs">
                    <Tab v-for="(tab, index) in tabs" v-bind:key="index" :tab="tab" :tabIndex="index"></Tab>
                </Tabs>
            </div>
            <div class="md:hidden">
                <DropDownMenu :titles="tabs">
                    <DropDownItem v-for="(tab, index) in tabs" v-bind:key="index" :tab="tab" :tabIndex="index"></DropDownItem>
                </DropDownMenu>
            </div>
        </div>
    </AppLayout>
</template>
