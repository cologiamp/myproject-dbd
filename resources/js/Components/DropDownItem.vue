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
        <div class="sticky top-0 z-[450] h-36 pt-8 px-8 w-full flex flex-col gap-6 mb-14 bg-aaron-900">
                <div class="w-full flex flex-row items-center justify-between">
                <h1 class="text-2xl font-medium">
                    {{ tab.name }}
                </h1>
                <AutoSaveSpinner :autosave="autosaveState" />
            </div>
            <div class="flex w-full h-2.5 bg-gray-700 rounded-md">
                <div class="bg-aaron-400 w-[50%] rounded-r-md" />
            </div>
        </div>
        <div class="p-8 block">
            <SectionSidebar v-if="tab.sidebaritems" :sidebarItems="tab.sidebaritems">
                <SectionContent @auto-save-up="handleAutosave" v-for="(item, index) in tab.sidebaritems" v-bind:key="index" :item="item" :sectionIndex="index"></SectionContent>
            </SectionSidebar>
        </div>
    </div>
</template>
