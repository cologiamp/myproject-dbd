<script setup>

import {onMounted, onUpdated, ref} from "vue";
import draggable from "vuedraggable";
import {PencilSquareIcon, TrashIcon} from '@heroicons/vue/24/outline';

const props = defineProps({
    topics: [],
    primaryTypes: [],
    objectives:[]
});

const emit = defineEmits(['getObjectiveToEdit', 'removeObjective', 'rearrangeTable'])
const data = ref(props.objectives);

onUpdated(()=>{
    console.log('onUpdated')
    // data.value = props.objectives; //necessary after saving edit/add button


    // console.log(JSON.stringify(data))
})

onMounted(() => {
    data.value = props.objectives;
})

function edit(id) {
    emit('getObjectiveToEdit', id)
}

function removeObjective(id) {
    emit('removeObjective', id)
}

function log() {
    console.log('log')
    // console.log(JSON.stringify(data.value));

    Object.entries(data.value).forEach(data => {
        const [key, value] = data;

        value.order = parseInt(key) + 1
    });
    console.log(JSON.stringify(data.value));

    emit('rearrangeTable', data.value)
}

function getIsPrimaryText(index) {
    return props.primaryTypes[parseInt(index)]
}

function getType(index) {
    return props.topics[parseInt(index)].name
}

function getObjective(topicIndex, objIndex) {
    return props.topics[parseInt(topicIndex)]['objectives'][parseInt(objIndex)]
}

function getWhatFor(topicIndex, whatForIndex) {
    // if(parseInt(whatForIndex) === 99) {
    //     return element.objective_custom
    // }
    return props.topics[parseInt(topicIndex)]['what_for'][parseInt(whatForIndex)]
}

</script>

<template>
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-300">
                <thead class="thead-dark bg-aaron-700">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold sm:pl-0">Type</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold">Topic</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold">Objective</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold">What for?</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                        <span class="sr-only">Edit</span>
                    </th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
                </thead>
                <draggable v-model="data" tag="tbody" item-key="name" @change="log">
                    <template #item="{ element }">
                        <tr>
                            <td scope="row" class="text-sm py-4 pl-4 pr-3 sm:pl-0">{{getIsPrimaryText(element.is_primary)}}</td>
                            <td class="text-sm px-3 py-4">{{ getType(element.type) }}</td>
                            <!-- element.objective === 'Other' ? element.objective_custom : element.objective -->
                            <td class="text-sm px-3 py-4">{{ getObjective(element.type, element.objective) }}</td>
                            <!-- element.what_for === 'Other' ? element.what_for_custom : element.what_for-->
                            <td class="text-sm px-3 py-4">{{ getWhatFor(element.type, element.what_for) }}</td>
                            <td class="relative text-sm py-4 pl-3 pr-4 text-right sm:pr-0">
                                <a href="#" @click="edit(element.id)" class="hover:text-indigo-900">
                                    <PencilSquareIcon class="w-5 h-5"></PencilSquareIcon>
                                </a>
                            </td>
                            <td class="relative text-sm py-4 pl-3 pr-4 text-right sm:pr-0">
                                <a href="#" @click="removeObjective(element.id)" class="hover:text-indigo-900">
                                    <TrashIcon class="w-5 h-5"></TrashIcon>
                                </a>
                            </td>
                        </tr>
                    </template>
                </draggable>
            </table>
            </div>
        </div>
        <rawDisplayer class="col-3" :value="list" title="List" />
    </div>
</template>

<style scoped>
.buttons {
    margin-top: 35px;
}
</style>
