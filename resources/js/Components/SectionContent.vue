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
    if ((parseInt(props.tabIndex) == 2 && parseInt(props.sectionIndex) >= 2) || component.includes('Expenditure')) {
       component = 'Expenditure'
    }
    if (props.tabName == 'Pension Recommendations') {
        if (parseInt(props.sectionIndex) == 1) {
            component = 'PensionBasicDetails'
        }
        //improve from here
        if (parseInt(props.sectionIndex) == 2) {
            component = 'PensionClientDetails'
        }
        if (parseInt(props.sectionIndex) == 3) {
            component = 'ExistingPensionPlans'
        }
        if (parseInt(props.sectionIndex) == 4) {
            component = 'NewContributions'
        }
        if (parseInt(props.sectionIndex) == 5) {
            component = 'AnnualAllowance'
        }
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
