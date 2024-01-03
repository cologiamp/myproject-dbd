<script setup>
import { defineProps, ref, provide, inject } from "vue";
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    sidebarItems: {
        type: Object,
        required: true
    },
    tabIndex: {
        type: Number,
        required: true
    },
});

const defaultItem = (navItem) => navItem.current == true;
const selectedItem = ref(props.sidebarItems[props.sidebarItems.findIndex(defaultItem)].name);

function itemsClick(item) {
    selectedItem.value = item.name

    Object.keys(props.sidebarItems).forEach(key => {
        props.sidebarItems[key].current = false;
    });

    item.current = true;
}

provide("selectedItem", selectedItem);

const tabIndex = inject("tabIndex");

</script>

<template>
    <aside id="default-sidebar" class="h-full absolute w-80 transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <div class="px-3 py-4 overflow-y-auto bg-aaron-900 dark:bg-gray-800 text-white">
            <ul class="font-medium">
                <li v-for="(item, index) in props.sidebarItems"
                    :key="item.name"
                    :id="index"
                    @click="itemsClick(item)">
                    <!-- <Link href="/fact-find" :data="{ step: tabIndex, section: index }" class="flex items-center p-2 text-aaron-50 gap-x-3 rounded-md text-sm leading-6 font-semibold group"> -->
                    <a href="#" class="flex items-center p-2 text-aaron-50 gap-x-3 rounded-md text-sm leading-6 font-semibold group">
                        <div class="section-number w-11 h-11 py-2 text-center bg-center bg-auto bg-no-repeat" 
                            :class="[item.current ? 'section-active' : 'section-inactive']">
                            {{ index + 1 }}
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

<style scoped>
.section-active {
    background-image: url("/images/ellipsis-active.png");
}

.section-inactive {
    background-image: url("/images/ellipsis-inactive.png");
}
</style>