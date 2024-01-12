<script setup>
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import { ref, watch } from 'vue';

const props = defineProps({
    filters: {
        type: Object,
        default: {}
    }
})

const search = ref(props.filters.search ?? "")
const select = ref(props.filters.select ?? 1)

const emits = defineEmits([
    "searchClients"
])

watch(search, value => {
    emits("searchClients", { search: value, select: select.value, searching: true })
})

watch(select, value => {
    emits("searchClients", { search: search.value, select: value, searching: false })
})

</script>

<template>
    <div class="flex flex-col gap-8 min-h-[650px]">
        <div class="w-full flex flex-wrap md:flex-row items-center justify-between">
            <p class="text-[24px] font-medium">Your Clients</p>
            <div class="flex flex-wrap gap-4 justify-end md:flex-row items-center md:gap-12 md:justify-normal">
                <div class="flex flex-row items-center justify-between border-b border-b-[rgba(255,255,255,0.4)] min-w-full md:min-w-[340px]">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search clients"
                        class="bg-transparent border-none focus:ring-0 py-2 px-0"
                    >
                    <MagnifyingGlassIcon class="w-5 h-5"/>
                </div>
                <select v-model="select" class="block w-fit float-right border-aaron-300 py-1.5 bg-aaron-950 ring-aaron-300 rounded-md text-aaron-50 shadow-sm focus:ring-aaron-400 sm:max-w-xs sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none min-w-[160px]">
                    <option :value="1" selected>Most recent</option>
                    <option :value="2" >Risk</option>
                    <option :value="3" >Fact Find</option>
                    <option :value="4" >Strategy</option>
                </select>
            </div>
        </div>
        <slot />
        <slot name="paginator" />
    </div>
</template>
