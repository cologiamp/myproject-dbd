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
            <div class="hidden md:block">
                <Tabs :tabTitles="tabs" @setOnloadKey="setOnloadKey">
                    <Tab v-for="(tab, index) in tabs"
                         v-bind:key="index"
                         :tab="tab"
                         :tabIndex="index"
                         @setOnloadKey="setOnloadKey">
                    </Tab>
                </Tabs>
            </div>
            <div class="md:hidden">
                <DropDownMenu :titles="tabs" key="tabs.name" @setOnloadKey="setOnloadKey">
                    <DropDownItem v-for="(tab, index) in tabs"
                                  v-bind:key="index"
                                  :tab="tab"
                                  :tabIndex="index"
                                  @setOnloadKey="setOnloadKey">
                    </DropDownItem>
                </DropDownMenu>
            </div>
        </div>
    </AppLayout>
</template>
