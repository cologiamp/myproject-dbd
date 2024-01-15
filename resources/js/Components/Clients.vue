<script setup>
import Client from './Client.vue';
import {router} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {ref} from "vue";

let selectedClient = ref(null);

defineProps({
    clients: Array,
})


function redirectToSelectedClient()
{
    router.visit('/client/' + selectedClient.value + '/dashboard');
}

function selectClient(client)
{
    selectedClient.value = client.io_id;
    axios.post('/client/' + selectedClient.value + '/sync').then(() => {
        redirectToSelectedClient()
    }).catch(error => {
        Swal.fire({
            title: 'Warning - data overwrite detected!',
            text: error.response.data.message,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Overwrite",
            denyButtonText: `Proceed without overwriting`
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('/client/' + selectedClient.value + '/sync',{
                    'force': true
                }).then(() => {
                    redirectToSelectedClient()
                }).catch(error => {
                    Swal.fire({
                        title: 'Error: Something failed. Please try again later.',
                        text: error.response.data.message,
                    })
                });
            } else if (result.isDenied) {
                redirectToSelectedClient()
            }
        });
    });
}
</script>

<template>
    <!-- clients lozenge -->
    <div class="w-full h-full grid grid-cols-2 md:grid-cols-3 gap-6 min-h-[420px]">
        <!-- client panel -->
        <Client
            v-for="(client, index) in clients"
            :key="index"
            :client="client"
            @click="selectClient(client)"
        />
    </div>
</template>
