<script setup>
import { ref, provide, inject, onBeforeMount } from "vue";

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

//Dropdown Ignacio
const currentSelectedSection = ref(1);
const menuShow = ref(true);
const formShow = ref(true);
//const isDropped = ref(true);
const toggleDropdown = (index) => {

    console.log("Clicked: " + index);
    console.log("currentSelectedSection: " + currentSelectedSection.value);
    console.log("Menu Show: " + menuShow.value);
    console.log("Form Show: " + formShow.value);
    //document.getElementById("txt").classList.toggle("max-h-[300px]");
    //document.getElementById("txt").classList.toggle("max-h-0");

    if (currentSelectedSection.value !== index) {
        menuShow.value = false;
        formShow.value = true;
    } else {
        if(formShow.value) {
            menuShow.value = true;
            //isDropped.value = !isDropped.value;
            formShow.value = false;
        } else {
            menuShow.value = false;
            formShow.value = true;
        }
    }

    currentSelectedSection.value = index;

    console.log("Clicked: " + index);
    console.log("currentSelectedSection: " + currentSelectedSection.value);
    console.log("Menu Show: " + menuShow.value);
    console.log("Form Show: " + formShow.value);

}
</script>

<template>

    <aside id="default-sidebar" class="sticky top-36 z-[400] h-1/4 w-full md:w-80 mb-8 sm:hidden md:block md:h-fit md:absolute md:mb-0" aria-label="Sidebar">
        <div class="md:px-3 py-4 overflow-y-auto bg-aaron-900 dark:bg-aaron-900 text-white">
                <ul class="font-medium">
                    <li v-for="(item, index) in props.sidebarItems"
                        v-bind:class="{'hidden': !menuShow && index !== currentSelectedSection, 'block': menuShow}" v-on:click="toggleDropdown(index)" ref="btnMenuRef"
                        :key="item.name"
                        :id="index"
                        @click="sectionsClick(index, item)">
                        <div class="flex items-center p-2 text-aaron-50 gap-x-3 rounded-md text-sm leading-6 font-semibold group">
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

        <div v-bind:class="{'hidden': !formShow, 'block': formShow }" class="md:p-4 sm:ml-80">
            <div class="p-4">
                <slot></slot>
            </div>
        </div>

</template>
