<script setup>
import {defineAsyncComponent, defineProps, inject} from "vue";


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
    sectionIndex: {
        type: String
    }
});

function dynamicComponent(component){
    return defineAsyncComponent(() => import(`../DynamicForms/${component}.vue`));
}
// need to send the section
// the input fields

const selectedSectionId = inject("selectedSectionId");

</script>

<template>
    <div class="tab-content h-full" v-show="sectionIndex == selectedSectionId">
        <component :is="dynamicComponent(item.renderable)" :formData="item.dynamicData" />
    </div>
</template>

<style scoped>
</style>
