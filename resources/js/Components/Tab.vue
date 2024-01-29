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
            'progress': 0,
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

<template>

       <div class="bg-aaron-900 sm:rounded-[20px] p-8" v-show="tabIndex == selectedTabId">
        <div class="w-full flex flex-col gap-6 mb-14">
            <div class="w-full flex flex-row items-center justify-between">
                <h1 class="text-2xl font-medium">
                    {{ tab.name }}
                </h1>
                <AutoSaveSpinner :autosave="autosaveState" />
            </div>

            <hr class="-mx-32 h-1 mt-2 mb-6 bg-aaron-950 border-0">

            <span class="-mb-3">{{ tab.name + ' progress: ' + tab.progress + '%' }} </span>
            <div class="flex w-full h-2.5 bg-gray-200 overflow-hidden dark:bg-gray-700 rounded-md">
                <div
                    :class="tab.progress === 100 ? `bg-green-400` : `bg-aaron-400`"
                    :style="{ width: `${tab.progress}%`}"
                    class="rounded-r-md duration-300"
                />
            </div>
        </div>
        <div class="h-fit min-h-[65vh]">
            <SectionSidebar v-if="tab.sidebaritems" :sidebarItems="tab.sidebaritems">
                <SectionContent @auto-save-up="handleAutosave"  v-for="(item, index) in tab.sidebaritems" v-bind:key="index" :item="item" :sectionIndex="index"></SectionContent>
            </SectionSidebar>
        </div>
    </div>
</template>

