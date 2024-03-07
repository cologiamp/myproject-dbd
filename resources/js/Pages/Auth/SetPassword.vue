<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import LoginAnimatedSection from "@/Pages/Partials/LoginAnimatedSection.vue";

const form = useForm({
    password: '',
    password_confirmation: '',
});

const setPassword = () => {
    form.post(route('set-password-store'), {
        errorBag: 'updatePassword',
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <Head title="Two-factor Confirmation" />
        <Head title="Log in" />
        <div class="flex min-h-full flex-1 bg-aaron-900">
            <div class="w-full md:w-1/2">
                <div class="h-full flex justify-center">
                    <div class="flex flex-col my-auto">
                        <AuthenticationCardLogo id="logo" class="max-w-2xl" />
                        <div id="login-form" class="mt-10 w-fit md:w-96 mx-auto">
                            <div class="mt-2 mb-2 text-aaron-50 ">
                                For security purposes, please reset your password using the form below to a secure password that you will remember.
                            </div>
                            <form @submit.prevent="submit" style="color:white">

                                <div class="mt-4">
                                    <InputLabel for="password" value="New Password" />
                                    <TextInput
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        class="mt-1 block w-full"
                                        required
                                        autocomplete="new-password"
                                        style="color:black"
                                    />
                                    <InputError class="mt-2" :message="form.errors.password" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="password_confirmation" value="Confirm New Password" />
                                    <TextInput
                                        id="password_confirmation"
                                        v-model="form.password_confirmation"
                                        type="password"
                                        class="mt-1 block w-full"
                                        required
                                        autocomplete="new-password"
                                        style="color:black"
                                    />
                                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <PrimaryButton @click="setPassword" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Reset Password
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
