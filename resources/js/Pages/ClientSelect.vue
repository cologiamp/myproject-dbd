<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import {nextTick, ref} from "vue";
import FormWell from "@/Components/FormWell.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {Link, router} from '@inertiajs/vue3'

let selectedClient = ref(null);

defineProps({
    title: String,
    breadcrumbs: Array,
    clients: Array
});

function selectClient()
{
    axios.post('/client/' + selectedClient.value + '/sync').then(() => {
        alert('post');
        // router.get('/client/' + selectedClient.value + '/dashboard');
    }).catch(error => {
        alert('something went wrong');
    });
}


</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <form-well>
            <div class="form-row flex-1">
                <label for="client" class="block text-sm font-medium leading-6 text-aaron-50 sm:pt-1.5 required">Client</label>
                <div class="mt-2 sm:col-span-2 sm:mt-0 md:pr-2">
                    <select v-model="selectedClient" id="client" name="client"  class="block w-full  border-aaron-300 py-1.5 bg-aaron-950 ring-aaron-300 rounded-md text-aaron-50 shadow-sm focus:ring-aaron-400 sm:max-w-xs sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        <option id="client" :value="null">-</option>
                        <option :id="id" :value="client.io_id" v-for="(client, id) in clients">{{ client.name }}</option>
                    </select>
                </div>
            </div>
            <div class="button-holder">
                <button class="rounded-md bg-aaron-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-aaron-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500" v-if="selectedClient" type="button" @click="selectClient">Select </button>
                <button v-else disabled class="disabled rounded-md bg-slate-300 text-slate-500 border-slate-200 disabled:shadow-none px-3 py-2 text-sm font-semibold shadow-sm">Select </button>
            </div>
        </form-well>

    </AppLayout>
</template>
