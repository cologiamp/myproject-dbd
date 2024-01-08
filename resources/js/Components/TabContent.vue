<script setup>
import {defineAsyncComponent, defineProps, inject} from "vue";


const props = defineProps({
    item: {
        type: Object,
        default: {
            'name' : 'Section Content',
            'currrent': true
        }
    },
    sectionIndex: {
        type: String
    }
});

function dynamicComponent(component){
    console.log(component);
    return defineAsyncComponent(() => import(`../DynamicForms/${component}.vue`));
}

const selectedSectionId = inject("selectedSectionId");

</script>

<template>
    <div class="tab-content" v-show="sectionIndex == selectedSectionId">
        <component :is="dynamicComponent(item.renderable)" :formData="item.dynamicData" />
    </div>
</template>

<style scoped>
</style>
