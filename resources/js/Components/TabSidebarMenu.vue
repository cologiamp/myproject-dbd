<script setup>
import { defineProps, ref, provide, inject } from "vue";
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    sidebarItems: {
        type: Object,
        required: true
    }
});

const findSectionKey = (obj, fn) =>
  Object.keys(obj).find(key => fn(obj[key], key, obj));

const selectedSectionId = ref(findSectionKey(props.sidebarItems, x => x.current == true));

function sectionsClick(index, item) {
    selectedSectionId.value = index

    Object.keys(props.sidebarItems).forEach(key => {
        props.sidebarItems[key].current = false;
    });

    item.current = true;
}

provide("selectedSectionId", selectedSectionId);

</script>

<template>
    <aside id="default-sidebar" class="h-full absolute w-80 transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <div class="px-3 py-4 overflow-y-auto bg-aaron-900 dark:bg-gray-800 text-white">
            <ul class="font-medium">
                <li v-for="(item, index) in props.sidebarItems"
                    :key="item.name"
                    :id="index"
                    @click="sectionsClick(index, item)">
                    <!-- <Link href="/fact-find" :data="{ step: tabIndex, section: index }" class="flex items-center p-2 text-aaron-50 gap-x-3 rounded-md text-sm leading-6 font-semibold group"> -->
                    <a href="#" class="flex items-center p-2 text-aaron-50 gap-x-3 rounded-md text-sm leading-6 font-semibold group">
                        <div class="rounded-full w-11 h-11 py-2 text-center"
                            :class="[item.current ? 'bg-aaron-400' : 'bg-aaron-950']">
                                {{ index }}
                        </div>
                        <span class="ms-3 text-base">{{ item.name }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-80">
        <div class="p-4 h-full">
            <slot></slot>
        </div>
    </div>

</template>