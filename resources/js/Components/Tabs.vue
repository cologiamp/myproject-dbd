<script setup>
import { ref, provide, onBeforeMount, inject } from "vue"

const props = defineProps({
    tabTitles: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['setOnloadKey', 'testEmit']);
const initialTabKey = inject("onloadKey");
const selectedTabId = ref(1);

function tabsClick(index, tab) {
    selectedTabId.value = index

    Object.keys(props.tabTitles).forEach(key => {
        props.tabTitles[key].current = false;
    });

    tab.current = true;
}

provide("selectedTabId", selectedTabId);

onBeforeMount(() => {
    // Emit function from parent component to set selected section onload
    emit('setOnloadKey', props.tabTitles, x => x.current == true);
    selectedTabId.value = initialTabKey.value;
});

</script>

<template>
    <div class="tabs">
        <ul class="tabs__header flex flex-wrap items-start whitespace-nowrap font-medium text-center text-gray-500 border-b border-aaron-500 dark:text-gray-400 dark:border-gray-700 mb-8">
            <li v-for="(tab, index) in props.tabTitles"
                :key="tab.name"
                :id="index"
                @click="tabsClick(index, tab)"
                class="list-item !w-auto cursor-pointer group"
                >
                <p :class="tab.current ? 'text-white' : 'text-gray-500'" class="p-4 group-hover:text-white">{{ tab.name }}</p>
                <!-- tab progress bar -->
                <div class="flex w-full h-1.5 overflow-hidden group-hover:bg-aaron-400" :class="[tab.current ? 'bg-aaron-400 dark:bg-aaron-400' : '']" role="progressbar">
                </div>
            </li>
        </ul>
        <slot></slot>
    </div>
</template>

<style scoped>
</style>
