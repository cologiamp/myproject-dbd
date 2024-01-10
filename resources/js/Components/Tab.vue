<script setup>
import { defineProps, inject, onBeforeMount } from "vue";

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

const emit = defineEmits(['setOnloadKey']);
const selectedTabId = inject("selectedTabId");

// Emit function from parent component to set selected section onload
function setSectionKey() {
    emit('setOnloadKey', props.tab.sidebaritems, x => x.current == true);
}

onBeforeMount(() => {
    setSectionKey();
});

</script>

<template>
    <div class="bg-aaron-900 sm:rounded-[20px] p-8" v-show="tabIndex == selectedTabId">
        <div class="my-6 pl-6">
            <span class="text-2xl font-medium">{{ tab.name }}</span>
        </div>
        <div class="h-screen">
            <SectionSidebar v-if="tab.sidebaritems" :sidebarItems="tab.sidebaritems">
                <SectionContent v-for="(item, index) in tab.sidebaritems" v-bind:key="index" :item="item" :sectionIndex="index"></SectionContent>
            </SectionSidebar>
        </div>
    </div>
</template>

<style scoped>
</style>