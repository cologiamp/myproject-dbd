<script setup>
import VueDatePicker from "@vuepic/vue-datepicker";
import '@vuepic/vue-datepicker/dist/main.css';

import { onMounted, onUpdated } from "vue";

const props = defineProps({
    name: String,
    stepForm: Object,
    index: Number
});

let tempDate = props.stepForm[props.index][props.name]

const handleMonthYear = ({ instance, month, year },key) => {
    let tmp = tempDate.split('-');

    if(Array.isArray(tmp) && tmp.length > 1)
    {
        props.stepForm[props.index][props.name] = (year) + '-' + (month + 1) + '-' + tmp[2];
    }
    // autosaveLocally()
}

const handleDate = (date,key) => {
    let tmp = tempDate.split('-');

    if(Array.isArray(tmp) && tmp.length > 1)
    {
        console.log(tmp[0] + '-' + tmp[1] + '-' + date.getDate());
        props.stepForm[props.index][props.name] = tmp[0] + '-' + tmp[1] + '-' + date.getDate();
    }

    //use an emit to call a function from parent to update stepForm date value
    // autosaveLocally()
}

function saveDate(value,key){
    stepForm[key].date_of_birth = value;
    // autosaveLocally()
}



onMounted(()=> {
    // console.log(JSON.stringify(props.stepForm[props.index][props.name]));
})

onUpdated(()=> {
    alert(props.stepForm[props.index][props.name]);
})

</script>

<template>
    <VueDatePicker text-input :enable-time-picker="false"
       @date-update="handleDate($event, key)"
       @update-month-year="handleMonthYear($event,key)"
       class="aaron-datepicker ring-aaron-600"
       dark utc format="dd/MM/yyyy"
       :name="props.name" :id="props.name"
       placeholder="dd/mm/yyyy"/>
</template>

<style scoped>

</style>
