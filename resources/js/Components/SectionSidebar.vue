<script setup>
import { ref, provide, inject, onBeforeMount } from "vue";
import {Link, router} from '@inertiajs/vue3';
import {ArrowRightIcon} from "@heroicons/vue/24/solid/index.js";

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
    },
    tabIndex: {
        type: String
    }
});

const initialSectionKey = inject("onloadKey");
const selectedSectionId = ref(1);
const hide_progress = ['Investment Recommendations', 'Pension Recommendations'];

function sectionsClick(index, item) {
    // alert('happened');
    selectedSectionId.value = index

    Object.keys(props.sidebarItems).forEach(key => {
        props.sidebarItems[key].current = false;
    });

    item.current = true;
    // store step and selectedSection on local session
    // localStorage.setItem('step' + props.tabIndex + 'section', selectedSectionId.value)

    const url = new URL(window.location);
    url.searchParams.set('step', props.tabIndex);
    url.searchParams.set('section', selectedSectionId.value.toString());

    window.history.pushState({}, null, url);
}

provide("selectedSectionId", selectedSectionId);

onBeforeMount(() => {
    selectedSectionId.value = initialSectionKey.value

    const url = new URL(window.location);
    if(url.searchParams.get('step') == null)
    {
        url.searchParams.set('step', 1);
    }
    if(url.searchParams.get('section') == null)
    {
        url.searchParams.set('section', 1);
    }

    window.history.pushState({}, '', url);
});

const currentSelectedSection = ref(1);
const menuShow = ref(true);
const formShow = ref(true);

const toggleDropdown = (index) => {

    if(window.innerWidth < 768) {
        if (currentSelectedSection.value !== index) {
            menuShow.value = false;
            formShow.value = true;
        } else {
            if(formShow.value) {
                menuShow.value = true;
                formShow.value = false;
            } else {
                menuShow.value = false;
                formShow.value = true;
            }
        }
    }

    currentSelectedSection.value = index;
}

function nextTab(){
    const pathname = window.location.pathname;
    const search = window.location.search;

    let arr = search.split('&');
    let section = arr[1].slice(-1);
    let step = arr[0].slice(-1);

    if(section == Object.keys(props.sidebarItems).length)
    {
        router.visit(pathname + '?step=' + (parseInt(step) +1) + '&section=1' )
    }
    else{
        router.visit(pathname + '?step=' + step + '&section=' + (parseInt(section) + 1) )
    }
}

</script>



<template>

  <aside id="default-sidebar" class="sticky z-[400] md:top-auto h-1/4 w-full md:w-80 mb-8 sm:hidden md:block md:h-fit md:absolute md:mb-0" :class="!hide_progress.includes(props.tabName) ? 'top-48' : 'top-36'" aria-label="Sidebar">
      <div class="md:px-3 py-4 overflow-y-auto bg-aaron-900 dark:bg-aaron-900 text-white">
          <ul class="font-medium">
                  <li v-for="(item, index) in props.sidebarItems"
                      v-bind:class="{'hidden': !menuShow && index !== currentSelectedSection, 'block': menuShow}" ref="btnMenuRef"
                      :key="item.name"
                      :id="index"
                      @click="toggleDropdown(index); sectionsClick(index, item)" class="cursor-pointer">
                      <div class="grid grid-cols-4 items-center p-2 text-aaron-50 rounded-md text-base font-semibold group">
                          <div class="rounded-full w-11 h-11 py-3 text-center text-sm"
                               :class="[item.current ? 'bg-aaron-400' : 'bg-aaron-950']">
                              {{ index }}
                          </div>
                          <span class="col-span-3 text-base">{{ item.name != 'Pension Basic Details' ? item.name : 'Basic Details' }}</span>
                      </div>
                  </li>
              </ul>
      </div>
  </aside>

    <!-- DISPLAY ON START, HIDE ONSCROLL DOWN AND WHEN MENU IS STICKED -->
    <div class="hidden md:hidden">
        <div class="mb-4">
            <span>{{ props.tabName + ' progress: '+ props.progress + '%' }} </span>
        </div>
        <div class="flex w-full mb-16 h-2.5 overflow-hidden bg-gray-700 rounded-md">
            <div class="bg-aaron-400 w-[50%] rounded-r-md" />
        </div>
    </div>

    <div v-bind:class="{'hidden': !formShow, 'block': formShow }" class="md:p-4 mb-4 sm:ml-80">
        <div class="p-4">
            <slot></slot>
        </div>
        <button type="button" @click="nextTab"
                class="float-right mr-3 inline-flex items-center gap-x-1.5 rounded-md bg-aaron-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#0098bc] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Next <ArrowRightIcon class="w-6 h-6" />
        </button>
    </div>

</template>
