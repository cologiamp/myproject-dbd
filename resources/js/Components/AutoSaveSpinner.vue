<script setup>
import {CheckIcon, ExclamationTriangleIcon } from "@heroicons/vue/24/solid/index.js";
import {computed, onMounted, ref} from "vue";

const percent = computed(() => {
    return 5;
})

let index = 0;
const states = [5, 25, 50, 75, 100]


defineProps({
    autosave: {
        type: Number,
        default: 1,
    },
});



const testSavingSpinner = () => {


    // make animation of progress play for 2 seconds
    const intervalId = setInterval(() => {
        index++
        if (index < states.length) {
            percent.value = states[index]
        } else {
            clearInterval(intervalId)
            index = 0
            percent.value = states[0]
            autosave.value = 1
        }
        // for checking error state
        // if (index == 4) {
        //     clearInterval(intervalId)
        //     index = 0
        //     percent.value = states[0]
        //     saving.value = 3
        // }
    }, 300)

};

const circumference = 15 * 2 * Math.PI
</script>

<template>
    <div class="flex flex-row items-center gap-4">
        <div v-if="autosave === 2" class="flex flex-row items-center gap-2">
            <p>Saving</p>
            <svg class="w-10 h-10">
                <circle
                    class="text-transparent"
                    stroke-width="5"
                    stroke="currentColor"
                    fill="transparent"
                    r="15"
                    cx="20"
                    cy="20"
                />
                <circle
                    class="text-aaron-400 duration-300"
                    stroke-width="5"
                    :stroke-dasharray="circumference"
                    :stroke-dashoffset="circumference - percent / 100 * circumference"
                    stroke-linecap="flat"
                    stroke="currentColor"
                    fill="transparent"
                    r="15"
                    cx="20"
                    cy="20"
                />
            </svg>
        </div>
        <div
            v-else-if="autosave === 1"
            class="flex flex-row items-center gap-2"
        >
            <p>Saved</p>
            <div
                class="bg-aaron-400 rounded-full p-2"
            >
                <CheckIcon class="w-5 h-5 text-aaron-950" />
            </div>
        </div>
        <ExclamationTriangleIcon v-else class="text-red-500 w-8 h-8" />
    </div>
</template>
