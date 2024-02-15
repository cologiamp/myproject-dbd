<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Tabs from "@/Components/Tabs.vue";
import Tab from "@/Components/Tab.vue";
import DropDownMenu from "@/Components/DropDownMenu.vue";
import DropDownItem from "@/Components/DropDownItem.vue";
import { ref, provide } from "vue";

defineProps({
    title: String,
    breadcrumbs: Array,
    tabs: Object
});


const onloadKey = ref(1);

// Set Tab/Section key based on [current] value(true/false), provide onloadKey to child components
function setOnloadKey (obj, fn) {
    onloadKey.value = Object.keys(obj).find(key => fn(obj[key], key, obj));
}

provide("onloadKey", onloadKey);

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <div class="sections flex flex-col">
                <Tabs :tabTitles="tabs"  @setOnloadKey="setOnloadKey">
                    <Tab v-for="(tab, index) in tabs"
                         v-bind:key="index"
                         :tab="tab"
                         :tabIndex="index"
                       >
                    </Tab>
                </Tabs>
        </div>
    </AppLayout>
</template>
