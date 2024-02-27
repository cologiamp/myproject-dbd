<script setup>
import {defineAsyncComponent, inject, watch} from "vue";

const emit = defineEmits(['autoSaveUp'])

const props = defineProps({
    item: {
        type: Object,
        default: {
            'name' : 'Section Content',
            'current': true,
            'renderable': 'Component',
            'dynamicData': null
        }
    },
    tabIndex: {
        type: String
    },
    sectionIndex: {
        type: String
    },
    tabName: {
        type: String
    }
});

function dynamicComponent(component){
    // For Expenditure sections
    if (component.includes('Expenditure')) {
       component = 'Expenditure'
    }

    return defineAsyncComponent(() => import(`../DynamicForms/${component}.vue`));
}

// need to send the section
// the input fields
const selectedSectionId = inject("selectedSectionId");

</script>

<template>
    <div class="tab-content" v-show="sectionIndex == selectedSectionId">
        <component @autosave-state-change="( n ) => $emit('autoSaveUp', n)"
                   :is="dynamicComponent(item.renderable)"
                   :formData="item.dynamicData"
                   :sectionIndex="sectionIndex"
        />
    </div>
</template>

<style scoped>
</style>
