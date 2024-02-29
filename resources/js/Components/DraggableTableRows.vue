<script setup>

import {ref, onUpdated, watch} from "vue";
import draggable from "vuedraggable";
import {autoS, autosaveT} from "@/autosave.js";
import {useForm} from "laravel-precognition-vue-inertia";

const props = defineProps({
    objectives:[]
});

const emit = defineEmits(['autosaveStateChange'])

watch(autoS,(newValue,oldValue) => {
    emit('autosaveStateChange',newValue)
})

const data = ref(props.objectives);

onUpdated(()=>{
    alert('yes')
    autosaveLocally()
})

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
                </tr>
                </thead>
                <draggable v-model="data" tag="tbody" item-key="name">
                    <template #item="{ element }">
                        <tr>
                            <td scope="row" class="text-sm py-4 pl-4 pr-3 sm:pl-0">{{ element.is_primary }}</td>
                            <td class="text-sm px-3 py-4">{{ element.type }}</td>
                            <td class="text-sm px-3 py-4">{{ element.objective }}</td>
                            <td class="text-sm px-3 py-4">{{ element.what_for }}</td>
                            <td class="relative text-sm py-4 pl-3 pr-4 text-right sm:pr-0">
                                <a href="#" class="hover:text-indigo-900">
                                    Edit<span class="sr-only"></span>
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
