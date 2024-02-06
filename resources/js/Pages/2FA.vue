<script setup>
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import HalfPageImage from '@/Components/HalfPageImage.vue';
import TwoFactorLoginForm from "@/Pages/Partials/TwoFactorLoginForm.vue";
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import LoginAnimatedSection from "@/Pages/Partials/LoginAnimatedSection.vue";
import { onMounted } from 'vue';

const page = usePage();
defineProps({
    halfPageImage: String
});

function animateLeftSideElems() {
    document.getElementById('login-form').classList.remove('opacity-0');
    document.getElementById('login-form').classList.add('animate-fade-from-bottom-50');
}

onMounted( ()=> {
    window.scrollTo(0, 0);
    animateLeftSideElems();
});

</script>

<template>
    <Head title="Log in" />
    <div class="flex min-h-full flex-1">
        <div class="w-full md:w-1/2">
            <div class="h-screen flex justify-center">
                <div class="flex flex-col">
                    <AuthenticationCardLogo id="logo" class="max-w-2xl mt-10" />
                    <div id="login-form" class="flex flex-1 flex-col justify-center px-4 py-4 sm:px-6 lg:flex-none order-2 opacity-0">
                        <div class="mx-auto w-full max-w-sm lg:w-96">
                            <div>
                                <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-white">Set up 2-FA</h2>
                            </div>

                            <div class="mt-10 w-[400px]">
                                <TwoFactorLoginForm :requires-confirmation="true" :show-qr-code="page.props.auth.user?.two_factor_enabled">

                                </TwoFactorLoginForm>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden md:block w-1/2">
            <div class="h-screen flex justify-center">
                <LoginAnimatedSection></LoginAnimatedSection>
            </div>
        </div>
    </div>
</template>
