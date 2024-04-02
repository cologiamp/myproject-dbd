<script setup>

import {onMounted, ref} from "vue";
import draggable from "vuedraggable";
import {PencilSquareIcon, TrashIcon, ArrowsUpDownIcon} from '@heroicons/vue/24/outline';

const props = defineProps({
    types: [],
    actions:[]
});

const emit = defineEmits(['getActionToEdit', 'removeAction', 'reOrderActions'])
const data = ref(props.actions);

onMounted(() => {
    data.value = props.actions;
})

function edit(id) {
    emit('getActionToEdit', id)
}

function removeAction(id) {
    emit('removeAction', id)
}

function updateOrder() {
    Object.entries(data.value).forEach(data => {
        const [key, value] = data;

        value.order = parseInt(key) + 1
    });

    emit('reOrderActions', data.value)
}

function getType(index) {
    return props.types[parseInt(index)] ? props.types[parseInt(index)] : ''
}

</script>

<template>
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-500">
                <thead class="bg-aaron-700">
                <tr>
                    <th scope="col" class="p-3 w-10"></th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold w-4/5">Call to Action</th>
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
                            <td class="text-sm px-3 py-4">{{ element.call_to_action === 99 ? element.call_to_action_custom : getType(element.call_to_action) }}</td>
                            <td class="relative text-sm py-4 pl-3 pr-4 text-right sm:pr-0">
                                <a href="#" @click="edit(element.id)">
                                    <PencilSquareIcon class="w-5 h-5"></PencilSquareIcon>
                                </a>
                            </td>
                            <td class="relative text-sm py-4 pl-3 pr-4 text-right sm:pr-0">
                                <a href="#" @click="removeAction(element.id)">
                                    <TrashIcon class="w-5 h-5"></TrashIcon>
                                </a>
                            </td>
                        </tr>
                    </template>
                </draggable>
                <tbody v-else>
                    <tr class="text-center">
                        <td colspan="7" class="text-sm px-3 py-4 text-center">
                            No call to actions data.
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
