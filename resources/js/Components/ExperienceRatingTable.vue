<script setup>
const props = defineProps({
    stepFormData: [],
    enumItem: []
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
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold sm:pl-3 w-[35%]">
                    <!-- Rating title goes here -->
                    <slot></slot>
                </th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-center w-[21.5%]">No experience</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold w-[21.5%]">Some knowledge including risks</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold w-[21.5%]">Good experience of buying/selling</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(experience, index) in props.stepFormData" class="even:bg-aaron-950">
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-3">{{ props.enumItem[index] }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                    <input @change="onChangeRating()" v-model="props.stepFormData[index].value"
                           :checked="experience.value === 1"
                           type="radio" id="1" :value="1"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                    <input @change="onChangeRating()" v-model="props.stepFormData[index].value"
                           :checked="experience.value === 2"
                           type="radio" id="2" :value="2"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                    <input @change="onChangeRating()" v-model="props.stepFormData[index].value"
                           :checked="experience.value === 3"
                           type="radio" id="3" :value="3"
                           class="h-4 w-4 border-gray-300 text-aaron-700 focus:ring-aaron-700" />
                </td>
            </tr>
        </tbody>
    </table>
</template>
