<script setup>
import { defineProps, ref, provide, inject, onBeforeMount } from "vue";

const props = defineProps({
    sidebarItems: {
        type: Object,
        required: true
    }
});

const initialSectionKey = inject("onloadKey");
const selectedSectionId = ref(1);

function sectionsClick(index, item) {
    selectedSectionId.value = index

    Object.keys(props.sidebarItems).forEach(key => {
        props.sidebarItems[key].current = false;
    });

    item.current = true;
}

provide("selectedSectionId", selectedSectionId);

onBeforeMount(() => {
    selectedSectionId.value = initialSectionKey.value
});

</script>

<template>
<<<<<<< HEAD:resources/js/Components/TabSidebarMenu.vue
    <aside id="default-sidebar" class="h-full absolute w-80 transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <div class="px-3 py-4 overflow-y-auto text-white">
=======
    <aside id="default-sidebar" class="h-1/4 w-full md:w-80 mb-8 sm:hidden md:block md:h-full md:absolute md:mb-0" aria-label="Sidebar">
        <div class="md:px-3 py-4 overflow-y-auto bg-aaron-900 dark:bg-aaron-900 text-white">
>>>>>>> main:resources/js/Components/SectionSidebar.vue
            <ul class="font-medium">
                <li v-for="(item, index) in props.sidebarItems"
                    :key="item.name"
                    :id="index"
                    @click="sectionsClick(index, item)">
<<<<<<< HEAD:resources/js/Components/TabSidebarMenu.vue
                    <a href="#" class="flex items-center p-2 text-aaron-50 gap-x-3 rounded-md text-sm leading-6 font-semibold group">
=======
                    <div class="flex items-center p-2 text-aaron-50 gap-x-3 rounded-md text-sm leading-6 font-semibold group">
>>>>>>> main:resources/js/Components/SectionSidebar.vue
                        <div class="rounded-full w-11 h-11 py-2 text-center"
                            :class="[item.current ? 'bg-aaron-400' : 'bg-aaron-950']">
                                {{ index }}
                        </div>
                        <span class="ms-3 text-base">{{ item.name }}</span>
                    </div>
                </li>
            </ul>
        </div>
    </aside>

<<<<<<< HEAD:resources/js/Components/TabSidebarMenu.vue
    <div class="pt-0 px-4 pb-4  sm:ml-80">
        <div class="pt-0 px-4 pb-4 h-full">
=======
    <div class="md:p-4 sm:ml-80">
        <div class="p-4 h-full">
>>>>>>> main:resources/js/Components/SectionSidebar.vue
            <slot></slot>
        </div>
    </div>

</template>
