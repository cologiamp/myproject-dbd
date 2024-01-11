<script setup>
import Stat from './Stat.vue';
const props = defineProps({
    stats: Array
})
</script>

<template>
    <!-- stats lozenge -->
    <div class="w-full h-full flex flex-row items-center bg-aaron-900 rounded-[20px]">
        <!-- stat panel -->
        <Stat
            v-for="(stat, index) in stats"
            :key="index"
            :stat="stat"
            :last="index === stats.length - 1"
        >
        </Stat>
    </div>
</template>
