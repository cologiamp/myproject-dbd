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
    <div class="bg-aaron-900 sm:rounded-lg p-8 h-full" v-show="tabIndex == selectedMenuId">
        <div class="w-full flex flex-col gap-6 mb-14">
            <div class="w-full flex flex-row items-center justify-between">
                <h1 class="text-2xl font-medium">
                    {{ tab.name }}
                </h1>
                <AutoSaveSpinner :autosave="autosaveState" />
            </div>
            <hr class="-mx-32 h-1 mt-2 mb-6 bg-aaron-950 border-0">
        </div>
        <div class="block">
            <SectionSidebar v-if="tab.sidebaritems" :sidebarItems="tab.sidebaritems">
                <SectionContent @auto-save-up="handleAutosave" v-for="(item, index) in tab.sidebaritems" v-bind:key="index" :item="item" :sectionIndex="index"></SectionContent>
            </SectionSidebar>
        </div>
    </div>
</template>
