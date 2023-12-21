<script setup>
import { nextTick, ref } from 'vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from "@/Components/Checkbox.vue";
import HalfPageImage from "@/Components/HalfPageImage.vue";

const recovery = ref(false);

defineProps({
    halfPageImage: String
});

const form = useForm({
    code: '',
    recovery_code: '',
});

const recoveryCodeInput = ref(null);
const codeInput = ref(null);

const toggleRecovery = async () => {
    recovery.value ^= true;

    await nextTick();

    if (recovery.value) {
        recoveryCodeInput.value.focus();
        form.code = '';
    } else {
        codeInput.value.focus();
        form.recovery_code = '';
    }
};

const submit = () => {
    form.post(route('two-factor.login'));
};
</script>

<template>
    <Head title="Two-factor Confirmation" />
        <Head title="Log in" />
        <div class="flex min-h-full flex-1 bg-aaron-950">
            <HalfPageImage :image="halfPageImage"/>
            <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <div>
                        <AuthenticationCardLogo class="max-w-sm" />
                        <h2 class="mt-8 text-2xl leading-9 tracking-tight text-white">Two Factor <span class="text-teal-500 font-bold">CHALLENGE</span></h2>
                    </div>

                    <form @submit.prevent="submit" class="mt-4">
                        <div v-if="! recovery">
                            <InputLabel class="text-aaron-50" for="code" value="Enter the 2FA Code displayed on your authenticator app" />
                            <TextInput
                                id="code"
                                ref="codeInput"
                                v-model="form.code"
                                type="text"
                                inputmode="numeric"
                                class="mt-1 block w-full"
                                autofocus
                                autocomplete="one-time-code"
                            />
                            <InputError class="mt-2" :message="form.errors.code" />
                        </div>

                        <div v-else>
                            <InputLabel for="recovery_code" value="Recovery Code" />
                            <TextInput
                                id="recovery_code"
                                ref="recoveryCodeInput"
                                v-model="form.recovery_code"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="one-time-code"
                            />
                            <InputError class="mt-2" :message="form.errors.recovery_code" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="button" class="text-sm text-aaron-400 hover:text-teal-500 underline cursor-pointer" @click.prevent="toggleRecovery">
                                <template v-if="! recovery">
                                    Use a recovery code
                                </template>

                                <template v-else>
                                    Use an authentication code
                                </template>
                            </button>

                            <PrimaryButton class="ms-4 bg-teal-500 hover:bg-teal-700" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Log in
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</template>
