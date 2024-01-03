<script setup>
import { defineProps, inject, provide } from "vue";

import TabSidebarMenu from "@/Components/TabSidebarMenu.vue";
import TabContent from "@/Components/TabContent.vue";

const props = defineProps({
    tab: {
        type: Object
    },
    tabIndex: {
        type: Number
    }
});

const selectedTitle = inject("selectedTitle");

provide("tabIndex", props.tabIndex);

</script>

<template>
    <div class="bg-aaron-900 sm:rounded-lg p-8" v-show="tab.name == selectedTitle">
        <div class="my-6 pl-6">
            <span class="text-2xl font-medium">{{ tab.name }}</span>
        </div>
        <div class="h-screen">
            <TabSidebarMenu v-if="tab.sidebaritems" :sidebarItems="tab.sidebaritems">
                <TabContent v-for="(item, index) in tab.sidebaritems" v-bind:key="index" :item="item"></TabContent>
            </TabSidebarMenu>
        </div>
    </div>
</template>

<style scoped>
</style>