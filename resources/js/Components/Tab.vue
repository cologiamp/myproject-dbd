<script setup>
import { inject, onBeforeMount, ref } from "vue";

import SectionSidebar from "@/Components/SectionSidebar.vue";
import SectionContent from "@/Components/SectionContent.vue";
import AutoSaveSpinner from "./AutoSaveSpinner.vue";

const props = defineProps({
    tab: {
        type: Object,
        default: {
            'name' : 'Tab Content',
            'current': true,
            'sidebaritems': {
                'name' : 'Section Content',
                'current': true
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
let autosaveState = ref(1);
function handleAutosave(val){
    autosaveState.value = val;
}

</script>
<!--THIS PAGE IS RENDERED ON DESKTOP ONLY-->
<template>

       <div class="bg-aaron-900 sm:rounded-[20px] p-8" v-show="tabIndex == selectedTabId">
        <div class="w-full flex flex-col gap-6 mb-4">
            <div class="w-full flex flex-row items-center justify-between">
                <h1 class="text-2xl font-medium">
                    {{ tab.name }}
                </h1>
                <AutoSaveSpinner :autosave="autosaveState" />
            </div>
            <hr class="-mx-16 h-1 mt-2 mb-6 bg-aaron-950 border-0">

        </div>
        <div class="h-fit min-h-[65vh]">
            <SectionSidebar v-if="tab.sidebaritems"
                :sidebarItems="tab.sidebaritems"
                :tabIndex="tabIndex"
                :tabName="tab.name">
                <SectionContent @auto-save-up="handleAutosave" v-for="(item, index) in tab.sidebaritems" v-bind:key="index"
                    :item="item"
                    :sectionIndex="index"
                    :tabIndex="tabIndex"
                    :tabName="tab.name">
                </SectionContent>
            </SectionSidebar>
            <SectionContent v-else-if="tab.tabcontent" @auto-save-up="handleAutosave" :item="tab.tabcontent"  :tabIndex="tabIndex"></SectionContent>
        </div>
    </div>
</template>

