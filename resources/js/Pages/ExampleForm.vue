<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormWell from "@/Components/FormWell.vue";
import {useForm} from 'laravel-precognition-vue-inertia';
import SavingSpinner from "@/Components/SavingSpinner.vue";
import {useThrottleFn} from '@vueuse/core'
import {ref} from "vue";

let autoS = ref(1);

const autosaveT = useThrottleFn(()=>{
    autosave()
},2000)


const props = defineProps({
    title: String,
    breadcrumbs: Array,
    step: Number,
    section: String,
    formData: {
        type: Object,
        default: {
            enums: {
                titles: []
            },
            model: {
                first_name: null,
                last_name: null,
                title: null,
            },
            submit_method: 'post',
            submit_url: '/',
        },
    },
    progress: Number,
    create: true,
    errors: Object,
});

function autosave(){
    autoS.value = 2;
    stepForm.transform((data)=>({
        ...data,
        autosave: true,
        section: "1-5",
    })).put(props.formData.submit_url,
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                //Autosave actually takes a fraction of a second, but we delay to 1s to make it clear to user it actually happened
                setTimeout(() => autoS.value = 1,1000);
            },
            onError:() => {
                console.log(props.errors)
                //Chore: Make this work
                setTimeout(() => autoS.value = 3,1000);
            }
        }
    );
}



const stepForm = useForm(props.formData.submit_method, props.formData.submit_url,{
    first_name: props.formData.model.first_name,
    last_name: props.formData.model.last_name,
    title: props.formData.model.title
})

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <form-well :notop="true" class="mt-8">
            <template #loading>
                <div class="flex">
                    <saving-spinner :saving="autoS"></saving-spinner>
                </div>
            </template>
            <div class="form-row flex-1">
                <div class="md:grid  md:grid-cols-6 md:items-start">
                    <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                        <label for="title" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Title</label>
                        <select @change="autosaveT" v-model="stepForm.title" id="unit" name="title"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-950 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <option id="title" :value="null">-</option>
                            <option :id="id" :value="id" v-for="(title, id) in formData.enums.titles">{{ title }}</option>
                        </select>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.title">{{ stepForm.errors.title }}</p>
                    </div>


                   <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                       <label for="first_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> First Name </label>
                       <div class="flex shadow-sm rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveT" v-model="stepForm.first_name" type="text" name="first_name" id="first_name"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="First Name" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.first_name">{{ stepForm.errors.first_name }}</p>
                    </div>

                    <div class="mt-2 md:mt-0 md:pr-2 md:col-span-3">
                        <label for="last_name" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 mt-2 md:mt-0  sm:pb-2"> Last Name </label>
                        <div class="flex shadow-sm  rounded-md  focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-300 sm:max-w-md">
                            <input @change="autosaveT" v-model="stepForm.last_name" type="text" name="last_name" id="last_name"  class="block ring-1 ring-inset ring-aaron-500 flex-1 border-0 rounded-md  bg-aaron-950 py-1.5 pl-2 text-aaron-50 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" placeholder="Last Name" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" v-if="stepForm.errors && stepForm.errors.last_name">{{ stepForm.errors.last_name }}</p>
                    </div>

                </div>
            </div>
        </form-well>
    </AppLayout>
</template>
