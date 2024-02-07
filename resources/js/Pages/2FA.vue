<script setup>
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import HalfPageImage from '@/Components/HalfPageImage.vue';
import TwoFactorLoginForm from "@/Pages/Partials/TwoFactorLoginForm.vue";
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import LoginAnimatedSection from "@/Pages/Partials/LoginAnimatedSection.vue";
import { onMounted } from 'vue';
import {isMobile} from "@/mobileCheck.js";

const page = usePage();
defineProps({
    halfPageImage: String
});

function animateLeftSideElems() {
    document.getElementById('login-form').classList.remove('opacity-0');
    document.getElementById('login-form').classList.add('animate-fade-from-bottom-50');
}

onMounted( ()=> {
    if (isMobile()) {
        // no animations on mobile
        document.getElementById('login-form').classList.remove('opacity-0');
    } else {
        window.scrollTo(0, 0);
        animateLeftSideElems();
    }
});

</script>

<template>
    <Head title="Log in" />
    <div class="flex flex-1 bg-aaron-900 snap-start">
        <div class="w-full md:w-1/2">
            <div class="min-h-full md:flex md:justify-center flex flex-col">
                <div class="flex flex-col my-auto">
                    <AuthenticationCardLogo id="logo" class="max-w-2xl mx-auto mt-10" />
                    <div id="login-form" class="mt-10 w-fit md:w-96 mx-auto">
                        <div class="mx-auto w-fit max-w-sm md:w-96 px-8 mb-8">
                            <div>
                                <h2 class="text-2xl font-bold leading-9 tracking-tight text-white">Set up 2-FA</h2>
                            </div>

                            <div class="mt-10 md:w-[400px]">
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
