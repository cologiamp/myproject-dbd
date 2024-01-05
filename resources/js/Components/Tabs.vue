<script setup>
import { ref, defineProps, provide } from "vue"

const props = defineProps({
    tabTitles: {
        type: Object,
        required: true
    }
});


const findTabKey = (obj, fn) =>
  Object.keys(obj).find(key => fn(obj[key], key, obj));

const selectedTabId = ref(findTabKey(props.tabTitles, x => x.current == true));

function tabsClick(index, tab) {
    selectedTabId.value = index

    Object.keys(props.tabTitles).forEach(key => {
        props.tabTitles[key].current = false;
    });

    tab.current = true;
}

provide("selectedTabId", selectedTabId);

</script>

<template>
    <div class="tabs">
        <ul class="tabs__header flex flex-wrap items-start whitespace-nowrap font-medium text-center text-gray-500 border-b border-[#5866CF] dark:text-gray-400 dark:border-gray-700 mb-6">
            <li v-for="(tab, index) in props.tabTitles" 
                :key="tab.name"
                :id="index"
                @click="tabsClick(index, tab)"
                class="list-item !w-auto"
                >
                <p class="text-white p-4">{{ tab.name }}</p>
                <!-- tab progress bar -->
                <div v-if="tab.current" class="flex w-full h-1.5 bg-gray-200 overflow-hidden dark:bg-gray-700" role="progressbar">
                    <div :class="tab.progress < 100 ? 'bg-blue-600' : 'bg-green-400'" :style="{width: tab.progress + '%'}"></div>
                </div>
            </li>
        </ul>
        <slot></slot>
    </div>
</template>

<style scoped>
</style>