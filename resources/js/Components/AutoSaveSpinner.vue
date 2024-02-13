<script setup>
import {CheckIcon, ExclamationTriangleIcon } from "@heroicons/vue/24/solid/index.js";
import {ref, watch} from "vue";


let index = 0;
let animationInterval;
const animationStates = [5, 25, 50, 75, 100]

const props = defineProps({
    autosave: Number
});

const circumferenceOffset = ref(5);

watch(() => props.autosave, newValue => {
    newValue === 2 ? animationCallback() : clearAnimationCallback()
})

const animationCallback = () => {

    circumferenceOffset.value = animationStates[0]

    animationInterval = setInterval(() => {
        index++
        if (index < animationStates.length) {
            circumferenceOffset.value = animationStates[index]
        } else {
            clearInterval(animationInterval)
            circumferenceOffset.value = animationStates[animationStates.length - 1]
            index = 0
        }
    }, 200)

}

const clearAnimationCallback = () => {
    clearInterval(animationInterval)
}

const circumference = 15 * 2 * Math.PI
</script>

<template>
    <div class="flex flex-row items-center gap-4">
        <div v-if="autosave === 2" class="flex flex-row items-center gap-2">
            <p>Saving&nbsp;</p>
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
                    :stroke-dashoffset="circumference - circumferenceOffset / 100 * circumference"
                    stroke-linecap="flat"
                    stroke="currentColor"
                    fill="transparent"
                    r="15"
                    cx="20"
                    cy="20"
                />
            </svg>
        </div>
        <div v-else-if="autosave === 1" class="flex flex-row items-center gap-2">
            <p>Saved&nbsp;</p>
            <div class="bg-aaron-400 rounded-full p-2">
                <CheckIcon class="w-6 h-6 text-aaron-950" />
            </div>
        </div>
        <ExclamationTriangleIcon v-else class="text-red-500 w-10 h-10" />
    </div>
</template>
