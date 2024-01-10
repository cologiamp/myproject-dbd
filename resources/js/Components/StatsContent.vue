<script setup>
</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="w-full flex flex-row items-center justify-between">
            <p class="text-[24px] font-medium">Your Stats</p>
            <select class="block w-fit border-aaron-300 py-1.5 bg-aaron-950 ring-aaron-300 rounded-md text-aaron-50 shadow-sm focus:ring-aaron-400 sm:max-w-xs sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none min-w-[160px]">
                <option :value="1" selected>Last 7 days</option>
                <option :value="2" >Last month</option>
                <option :value="3" >Last 3 months</option>
                <option :value="4" >Last Year</option>
            </select>
        </div>
        <slot />
    </div>
</template>
