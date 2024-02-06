<script setup>
import { nextTick, ref, onMounted } from 'vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import LoginAnimatedSection from "@/Pages/Partials/LoginAnimatedSection.vue";
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

function animateLeftSideElems() {
    document.getElementById('login-form').classList.remove('opacity-0');
    document.getElementById('login-form').classList.add('animate-fade-from-bottom-50');
}

onMounted( ()=> {
    animateLeftSideElems()
});
</script>

<template>
    <Head title="Two-factor Confirmation" />
        <Head title="Log in" />
        <div class="flex min-h-full flex-1 bg-aaron-950">
            <div class="w-full md:w-1/2">
                <div class="h-screen flex justify-center">
                    <div class="flex flex-col my-auto">
                        <AuthenticationCardLogo id="logo" class="max-w-2xl" />
                        <div id="login-form" class="mt-10 w-fit md:w-96 mx-auto opacity-0">
                            <form @submit.prevent="submit" class="mt-4">
                                <h2 class="mt-8 text-2xl leading-9 tracking-tight text-white">Two factor challenge</h2>
                        
                                <div v-if="! recovery">
                                    <InputLabel class="text-aaron-50 w-48 font-normal my-8" for="code" value="Enter the 2FA Code displayed on your authenticator app" />
                                    <span class="text-aaron-50 mb-4">Code</span>
                                    <TextInput
                                        id="code"
                                        ref="codeInput"
                                        v-model="form.code"
                                        type="text"
                                        inputmode="numeric"
                                        class="mt-4 block w-full"
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
                                    <button type="button" class="text-sm text-teal-500 hover:text-teal-500 cursor-pointer" @click.prevent="toggleRecovery">
                                        <template v-if="! recovery">
                                            Use a recovery code
                                        </template>

                                        <template v-else>
                                            Use an authentication code
                                        </template>
                                    </button>

                                    <PrimaryButton class="ms-4 bg-teal-500 hover:bg-teal-700 w-[155px] h-[49px] justify-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
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
