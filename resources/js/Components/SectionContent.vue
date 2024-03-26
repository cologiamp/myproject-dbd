<script setup>
import {defineAsyncComponent, inject} from "vue";

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
    },
    sidebarItemsLength: {
        type: Number
    }
});

const expenditure_sections = ['BasicEssentialExpenditure', 'BasicQualityOfLivingExpenditure', 'NonEssentialOutgoingsExpenditure', 'LiabilityExpenditure', 'LumpSumExpenditure'];

function dynamicComponent(component){
    // For Expenditure sections
    if (expenditure_sections.includes(component)) {
       component = 'Expenditure'
    }

    return defineAsyncComponent(() => import(`../DynamicForms/${component}.vue`));
}

const selectedSectionId = inject("selectedSectionId");

</script>
<template>
    <div class="tab-content" v-show="sectionIndex == selectedSectionId">
        <component :is="dynamicComponent(item.renderable)"
           :formData="item.dynamicData"
           :sidebarItemsLength="sidebarItemsLength"
        />
    </div>
</template>

<style scoped>
</style>
