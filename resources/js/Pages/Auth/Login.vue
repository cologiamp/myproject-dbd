<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import HalfPageImage from '@/Components/HalfPageImage.vue';
import LoginAnimatedSection from "@/Pages/Partials/LoginAnimatedSection.vue";
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { onMounted } from 'vue';

import {isMobile} from "@/mobileCheck.js";

defineProps({
    canResetPassword: Boolean,
    status: String,
    halfPageImage: String
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

function animateLeftSideElems() {
    setTimeout(() => {
        // animate adviserHub logo
        document.getElementById('logo').classList.remove('opacity-0');
        document.getElementById('logo').classList.add('animate-fade-from-bottom-50');
    }, "1500");

    setTimeout(() => {
        // animate login form
        document.getElementById('login-form').classList.remove('opacity-0');
        document.getElementById('login-form').classList.add('animate-fade-from-bottom-50');
    }, "2500");
}

onMounted( ()=> {
    if (isMobile()) {
        // no animations on mobile
        document.getElementById('logo').classList.remove('opacity-0');
        document.getElementById('login-form').classList.remove('opacity-0');
    } else {
        document.getElementById('round-elem-1').classList.add('animate-scale-inwards');
        document.getElementById('round-elem-2').classList.add('animate-scale-inwards');
        document.getElementById('round-elem-3').classList.add('animate-scale-inwards');
        document.getElementById('round-elem-4').classList.add('animate-scale-inwards');
        document.getElementById('round-elem-5').classList.add('animate-scale-inwards');
        document.getElementById('round-elem-6').classList.add('animate-scale-inwards');
        document.getElementById('round-elem-7').classList.add('animate-scale-inwards');
        document.getElementById('round-elem-8').classList.add('animate-scale-inwards');
        document.getElementById('round-elem-9').classList.add('animate-scale-inwards');
        document.getElementById('round-elem-10').classList.add('animate-scale-inwards');
        document.getElementById('round-elem-11').classList.add('animate-scale-inwards');
        document.getElementById('round-elem-12').classList.add('animate-scale-inwards');
        document.getElementById('round-elem-13').classList.add('animate-scale-inwards');
        document.getElementById('graph-elem-1').classList.add('animate-fade-from-bottom-30');
        document.getElementById('graph-elem-2').classList.add('animate-fade-from-bottom-30');
        animateLeftSideElems();
    }
});

</script>

<template>
    <Head title="Log in" />
    <div class="flex min-h-full md:overflow-y-hidden flex-1 bg-aaron-900">
        <div class="w-full md:w-1/2">
            <div class="h-full md:flex md:justify-center flex flex-col">
                <div class="flex flex-col my-auto">
                    <AuthenticationCardLogo id="logo" class="max-w-2xl mx-auto opacity-0"/>
                    <div id="login-form" class="mt-10 w-full md:w-96 md:mx-auto opacity-0">
                        <div class="bg-aaron-950 h-12 items-center block md:bg-transparent">
                            <h2 class="mt-8 text-2xl leading-9 tracking-tight text-white text-center pt-1 md:text-left">Sign in</h2>
                        </div>
                        <form @submit.prevent="submit" action="#" method="POST" class="space-y-6 p-8 md:p-0">
                            <div>
                                <InputLabel for="email" value="Email address" class="block text-sm font-medium leading-6 text-white" />
                                <div class="mt-2">
                                    <TextInput
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            class="block w-full h-11 text-white rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-aaron-500 bg-aaron-950 focus:ring-1 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"
                                            required
                                            autofocus
                                            autocomplete="email"
                                        />
                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>
                            </div>

                            <div>
                                <div class="mt-2">
                                    <InputLabel for="password" value="Password" class="block text-sm font-medium leading-6 text-white" />
                                    <TextInput
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        class="mt-1 block w-full h-11 text-white rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-aaron-500 bg-aaron-950 focus:ring-1 focus:ring-inset focus:ring-aaron-500 sm:text-sm sm:leading-6"
                                        required
                                        autocomplete="current-password"
                                    />
                                    <InputError class="mt-2" :message="form.errors.password" />

                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <Checkbox v-model:checked="form.remember" name="remember" class="h-4 w-4 rounded border-aaron-500 bg-aaron-950 text-teal-500 ring-aaron-500 focus:ring-aaron-500" />
                                    <label for="remember-me" class="ml-3 block text-sm leading-6 text-gray-100">Remember me</label>
                                </div>


                                <div class="text-sm leading-6">
                                    <Link v-if="canResetPassword" :href="route('password.request')" class="font-semibold text-teal-500 hover:text-teal-400">Forgot password?</Link>
                                </div>
                            </div>

                            <div>
                                <PrimaryButton class="flex w-full h-12 justify-center rounded-md bg-teal-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-teal-500" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Log in
                                </PrimaryButton>

                            </div>
                        </form>
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
