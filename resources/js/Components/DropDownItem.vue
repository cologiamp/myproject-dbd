<script setup>
import { defineProps, inject, provide } from "vue";

import SectionSidebar from "@/Components/SectionSidebar.vue";
import SectionContent from "@/Components/SectionContent.vue";

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

const selectedMenuId = inject("selectedMenuId");
</script>

<template>
    <div class="bg-aaron-900 sm:rounded-lg p-8 h-full" v-show="tabIndex == selectedMenuId">
        <div class="my-4 px-4">
            <span class="text-2xl font-medium">{{ tab.name }}</span>
        </div>
        <div class="block">
            <SectionSidebar v-if="tab.sidebaritems" :sidebarItems="tab.sidebaritems">
                <SectionContent v-for="(item, index) in tab.sidebaritems" v-bind:key="index" :item="item" :sectionIndex="index"></SectionContent>
            </SectionSidebar>
        </div>
    </div>
</template>