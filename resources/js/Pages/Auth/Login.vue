<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import HalfPageImage from '@/Components/HalfPageImage.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

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
</script>

<template>
    <Head title="Log in" />
    <div class="flex min-h-full flex-1">
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <AuthenticationCardLogo />
                    <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to AdviserHub</h2>
                </div>

                <div class="mt-10">
                    <div>
                        <form @submit.prevent="submit" action="#" method="POST" class="space-y-6">
                            <div>
                                <InputLabel for="email" value="Email address" class="block text-sm font-medium leading-6 text-gray-900" />
                                <div class="mt-2">
                                    <TextInput
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-400 sm:text-sm sm:leading-6"
                                            required
                                            autofocus
                                            autocomplete="email"
                                        />
                                    <InputError class="mt-2" :message="form.errors.email" />

                                </div>
                            </div>

                            <div>
                                <div class="mt-2">
                                    <InputLabel for="password" value="Password" class="block text-sm font-medium leading-6 text-gray-900" />
                                    <TextInput
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        class="mt-1 block w-full"
                                        required
                                        autocomplete="current-password"
                                    />
                                    <InputError class="mt-2" :message="form.errors.password" />

                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <Checkbox v-model:checked="form.remember" name="remember" class="h-4 w-4 rounded border-gray-300 text-blue-400 focus:ring-blue-400" />
                                    <label for="remember-me" class="ml-3 block text-sm leading-6 text-gray-700">Remember me</label>
                                </div>


                                <div class="text-sm leading-6">
                                    <Link v-if="canResetPassword" :href="route('password.request')" class="font-semibold text-blue-400 hover:text-blue-300">Forgot password?</Link>
                                </div>
                            </div>

                            <div>
                                <PrimaryButton class="flex w-full justify-center rounded-md bg-blue-400 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-400" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Log in
                                </PrimaryButton>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <HalfPageImage :image="halfPageImage"/>
    </div>
</template>
