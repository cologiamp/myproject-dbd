<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FormWell from "@/Components/FormWell.vue";
import {Link} from "@inertiajs/vue3";


defineProps({
    title: String,
    breadcrumbs: Array,
});

function changeSection(section)
{
    //axios.get();
}

function changeStep(section,step)
{

}

</script>

<template>
    <AppLayout :title="title" :breadcrumbs="breadcrumbs">
        <div class="sections flex flex-col">
<!--            Chore: Tab component with sections as tabs-->
                <div class="">
                    <PrimaryButton class="w-[50px]"  @click="changeSection(1)">1</PrimaryButton>
                    <PrimaryButton class="w-[50px]" @click="changeSection(2)">2</PrimaryButton>
                    <PrimaryButton class="w-[50px]" @click="changeSection(3)">3</PrimaryButton>
                    <PrimaryButton class="w-[50px]" @click="changeSection(4)">4</PrimaryButton>
                    <PrimaryButton class="w-[50px]" @click="changeSection(5)">5</PrimaryButton>
                </div>
            <div class="steps flex flex-col pt-4">
<!--            Chore: Steps component with steps inside of tabs-->
                <PrimaryButton class="w-[50px]" @click="changeStep(1)">1</PrimaryButton>
                <PrimaryButton class="w-[50px]" @click="changeStep(2)">2</PrimaryButton>
                <PrimaryButton class="w-[50px]" @click="changeStep(3)">3</PrimaryButton>
                <PrimaryButton class="w-[50px]" @click="changeStep(4)">4</PrimaryButton>
                <PrimaryButton class="w-[50px]" @click="changeStep(5)">5</PrimaryButton>
            </div>
        </div>
        <form-well class="mt-8">
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
                <Link class="rounded-md bg-aaron-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-aaron-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500" v-if="selectedClient" as="button" type="button" :href='"client/" + selectedClient + "/dashboard"'>Select </link>
                <button v-else disabled class="disabled rounded-md bg-slate-300 text-slate-500 border-slate-200 disabled:shadow-none px-3 py-2 text-sm font-semibold shadow-sm">Select </button>
            </div>
        </form-well>
    </AppLayout>
</template>
