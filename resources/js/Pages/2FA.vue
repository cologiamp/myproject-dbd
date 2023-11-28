<script setup>
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import HalfPageImage from '@/Components/HalfPageImage.vue';
import TwoFactorLoginForm from "@/Pages/Partials/TwoFactorLoginForm.vue";
const page = usePage();
defineProps({
    halfPageImage: String
});



</script>

<template>
    <Head title="Log in" />
    <div class="flex min-h-full flex-1">

        <HalfPageImage :image="halfPageImage" class="order-1"/>
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24 order-2">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">Set up 2-FA</h2>
                </div>

                <div class="mt-10">
                    <TwoFactorLoginForm :requires-confirmation="true" :show-qr-code="page.props.auth.user?.two_factor_enabled">

                    </TwoFactorLoginForm>
                </div>
            </div>
        </div>
    </div>
</template>
