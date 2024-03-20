<script setup>
import { ref, provide, onBeforeMount, inject } from "vue";
import {forceRefresh} from "@/calculateRiskAssesment.js";

const props = defineProps({
    tabTitles: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['setOnloadKey', 'testEmit']);
const initialTabKey = inject("onloadKey");
const selectedTabId = ref(1);
const cachedSectionForStep = ref(1);

let urlWithFragment = '';

function tabsClick(index, tab) {
    selectedTabId.value = index

    Object.keys(props.tabTitles).forEach(key => {
        props.tabTitles[key].current = false;
    });

    tab.current = true;

    const url = new URL(window.location);
    url.searchParams.set('step', index);
    url.searchParams.set('section', '1');

    window.history.pushState({}, null, url);

    if (tab.name === 'Summary') {
        forceRefresh.value += 1;
    }
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
        <ul class="tabs__header flex flex-wrap items-start whitespace-nowrap font-medium text-center text-gray-400 border-b border-gray-700 mb-8">
            <li v-for="(tab, index) in props.tabTitles"
                :key="tab.name"
                :id="index"
                @click="tabsClick(index, tab)"
                class="list-item !w-auto group cursor-pointer"
                >
                <p :class="tab.current ? 'text-white' : 'text-gray-500'" class="p-4 group-hover:text-white transition-all">{{ tab.name }}</p>


            </li>
        </ul>
        <slot></slot>
    </div>
</template>

<style scoped>
</style>
