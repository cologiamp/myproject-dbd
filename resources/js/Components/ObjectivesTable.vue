<script setup>

import {onMounted, ref} from "vue";
import draggable from "vuedraggable";
import {PencilSquareIcon, TrashIcon, ArrowsUpDownIcon} from '@heroicons/vue/24/outline';

const props = defineProps({
    topics: [],
    primaryTypes: [],
    objectives:[]
});

const emit = defineEmits(['getObjectiveToEdit', 'removeObjective', 'reOrderObjectives'])
const data = ref(props.objectives);

onMounted(() => {
    data.value = props.objectives;
})

function edit(id) {
    emit('getObjectiveToEdit', id)
}

function removeObjective(id) {
    emit('removeObjective', id)
}

function updateOrder() {
    Object.entries(data.value).forEach(data => {
        const [key, value] = data;

        value.order = parseInt(key) + 1
    });

    emit('reOrderObjectives', data.value)
}

function getIsPrimaryText(index) {
    return props.primaryTypes[parseInt(index)]
}

function getType(index) {
    return props.topics[parseInt(index)] ? props.topics[parseInt(index)].name : ''
}

function getObjective(topicIndex, objIndex, objCustom) {
    let customText = ''

    if ((topicIndex === 2 && objIndex === 1) || topicIndex === 3 && [0,1].includes(objIndex)) {
        customText = props.topics[parseInt(topicIndex)]['objectives'][parseInt(objIndex)]
        return customText.replace('£XX', objCustom)
    }

    if ((topicIndex === 8 && [1,2].includes(objIndex))) {
        customText = props.topics[parseInt(topicIndex)]['objectives'][parseInt(objIndex)]
        return customText.replace('XX', objCustom)
    }

    return props.topics[parseInt(topicIndex)] ? props.topics[parseInt(topicIndex)]['objectives'][parseInt(objIndex)] : ''
}

function getWhatFor(topicIndex, whatForIndex, whatForCustom) {
    let customText = ''

    if ((topicIndex === 2 && [0,1].includes(whatForIndex))) {
        customText = props.topics[parseInt(topicIndex)]['what_for'][parseInt(whatForIndex)]
        return customText.replace('XX', whatForCustom)
    }
    return props.topics[parseInt(topicIndex)] ? props.topics[parseInt(topicIndex)]['what_for'][parseInt(whatForIndex)] : ''
}

</script>

<template>
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-500">
                <thead class="bg-aaron-700">
                <tr>
                    <th scope="col" class="p-3"></th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold">Type</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold">Topic</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold">Objective</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold">What for?</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0"></th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0"></th>
                </tr>
                </thead>
                <draggable v-if="data.length > 0" v-model="data" tag="tbody" item-key="name" @change="updateOrder" class="divide-y divide-gray-500">
                    <template #item="{ element }">
                        <tr>
                            <td class="px-3 py-4">
                                <a href="#" class="hover:text-indigo-900">
                                    <ArrowsUpDownIcon class="w-5 h-5"></ArrowsUpDownIcon>
                                </a>
                            </td>
                            <td scope="row" class="text-sm px-3 py-4">{{getIsPrimaryText(element.is_primary)}}</td>
                            <td class="text-sm px-3 py-4">{{ getType(element.type) }}</td>
                            <td class="text-sm px-3 py-4">{{ element.objective === 99 ? element.objective_custom : getObjective(element.type, element.objective, element.objective_custom) }}</td>
                            <td class="text-sm px-3 py-4">{{ element.what_for === 99 ? element.what_for_custom : getWhatFor(element.type, element.what_for, element.what_for_custom) }}</td>
                            <td class="relative text-sm py-4 pl-3 pr-4 text-right sm:pr-0">
                                <a href="#" @click="edit(element.id)">
                                    <PencilSquareIcon class="w-5 h-5"></PencilSquareIcon>
                                </a>
                            </td>
                            <td class="relative text-sm py-4 pl-3 pr-4 text-right sm:pr-0">
                                <a href="#" @click="removeObjective(element.id)">
                                    <TrashIcon class="w-5 h-5"></TrashIcon>
                                </a>
                            </td>
                        </tr>
                    </template>
                </draggable>
                <tbody v-else>
                    <tr class="text-center">
                        <td colspan="7" class="text-sm px-3 py-4 text-center">
                            No objectives data.
                        </td>
                    </tr>
                    </tbody>
            </table>
            </div>
        </div>
        <rawDisplayer class="col-3" :value="list" title="List" />
    </div>
</template>

<style scoped>
.grabbing * {
    cursor: grabbing;
}
</style>
