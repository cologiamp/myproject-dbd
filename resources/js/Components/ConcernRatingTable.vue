<script setup>
const props = defineProps({
    stepFormData: Array,
    enumItem: Array
});

const emit = defineEmits(['setRating'])

function onChangeRating() {
    emit('setRating', props.stepFormData)
}
</script>

<template>
    <table class="min-w-full divide-y divide-gray-300">
        <thead>
        <tr>
            <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-sm sm:pl-3 w-[5%]"></th>
            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-sm w-[19%]">Very unconcerned</th>
            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-sm w-[19%]">Unconcerned</th>
            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-sm w-[19%]">Neutral</th>
            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-sm w-[19%]">Concerned</th>
            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-sm w-[19%]">Very concerned</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(vol, index) in stepFormData" class="even:bg-aaron-950">
            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-3 w-1/2">{{ enumItem[index] }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-center">
                <input @change="onChangeRating()" v-model="stepFormData[index].value"
                       :checked="vol.value === 1"
                       type="radio" id="1" :value="1"
                       class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
            </td>
            <td class="whitespace-nowrap px-3 py-4 text-center">
                <input @change="onChangeRating()" v-model="stepFormData[index].value"
                       :checked="vol.value === 2"
                       type="radio" id="2" :value="2"
                       class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
            </td>
            <td class="whitespace-nowrap px-3 py-4 text-center">
                <input @change="onChangeRating()" v-model="stepFormData[index].value"
                       :checked="vol.value === 3"
                       type="radio" id="3" :value="3"
                       class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
            </td>
            <td class="whitespace-nowrap px-3 py-4 text-center">
                <input @change="onChangeRating()" v-model="stepFormData[index].value"
                       :checked="vol.value === 4"
                       type="radio" id="4" :value="4"
                       class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
            </td>
            <td class="whitespace-nowrap px-3 py-4 text-center">
                <input @change="onChangeRating()" v-model="stepFormData[index].value"
                       :checked="vol.value === 5"
                       type="radio" id="5" :value="5"
                       class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
            </td>
        </tr>
        </tbody>
    </table>
</template>
