<script setup>
import {CheckIcon, ExclamationTriangleIcon } from "@heroicons/vue/24/solid/index.js";
import { ref } from "vue";
const saving = ref(1);
const percent = ref(5);

let index = 0;
const states = [5, 25, 50, 75, 100]

const testSavingSpinner = () => {
    // saving has started
    saving.value = 2

    // make animation of progress play for 2 seconds
    const intervalId = setInterval(() => {
        index++
        if (index < states.length) {
            percent.value = states[index]
        } else {
            clearInterval(intervalId)
            index = 0
            percent.value = states[0]
            saving.value = 1
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

const circumferance = 15 * 2 * Math.PI
</script>

<template>
    <div class="flex flex-row items-center gap-4">
        <button
            @click="testSavingSpinner"
            class="bg-blue-600 px-2 py-1 rounded-md hover:scale-105 duration-300"
        >
            Test
        </button>
        <div v-if="saving == 2" class="flex flex-row items-center gap-2">
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
                    class="text-[#00B3DD] duration-300"
                    stroke-width="5"
                    :stroke-dasharray="circumferance"
                    :stroke-dashoffset="circumferance - percent / 100 * circumferance"
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
            v-else-if="saving === 1"
            class="flex flex-row items-center gap-2"
        >
            <p>Saved</p>
            <div
                class="bg-[#00B3DD] rounded-full p-2"
            >
                <CheckIcon class="w-5 h-5 text-[#0B0F28]" />
            </div>
        </div>
        <ExclamationTriangleIcon v-else class="text-red-500 w-8 h-8" />
    </div>
</template>
