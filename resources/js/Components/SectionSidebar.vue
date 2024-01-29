<script setup>
import { ref, provide, inject, onBeforeMount } from "vue";

const props = defineProps({
    sidebarItems: {
        type: Object,
        required: true
    },
    data: () => ({
        scrolledtobottom: false,
    }),
    mounted() {
        this.scroll()
    },
    methods: {
        scroll() {
            window.onscroll = () => {
                const bottomofwindow =
                    math.max(
                        window.pageyoffset,
                        document.documentelement.scrolltop,
                        document.body.scrolltop
                    ) +
                    window.innerheight ===
                    document.documentelement.offsetheight

                if (bottomofwindow) {
                    this.scrolledtobottom = true
                }
            }
        },
    },
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

<style>

.scrollable-container {
    height: 500px;
    overflow-y: scroll;
}

.content {
    height: 1000px;
}

.to-hide {
    min-height: 500px;
}

</style>

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
    
    <div class="sticky top-0 flex items-center p-2 text-aaron-50 gap-x-3 rounded-md text-sm leading-6 font-semibold group">
        <div class="rounded-full w-11 py-2 text-center hover:bg-aaron-400 group-hover:bg-aaron-400 bg-aaron-400">
            9
        </div>
        <span class="ms-3 text-base group-hover:text-aaron-50">Option nine</span>
    </div>


    <div class="md:p-4 sm:ml-80">
        <div class="p-4">
            <slot></slot>
        </div>
    </div>

</template>
