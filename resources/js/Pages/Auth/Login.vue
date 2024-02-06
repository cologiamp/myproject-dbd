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
import { onMounted } from 'vue';

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

function animateRightSideElems() {
    document.getElementById('round-elem-1').classList.remove('scale-0');
    document.getElementById('round-elem-2').classList.remove('scale-0');
    document.getElementById('round-elem-3').classList.remove('scale-0');
    document.getElementById('round-elem-4').classList.remove('scale-0');
    document.getElementById('round-elem-5').classList.remove('scale-0');
    document.getElementById('round-elem-6').classList.remove('scale-0');
    document.getElementById('round-elem-7').classList.remove('scale-0');
    document.getElementById('round-elem-8').classList.remove('scale-0');
    document.getElementById('round-elem-9').classList.remove('scale-0');
    document.getElementById('round-elem-10').classList.remove('scale-0');
    document.getElementById('round-elem-11').classList.remove('scale-0');
    document.getElementById('round-elem-12').classList.remove('scale-0');
    document.getElementById('round-elem-13').classList.remove('scale-0');
    document.getElementById('graph-elem-1').classList.remove('opacity-0');
    document.getElementById('graph-elem-2').classList.remove('opacity-0');
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
}

onMounted( ()=> {
    // animate elements
    animateRightSideElems();
    animateLeftSideElems();
});
    
</script>

<template>
    <Head title="Log in" />
    <div class="flex min-h-full flex-1 bg-aaron-950">
        <div class="w-1/2">
            <div class="h-screen flex justify-center">
                <div class="flex flex-col my-auto">
                    <AuthenticationCardLogo id="logo" class="max-w-2xl opacity-0"/>
                    <div id="login-form" class="mt-10 w-96 mx-auto opacity-0">
                        <form @submit.prevent="submit" action="#" method="POST" class="space-y-6">
                            <h2 class="mt-8 text-2xl leading-9 tracking-tight text-white">Sign in</h2>
                            <div>
                                <InputLabel for="email" value="Email address" class="block text-sm font-medium leading-6 text-white" />
                                <div class="mt-2">
                                    <TextInput
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-teal-500 sm:text-sm sm:leading-6"
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
                                        class="mt-1 block w-full"
                                        required
                                        autocomplete="current-password"
                                    />
                                    <InputError class="mt-2" :message="form.errors.password" />

                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <Checkbox v-model:checked="form.remember" name="remember" class="h-4 w-4 rounded border-gray-300 text-teal-500 focus:ring-teal-500" />
                                    <label for="remember-me" class="ml-3 block text-sm leading-6 text-gray-100">Remember me</label>
                                </div>


                                <div class="text-sm leading-6">
                                    <Link v-if="canResetPassword" :href="route('password.request')" class="font-semibold text-teal-500 hover:text-teal-400">Forgot password?</Link>
                                </div>
                            </div>

                            <div>
                                <PrimaryButton class="flex w-full justify-center rounded-md bg-teal-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-teal-500" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Log in
                                </PrimaryButton>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-1/2">
            <div class="h-screen flex justify-center">
                <div class="z-0 absolute top-1/2 transform -translate-y-1/2">
                    <img src="/images/MAN1-550.png" id="round-elem-1" class="2xl:w-[450px] 2xl:h-[450px] 3xl:w-[550px] 3xl:h-[550px] scale-0"/>
                </div> 
                <!-- top group elements -->
                <div class="z-20 absolute top-[35%] right-[40%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LOCK.svg" id="round-elem-2" class="2xl:w-[73px] 2xl:h-[73px] 3xl:w-[83px] 3xl:h-[83px] scale-0"/>
                </div>
                <div class="z-20 absolute top-[20%] right-[33%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LADY1-150.png" id="round-elem-3" class="2xl:w-[140px] 2xl:h-[140px] 3xl:w-[150px] 3xl:h-[150px] scale-0"/>
                </div>
                <div class="z-20 absolute top-[12%] right-[25%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LADY2-85.png" id="round-elem-4" class="2xl:w-[75px] 2xl:h-[75px] 3xl:w-[85px] 3xl:h-[85px] scale-0"/>
                </div>
                <div class="z-20 absolute top-[18%] right-[20%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/GRAPH-56.svg" id="round-elem-5" class="2xl:w-[46px] 2xl:h-[46px] 3xl:w-[56px] 3xl:h-[56px] scale-0"/>
                </div>
                <div class="z-20 absolute top-[23%] right-[9%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LADY3-135.png" id="round-elem-6" class="2xl:w-[125px] 2xl:h-[125px] 3xl:w-[135px] 3xl:h-[135px] scale-0"/>
                </div>
                <div class="z-20 absolute top-[35%] right-[6%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/MATHS-SYMBOLS.svg" id="round-elem-7" class="2xl:w-[56px] 2xl:h-[56px] 3xl:w-[66px] 3xl:h-[66px] scale-0"/>
                </div>
                <div class="z-20 absolute top-[34%] right-[23%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/GRAPH1-184x122.svg" id="graph-elem-1" class="2xl:w-[174px] 2xl:h-[112px] 3xl:w-[184px] 3xl:h-[122px] opacity-0"/>
                </div>
                <!-- bottom group elements -->
                <div class="z-10 absolute bottom-[30%] left-1/2 transform -translate-x-1/2 -translate-y-[65%]">
                    <img src="/images/BULB-68.svg" id="round-elem-8" class="2xl:w-[58px] 2xl:h-[58px] 3xl:w-[68px] 3xl:h-[68px] scale-0"/>
                </div>
                <div class="z-20 absolute bottom-[8%] right-[37%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/MAN2-124.png" id="round-elem-9" class="2xl:w-[114px] 2xl:h-[114px] 3xl:w-[123px] 3xl:h-[124px] scale-0"/>
                </div>
                <div class="z-20 absolute bottom-[8%] right-[32%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LADY4-66.png" id="round-elem-10" class="2xl:w-[56px] 2xl:h-[56px] 3xl:w-[66px] 3xl:h-[66px] scale-0"/>
                </div>
                <div class="z-20 absolute -bottom-20 right-[20%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/MAN3-134.png" id="round-elem-11" class="2xl:w-[124px] 2xl:h-[124px] 3xl:w-[134px] 3xl:h-[134px] scale-0"/>
                </div>
                <div class="z-20 absolute bottom-[8%] right-[15%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/CURSOR-45.svg" id="round-elem-12" class="2xl:w-[35px] 2xl:h-[35px] 3xl:w-[45px] 3xl:h-[45px] scale-0"/>
                </div>
                <div class="z-20 absolute bottom-[2%] right-[7%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/MAN4-78.png" id="round-elem-13" class="2xl:w-[68px] 2xl:h-[68px] 3xl:w-[78px] 3xl:h-[78px] scale-0"/>
                </div>
                <div class="z-20 absolute bottom-[7%] right-[5%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/GRAPH2-184x122.svg" id="graph-elem-2" class="2xl:w-[174px] 2xl:h-[112px] 3xl:w-[184px] 3xl:h-[122px] opacity-0"/>
                </div>
            </div>
        </div>
    </div>
</template>
