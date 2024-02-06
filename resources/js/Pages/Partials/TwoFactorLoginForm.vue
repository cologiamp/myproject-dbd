<script setup>
import {ref, computed, watch, onMounted} from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import ASForm from '@/Components/ASForm.vue';
import ConfirmsPassword from '@/Components/ConfirmsPassword.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    requiresConfirmation: Boolean,
    showQrCode: Boolean,
});

const page = usePage();
const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: '',
});

const twoFactorEnabled = computed(
    () => ! enabling.value && page.props.auth.user?.two_factor_enabled,
);

watch(twoFactorEnabled, () => {
    if (! twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

onMounted(() => {
    if(props.showQrCode){
        Promise.all([
            showQrCode(),
            showSetupKey()
        ]);
        confirming.value = true;
    }
})

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    router.post(route('two-factor.enable'), {}, {
        preserveScroll: true,
        onSuccess: () => Promise.all([
            showQrCode(),
            showSetupKey(),
            showRecoveryCodes(),
        ]),
        onFinish: () => {
            enabling.value = false;
            confirming.value = props.requiresConfirmation;
        },
    });
};

const showQrCode = () => {
    return axios.get(route('two-factor.qr-code')).then(response => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get(route('two-factor.secret-key')).then(response => {
        setupKey.value = response.data.secretKey;
    });
}

const showRecoveryCodes = () => {
    return axios.get(route('two-factor.recovery-codes')).then(response => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post(route('two-factor.confirm'), {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
           window.location.href = "/dashboard"
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios
        .post(route('two-factor.recovery-codes'))
        .then(() => showRecoveryCodes());
};


</script>

<template>
    <ASForm>
        <template #title>
            Two Factor Authentication
        </template>

        <template #description>
            <span class="text-white font-normal">Add additional security to your account using two factor authentication.</span>

        </template>

        <template #content>


            <h3 v-if="!twoFactorEnabled" class="text-lg font-medium text-white">
                You have not enabled two factor authentication.
            </h3>

            <h3 v-else-if="twoFactorEnabled && confirming" class="text-lg font-medium text-white">
                Finish enabling two factor authentication.
            </h3>

            <h3 v-else class="text-lg font-medium text-white">
                Confirm your 2-FA
            </h3>


            <div class="mt-3 max-w-xl text-sm text-white">
                <p>
                    When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
                </p>
            </div>

            <div v-if="twoFactorEnabled">
                <div v-if="qrCode">
                    <div class="mt-4 max-w-xl text-sm text-white">
                        <p v-if="confirming" class="font-normal">
                            To finish enabling two factor authentication, scan the following QR code using your phone's authenticator application or enter the setup key and provide the generated OTP code.
                        </p>

                        <p v-else>
                            Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application or enter the setup key.
                        </p>
                    </div>

                    <div class="md:flex md:flex-row">
                        <div class="mt-4 p-2 inline-block bg-white" v-html="qrCode" />
                        <div v-if="setupKey" class="mt-4 max-w-xl text-sm text-[#00B49D] md:ml-8">
                            <p class="font-semibold">
                                Setup Key: <span v-html="setupKey"></span>
                            </p>
                            <div v-if="confirming" class="mt-4 text-white">
                                <InputLabel for="code" value="Code" />

                                <TextInput
                                    id="code"
                                    v-model="confirmationForm.code"
                                    type="text"
                                    name="code"
                                    class="block mt-1 w-1/2 ring-1 ring-inset ring-aaron-500 bg-aaron-950 focus:ring-1 focus:ring-inset focus:ring-aaron-500"
                                    inputmode="numeric"
                                    autofocus
                                    autocomplete="one-time-code"
                                    @keyup.enter="confirmTwoFactorAuthentication"
                                />
                                <InputError :message="confirmationForm.errors.code" class="mt-2" />
                                <div v-if="twoFactorEnabled" class="mt-4">
                                    <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
                                        <PrimaryButton
                                            v-if="confirming"
                                            type="button"
                                            class="me-3 bg-teal-500 hover:bg-teal-700 w-[155px] h-[49px] justify-center"
                                            :class="{ 'opacity-25': enabling }"
                                            :disabled="enabling"
                                        >
                                            Confirm
                                        </PrimaryButton>
                                    </ConfirmsPassword>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="recoveryCodes.length > 0 && ! confirming">
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p class="font-semibold">
                            Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
                        </p>
                    </div>

                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                        <div v-for="code in recoveryCodes" :key="code">
                            {{ code }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div v-if="! twoFactorEnabled">
                    <ConfirmsPassword @confirmed="enableTwoFactorAuthentication">
                        <PrimaryButton type="button" :class="{ 'opacity-25': enabling }" :disabled="enabling">
                            Enable
                        </PrimaryButton>
                    </ConfirmsPassword>
                </div>

                <div v-else>
                    <!-- <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
                        <PrimaryButton
                            v-if="confirming"
                            type="button"
                            class="me-3 bg-teal-500 hover:bg-teal-700 w-[155px] h-[49px] justify-center"
                            :class="{ 'opacity-25': enabling }"
                            :disabled="enabling"
                        >
                            Confirm
                        </PrimaryButton>
                    </ConfirmsPassword> -->

                    <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length > 0 && ! confirming"
                            class="me-3"
                        >
                            Regenerate Recovery Codes
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="showRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length === 0 && ! confirming"
                            class="me-3"
                        >
                            Show Recovery Codes
                        </SecondaryButton>
                    </ConfirmsPassword>

                </div>
            </div>
        </template>
    </ASForm>
</template>
