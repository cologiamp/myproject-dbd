<script setup>
import { ref, provide, onBeforeMount, inject } from "vue"

import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    titles: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['setOnloadKey']);
const initialDropdownKey = inject("onloadKey");

const selectedMenu = ref(props.titles[1].name);
const selectedMenuId = ref(1);

function itemsClick(index, tab) {
    selectedMenu.value = tab.name
    selectedMenuId.value = index

    Object.keys(props.titles).forEach(key => {
        props.titles[key].current = false;
    });

    tab.current = true;
}

provide("selectedMenuId", selectedMenuId);

onBeforeMount(() => {
    // Emit function from parent component to set selected section onload
    emit('setOnloadKey', props.titles, x => x.current == true)
    selectedMenuId.value = initialDropdownKey.value
    selectedMenu.value = props.titles[selectedMenuId.value].name
});

</script>

<template>
    <Menu as="div" class="relative inline-block w-full text-left mb-8 px-7">
      <div>
        <MenuButton class="inline-flex w-full justify-between gap-x-1.5 rounded-md bg-aaron-700 ring-aaron-600 text-white p-3 text-sm font-semibold shadow-sm ring-1 ring-inset hover:bg-aaron-600 h-11">
          {{ selectedMenu }}
          <ChevronDownIcon class="-mr-1 h-5 w-5 text-white" aria-hidden="true" />
        </MenuButton>
      </div>

      <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
        <MenuItems class="z-[600] absolute w-full left-0 z-10 mt-2 origin-top-right rounded-md border-aaron-300 py-1.5 bg-aaron-950 ring-aaron-300 shadow-lg ring-1 ring-opacity-5 focus:outline-none">
          <div class="py-1">
            <MenuItem v-for="(tab, index) in titles" v-bind:key="index" @click="itemsClick(index, tab)">
                <a href="#" :class="[tab.current ? 'bg-aaron-700 text-white' : 'text-aaron-50', 'block px-4 py-2 text-sm']">
                    {{ tab.name }}
                </a>
            </MenuItem>
          </div>
        </MenuItems>
      </transition>
    </Menu>
    <slot></slot>
  </template>
