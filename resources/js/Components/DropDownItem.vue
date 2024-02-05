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
const selectedMenuId = inject("selectedMenuId");

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
    <div class="bg-aaron-900 sm:rounded-lg h-full" v-show="tabIndex == selectedMenuId">
        <div class="sticky top-0 z-[450] w-full flex flex-col gap-6 bg-aaron-900">
            <div>
                <div class="w-full flex flex-row items-center justify-between px-8 py-6 mb-8 border-b-2">
                    <h1 class="text-2xl font-medium">
                        {{ tab.name }}
                    </h1>
                    <AutoSaveSpinner :autosave="autosaveState" />
                </div>
                <div class="hidden flex w-full h-2.5 bg-gray-700 rounded-md">
                    <div class="bg-aaron-400 w-[50%] rounded-r-md" />
                </div>
                <!--
                <hr class="-mx-32 h-1 mt-2 mb-6 bg-aaron-950 border-0">
                -->
                <div class="px-8 mb-8 w-full">
                    <div class="mb-4">
                        <span>{{ props.tabName + ' progress: '+ props.progress + '%' }} </span>
                    </div>
                    <div class="flex w-full h-2.5 overflow-hidden bg-gray-700 rounded-md">
                        <div class="bg-aaron-400 w-[50%] rounded-r-md" />
                    </div>
                </div>
            </div>
        </div>


        <div class="px-8 block">
            <SectionSidebar v-if="tab.sidebaritems" :sidebarItems="tab.sidebaritems" :progress="tab.progress" :tabName="tab.name" >
                <SectionContent @auto-save-up="handleAutosave" v-for="(item, index) in tab.sidebaritems" v-bind:key="index" :item="item" :sectionIndex="index"></SectionContent>
            </SectionSidebar>
        </div>
    </div>
</template>
