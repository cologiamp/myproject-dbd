<script setup>
import { defineProps, inject, provide } from "vue";

import TabSidebarMenu from "@/Components/TabSidebarMenu.vue";
import TabContent from "@/Components/TabContent.vue";

const props = defineProps({
    tab: {
        type: Object,
        default: {
            'name' : 'Tab Content',
            'currrent': true,
            'progress': 0,
            'sidebaritems': {
                'name' : 'Section Content',
                'currrent': true
            }
        }
    },
    tabIndex: {
        type: String
    }
});

const selectedTabId = inject("selectedTabId");

</script>

<template>
    <div class="bg-aaron-900 sm:rounded-lg p-8" v-show="tabIndex == selectedTabId">
        <div class="w-full flex flex-col gap-6 mb-14">
            <div class="w-full flex flex-row items-center justify-between">
                <h1 class="text-2xl font-medium">
                    {{ tab.name }}
                </h1>
                <div class="flex flex-row items-center gap-4">
                    <p>Saved</p>
                    <p>Spinner</p>
                </div>
            </div>
            <div class="flex w-full h-2.5 bg-gray-200 overflow-hidden dark:bg-gray-700 rounded-md">
                <div class="bg-[#078EBF] w-[50%] rounded-r-md" />
            </div>
        </div>
        <div class="h-screen">
            <TabSidebarMenu v-if="tab.sidebaritems" :sidebarItems="tab.sidebaritems">
                <TabContent v-for="(item, index) in tab.sidebaritems" v-bind:key="index" :item="item" :sectionIndex="index"></TabContent>
            </TabSidebarMenu>
        </div>
    </div>
</template>

<style scoped>
</style>
