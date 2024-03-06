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
<!--THIS PAGE IS RENDERED ON MOBILE ONLY-->
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

            </div>
        </div>


        <div class="px-8 block">
            <SectionSidebar v-if="tab.sidebaritems" :sidebarItems="tab.sidebaritems" :tabName="tab.name" :tabIndex="tabIndex">
                <SectionContent @auto-save-up="handleAutosave" v-for="(item, index) in tab.sidebaritems" v-bind:key="index" :item="item" :sectionIndex="index"></SectionContent>
            </SectionSidebar>
        </div>
    </div>
</template>
