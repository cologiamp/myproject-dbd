<script setup>
import { ref, provide, inject, onBeforeMount } from "vue";

const props = defineProps({
    sidebarItems: {
        type: Object,
        required: true
    },
    progress: {
        type: Number,
        default: 0
    },
    tabName: {
        type: String,
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

    <aside id="default-sidebar" class="h-1/4 w-full md:w-80 mb-8 sm:hidden md:block md:h-full md:absolute md:mb-0" aria-label="Sidebar">
        <div class="md:px-3 py-4 overflow-y-auto bg-aaron-900 text-white">
            <ul class="font-medium">
                <li class="cursor-pointer" v-for="(item, index) in props.sidebarItems"
                    :key="item.name"
                    :id="index"
                    @click="sectionsClick(index, item)">
                    <div class="flex items-center p-2 text-aaron-50 gap-x-3 rounded-md text-sm leading-6 font-semibold group">
                        <div class="rounded-full w-11 h-11 py-2 text-center hover:bg-aaron-400 group-hover:bg-aaron-400"
                            :class="[item.current ? 'bg-aaron-400' : 'bg-aaron-950']">
                                {{ index }}
                        </div>
                        <span class="ms-3 text-base group-hover:text-aaron-50">{{ item.name }}</span>
                    </div>
                </li>
            </ul>
        </div>
    </aside>

    <div class="md:hidden">
        <div class="mb-4">
            <span>{{ props.tabName + ' progress: '+ props.progress + '%' }} </span>
        </div>
        <div class="flex w-full mb-16 h-2.5 overflow-hidden bg-gray-700 rounded-md">
            <div class="bg-aaron-400 w-[50%] rounded-r-md" />
        </div>
    </div>

    <div class="md:p-4 sm:ml-80">
        <div class="p-4">
            <slot></slot>
        </div>
    </div>

</template>
