<script setup>
import {computed, ref} from 'vue'
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';


import {defineAsyncComponent} from "vue";

function comp(componentName){
    return defineAsyncComponent(() => import(`../NavigationItems/${componentName}.vue`));
}


defineProps({
    title: String,
    breadcrumbs: Array,
});

const page = usePage();
const navigation = computed(() => page.props.navigation)

// const navigation = [
//    { name: 'Dashboard', href: '#', icon: HomeIcon, current: true },
//    { name: 'Team', href: '#', icon: UsersIcon, current: false },
//    { name: 'Projects', href: '#', icon: FolderIcon, current: false },
//    { name: 'Calendar', href: '#', icon: CalendarIcon, current: false },
//    { name: 'Documents', href: '#', icon: DocumentDuplicateIcon, current: false },
//    { name: 'Reports', href: '#', icon: ChartPieIcon, current: false },
// ]
//
const showingNavigationDropdown = ref(true);

import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import {
    Bars3Icon,
    Cog6ToothIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline'
import {
    EllipsisHorizontalIcon
} from '@heroicons/vue/20/solid'
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";

const userNavigation = [
    { name: 'Your profile', href: '/profile' },
    { name: 'Sign out', href: '/logout' },
]

const sidebarOpen = ref(false)

</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div>
            <TransitionRoot as="template" :show="sidebarOpen">
                <Dialog as="div" class="relative z-[600] lg:hidden" @close="sidebarOpen = false">
                    <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
                        <div class="fixed inset-0 bg-gray-900/80" />
                    </TransitionChild>

                    <div class="fixed inset-0 flex">
                        <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
                            <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
                                <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
                                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                                        <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                                            <span class="sr-only">Close sidebar</span>
                                            <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                        </button>
                                    </div>
                                </TransitionChild>
                                <!-- Sidebar component, swap this element with another sidebar if you like -->
                                <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-aaron-900 px-6 py-8">
                                    <div class="flex h-fit shrink-0 items-center mb-4">
                                        <AuthenticationCardLogo/>
                                    </div>
                                    <nav class="flex flex-1 flex-col">
                                        <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                            <li>
                                                <ul role="list" class="-mx-2 space-y-1">
                                                    <li v-for="item in navigation" :key="item.name">
                                                        <Link :href="item.href" :class="[item.current ? 'bg-blue-400 text-white' : 'text-gray-200 hover:text-indigo-600 hover:bg-gray-50', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']">
                                                            <component :is="comp(item.icon)" :class="[item.current ? 'text-indigo-600' : 'text-gray-200 group-hover:text-indigo-600', 'h-6 w-6 shrink-0']" aria-hidden="true" />
                                                            {{ item.name }}
                                                        </Link>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="mt-auto">
                                                <a href="#" class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-white hover:bg-gray-50 hover:text-indigo-600">
                                                    <Cog6ToothIcon class="h-6 w-6 shrink-0 text-white group-hover:text-indigo-600" aria-hidden="true" />
                                                    Settings
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </Dialog>
            </TransitionRoot>

            <!-- Static sidebar for desktop -->
            <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-52 xl:w-72 lg:flex-col">
                <!-- Sidebar component, swap this element with another sidebar if you like -->
                <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-aaron-900 px-6 pb-4">
                    <div class="flex h-16 shrink-0 items-center mt-12 mb-16">
                        <AuthenticationCardLogo/>
                    </div>
                    <nav class="flex flex-1 flex-col">
                        <ul role="list" class="flex flex-1 flex-col gap-y-7">
                            <li>
                                <ul role="list" class="-mx-2 space-y-1">
                                    <li v-for="item in navigation" :key="item.name">
                                        <Link :href="item.name === 'Fact-Find' || item.name === 'Investment Recommendation' ? item.href + '?step=1&section=1' : item.href" :class="[item.current ? 'bg-aaron-400 text-aaron-950' : 'text-aaron-50 hover:text-aaron-50 hover:bg-aaron-400', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']">
                                            <component :is="comp(item.icon)" :class="[item.current ? 'text-aaron-950' : 'text-aaron-50 hover:text-aaron-50 hover:bg-aaron-400', 'h-6 w-6 shrink-0']" aria-hidden="true" />
                                            {{ item.name }}
                                        </Link>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="lg:pl-52 xl:pl-72">
                <div class="sticky top-0 z-40 lg:mx-auto lg:max-w-7xl lg:px-8 bg-aaron-950 md:bg-transparent">
                    <div class="flex h-16 items-center gap-x-4 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-0 lg:shadow-none">
                        <button type="button" class="-m-2.5 p-2.5 text-gray-200 lg:hidden" @click="sidebarOpen = true">
                            <span class="sr-only">Open sidebar</span>
                            <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                        </button>


                        <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 justify-end">
                            <div class="flex items-center gap-x-4 lg:gap-x-6">
<!--
                                Profile dropdown -->
                                <Menu as="div" class="relative">
                                    <MenuButton class="-m-1.5 flex items-center p-1.5">
                                        <span class="hidden lg:flex lg:items-center">
                                           <EllipsisHorizontalIcon class="mr-2 h-5 w-5 text-gray-400" aria-hidden="true" />
                                        </span>
                                        <span class="sr-only">Open user menu</span>
                                        <img class="h-8 w-8 border-2 border-aaron-50 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />

                                    </MenuButton>
                                    <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                        <MenuItems class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                                            <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                                                <a :href="item.href" :class="[active ? 'bg-aaron-400' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']">{{ item.name }}</a>
                                            </MenuItem>
                                        </MenuItems>
                                    </transition>
                                </Menu>
                            </div>
                        </div>
                    </div>
                </div>
                <aside class="py-0">
                    <div class="mx-auto px-4 sm:px-6 lg:px-8 text-aaron-50">
                        <h3 class=" text-3xl font-thin ">
                            {{ title }}
                        </h3>
                        <div class="mx-auto pt-4 text-sm">

                            <ol  class="flex gap-x-0">
                                <li v-for="(breadcrumb, key) in breadcrumbs" :key="key">
                                    <span v-if="!breadcrumb.is_active">
                                        <a :href="breadcrumb.url" class="whitespace-pre underline">{{ breadcrumb.title }}</a> /&nbsp;
                                    </span>
                                    <span v-else>
                                          <span>{{ breadcrumb.title }}</span>
                                    </span>
                                </li>
                            </ol>
                        </div>
                        <div class="flex mx-auto pt-4 justify-end xl:mr-16 lg:mr-12 sm:mr-6 mt-[-30px]">
                            <slot name="buttons-section" />
                        </div>
                    </div>


                </aside>
                <main class="py-10">
                    <div class="mx-auto max-w-7xl md:px-4 lg:px-8 text-aaron-50">
                        <slot />
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
