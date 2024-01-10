<script setup>
import { ref, defineProps, provide } from "vue"

import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    titles: {
        type: Object,
        required: true
    }
});

const findTabKey = (obj, fn) =>
  Object.keys(obj).find(key => fn(obj[key], key, obj));

const selectedMenu = ref(props.titles[findTabKey(props.titles, x => x.current == true)]);
const selectedMenuId = ref(1);

function itemsClick(index, tab) {
    selectedMenu.value = tab
    selectedMenuId.value = index

    Object.keys(props.titles).forEach(key => {
        props.titles[key].current = false;
    });

    tab.current = true;
}

provide("selectedMenuId", selectedMenuId);

</script>
<template>
    <Menu as="div" class="relative inline-block w-full text-left mb-8 px-4">
      <div>
        <MenuButton class="inline-flex w-full justify-between gap-x-1.5 rounded-md bg-[#313FA7] ring-[#4654BE]-300 text-white px-3 py-2 text-sm font-semibold shadow-sm ring-1 ring-inset hover:bg-[#4654BE]">
          {{ selectedMenu.name }}
          <ChevronDownIcon class="-mr-1 h-5 w-5 text-white" aria-hidden="true" />
        </MenuButton>
      </div>
  
      <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
        <MenuItems class="absolute w-full left-0 z-10 mt-2 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
          <div class="py-1">
            <MenuItem v-for="(tab, index) in titles" v-bind:key="index" @click="itemsClick(index, tab)">
                <a href="#" :class="[tab.current ? 'bg-[#313FA7] text-white' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                    {{ tab.name }}
                </a>
            </MenuItem>
          </div>
        </MenuItems>
      </transition>
    </Menu>
    <slot></slot>
  </template>