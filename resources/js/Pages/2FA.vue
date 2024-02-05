<script setup>
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import HalfPageImage from '@/Components/HalfPageImage.vue';
import TwoFactorLoginForm from "@/Pages/Partials/TwoFactorLoginForm.vue";
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import { onMounted } from 'vue';

const page = usePage();
defineProps({
    halfPageImage: String
});

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
    // animate right-side elements
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

    animateLeftSideElems()
});

</script>

<template>
    <Head title="Log in" />
    <div class="flex min-h-full flex-1">
        <div class="w-1/2">
            <div class="h-screen flex justify-center">
                <div class="flex flex-col my-auto">
                    <AuthenticationCardLogo id="logo" class="max-w-2xl mt-10 opacity-0" />
                    <!-- <HalfPageImage :image="halfPageImage" class="order-1"/> -->
                    <div id="login-form" class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24 order-2 opacity-0">
                        <div class="mx-auto w-full max-w-sm lg:w-96">
                            <div>
                                <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-white">Set up 2-FA</h2>
                            </div>

                            <div class="mt-10">
                                <TwoFactorLoginForm :requires-confirmation="true" :show-qr-code="page.props.auth.user?.two_factor_enabled">

                                </TwoFactorLoginForm>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-1/2">
            <div class="h-screen flex justify-center">
                <div class="z-0 absolute top-1/2 transform -translate-y-1/2">
                    <img src="/images/MAN1-550.png" id="round-elem-1" class="w-[550px] h-[550px] scale-0"/>
                </div> 
                <!-- top group elements -->
                <div class="z-20 absolute top-[35%] right-[40%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LOCK.svg" id="round-elem-2" class="w-[83px] h-[83px] scale-0"/>
                </div>
                <div class="z-20 absolute top-[20%] right-[33%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LADY1-150.png" id="round-elem-3" class="w-[150px] h-[150px] scale-0"/>
                </div>
                <div class="z-20 absolute top-[12%] right-[25%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LADY2-85.png" id="round-elem-4" class="w-[85px] h-[85px] scale-0"/>
                </div>
                <div class="z-20 absolute top-[18%] right-[20%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/GRAPH-56.svg" id="round-elem-5" class="w-[56px] h-[56px] scale-0"/>
                </div>
                <div class="z-20 absolute top-[23%] right-[9%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LADY3-135.png" id="round-elem-6" class="w-[135px] h-[135px] scale-0"/>
                </div>
                <div class="z-20 absolute top-[35%] right-[6%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/MATHS-SYMBOLS.svg" id="round-elem-7" class="w-[66px] h-[66px] scale-0"/>
                </div>
                <div class="z-20 absolute top-[34%] right-[26%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/GRAPH1-184x122.svg" id="graph-elem-1" class="w-[184px] h-[122px] opacity-0"/>
                </div>
                <!-- bottom group elements -->
                <div class="z-10 absolute bottom-[30%] left-1/2 transform -translate-x-1/2 -translate-y-[65%]">
                    <img src="/images/BULB-68.svg" id="round-elem-8" class="w-[68px] h-[68px] scale-0"/>
                </div>
                <div class="z-20 absolute bottom-[8%] right-[37%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/MAN2-124.png" id="round-elem-9" class="w-[123px] h-[124px] scale-0"/>
                </div>
                <div class="z-20 absolute bottom-[8%] right-[32%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LADY4-66.png" id="round-elem-10" class="w-[66px] h-[66px] scale-0"/>
                </div>
                <div class="z-20 absolute -bottom-20 right-[20%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/MAN3-134.png" id="round-elem-11" class="w-[134px] h-[134px] scale-0"/>
                </div>
                <div class="z-20 absolute bottom-[8%] right-[15%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/CURSOR-45.svg" id="round-elem-12" class="w-[45px] h-[45px] scale-0"/>
                </div>
                <div class="z-20 absolute bottom-[2%] right-[7%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/MAN4-78.png" id="round-elem-13" class="w-[78px] h-[78px] scale-0"/>
                </div>
                <div class="z-20 absolute bottom-[7%] right-[8%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/GRAPH2-184x122.svg" id="graph-elem-2" class="w-[184px] h-[122px] opacity-0"/>
                </div>
            </div>
        </div>
    </div>
</template>
