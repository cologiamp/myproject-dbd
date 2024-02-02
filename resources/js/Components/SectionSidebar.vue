<script setup>
import { ref, provide, inject, onBeforeMount } from "vue";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    sidebarItems: {
        type: Object,
        required: true
    },
    tabIndex: {
        type: String
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

    // store step and selectedSection on local session
    localStorage.setItem('step' + props.tabIndex + 'section', selectedSectionId.value)
}

provide("selectedSectionId", selectedSectionId);

onBeforeMount(() => {
    selectedSectionId.value = initialSectionKey.value
});

</script>

<template>

    <aside id="default-sidebar" class="h-1/4 w-full md:w-80 mb-8 sm:hidden md:block md:h-fit md:absolute md:mb-0" aria-label="Sidebar">
                <div class="md:px-3 py-4 overflow-y-auto bg-aaron-900 dark:bg-aaron-900 text-white">
            <ul class="font-medium">
                <li v-for="(item, index) in props.sidebarItems"
                    :key="item.name"
                    :id="index"
                    @click="sectionsClick(index, item)">
                    <Link :href="`?step=${ props.tabIndex }&section=${ index }`" >
                        <div class="flex items-center p-2 text-aaron-50 gap-x-3 text-sm leading-6 font-semibold group">
                          <div>
                              <div class="rounded-full w-11 h-11 py-2 text-center"
                                  :class="[item.current ? 'bg-aaron-400' : 'bg-aaron-950']">
                                      {{ index }}
                              </div>
                          </div>
                        <span class="ml-3 text-base">{{ item.name }}</span>
                    </div>
                    </Link>
                </li>
            </ul>
        </div>
    </aside>

    <div class="md:p-4 sm:ml-80">
        <div class="p-4">
            <slot></slot>
        </div>
    </div>

</template>
