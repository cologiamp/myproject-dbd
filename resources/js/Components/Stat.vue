<script setup>
const props = defineProps({
    stat: Object,
    last: Boolean
})
</script>

<template>
    <div
        class="p-8 px-8 w-1/2 md:w-1/4 flex flex-row items-center justify-center min-h-[184px]"
        :class="last ? 'border-none' : 'md:border-r border-[rgba(255,255,255,0.4)]'"
    >
        <div class="flex flex-col items-start justify-between w-full h-full">
            <div class="flex flex-row items-center justify-between w-full h-full">
                <p class="text-lg md:text-[14px] font-medium">{{ stat.title }}</p>
                <p class="text-lg md:text-[14px] font-medium text-green-300">+2</p>
            </div>
            <p class="text-5xl md:text-[40px] font-medium">{{ stat.value }}</p>
        </div>
    </div>
</template>
