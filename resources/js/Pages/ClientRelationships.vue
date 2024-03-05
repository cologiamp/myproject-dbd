<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormWell from "@/Components/FormWell.vue";
import {Link, router} from "@inertiajs/vue3";
import {
    QuestionMarkCircleIcon,
    LightBulbIcon,
    GlobeAsiaAustraliaIcon,
    BanknotesIcon,
    PlusCircleIcon
} from "@heroicons/vue/24/solid/index.js";
import Swal from "sweetalert2";
import {ref} from "vue";

const props = defineProps({
    title: String,
    breadcrumbs: Array,
    contentTitle: String,
    clientId: String,
    relationships: Array,
});

let selectedRelationship = null;

async function selectRelationship(selectedRelationship)
{
    let ref2 = await Swal.fire({
        title: "Complete fact-find and strategy report with: " + selectedRelationship.name + '? You cannot change this later.',
        showCancelButton: true,
        confirmButtonText: "Yes",
    }).then((result) => {
        return result.isConfirmed;
    });
    if(ref2 === true)
    {
        axios.post('/api/client/' + props.clientId + '/fact-find-together/' + selectedRelationship.io_id, {
            'relationship': selectedRelationship.relationship.name
        }).then(() => {
            router.visit('/client/' + props.clientId + '/dashboard');
        })
    }
    else{

    }

}


function proceedSolo(){
    Swal.fire({
        title: "Complete fact-find and strategy report solo. You cannot change this later.",
        showCancelButton: true,
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post('/api/client/' + props.clientId + '/fact-find-solo').then(() => {
                router.visit('/client/' + props.clientId + '/dashboard');
            })
        }
    });
}

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <form-well>
            <h3 class="text-aaron-50 text-2xl px-4 md:p-0">{{contentTitle}}</h3>
        </form-well>
        <div class="overflow-hidden bg-aaron-950 pt-5 rounded-lg ">
            <div class="bg-aaron-900 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-base font-semibold leading-6 text-aaron-50">This client has active relationships with other clients.</h3>
                    <h3 class="text-base font-semibold leading-6 text-aaron-50">Please select if you would like to complete a joint fact-find or continue as an individual</h3>
                    <div class="mt-5">
                        <div class="rounded-md text-aaron-50 px-6 py-5 sm:flex sm:items-start sm:justify-between">
                            <div class="mt-2 sm:col-span-3 sm:mt-0 md:pr-2">
                                <label for="relationship" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 sm:pb-2">Related Client</label>
                                <select v-model="selectedRelationship" id="relationship" name="relationship"  class="block rounded-md  w-full  border-0 py-1.5 bg-aaron-700 text-aaron-50 sm:max-w-md shadow-sm ring-1 ring-inset ring-aaron-600 focus:ring-2 focus:ring-inset focus:ring-red-300  sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                                    <option id="title" :value="null">-</option>
                                    <option :id="item.io_id" :value="item" v-for="item in relationships">{{ item.name }} ({{item.relationship.name}})</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-5 right-0 gap-4 flex">

                            <button type="button" @click="selectRelationship(selectedRelationship)"
                                    class="float-right inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Select Client Two
                            </button>
                            <button type="button" @click="proceedSolo"
                                    class=" mr-4 float-right inline-flex items-center gap-x-1.5 rounded-md bg-aaron-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                                Proceed without second client
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </AppLayout>
</template>
