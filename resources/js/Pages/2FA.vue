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
    document.getElementById('login-form').classList.remove('opacity-0');
    document.getElementById('login-form').classList.add('animate-fade-from-bottom-50');
}

onMounted( ()=> {
    window.scrollTo(0, 0);
    animateLeftSideElems()
});

</script>

<template>
    <Head title="Log in" />
    <div class="flex min-h-full flex-1">
        <div class="w-1/2">
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
        <div class="w-1/2">
            <div class="h-screen flex justify-center">
                <div class="z-0 absolute top-1/2 transform -translate-y-1/2">
                    <img src="/images/MAN1-550.png" id="round-elem-1" class="2xl:w-[450px] 2xl:h-[450px] 3xl:w-[550px] 3xl:h-[550px]"/>
                </div> 
                <!-- top group elements -->
                <div class="z-20 absolute top-[35%] right-[40%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LOCK.svg" id="round-elem-2" class="2xl:w-[73px] 2xl:h-[73px] 3xl:w-[83px] 3xl:h-[83px]"/>
                </div>
                <div class="z-20 absolute top-[20%] right-[33%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LADY1-150.png" id="round-elem-3" class="2xl:w-[140px] 2xl:h-[140px] 3xl:w-[150px] 3xl:h-[150px]"/>
                </div>
                <div class="z-20 absolute top-[12%] right-[25%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LADY2-85.png" id="round-elem-4" class="2xl:w-[75px] 2xl:h-[75px] 3xl:w-[85px] 3xl:h-[85px]"/>
                </div>
                <div class="z-20 absolute top-[18%] right-[20%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/GRAPH-56.svg" id="round-elem-5" class="2xl:w-[46px] 2xl:h-[46px] 3xl:w-[56px] 3xl:h-[56px]"/>
                </div>
                <div class="z-20 absolute top-[23%] right-[9%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LADY3-135.png" id="round-elem-6" class="2xl:w-[125px] 2xl:h-[125px] 3xl:w-[135px] 3xl:h-[135px]"/>
                </div>
                <div class="z-20 absolute top-[35%] right-[6%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/MATHS-SYMBOLS.svg" id="round-elem-7" class="2xl:w-[56px] 2xl:h-[56px] 3xl:w-[66px] 3xl:h-[66px]"/>
                </div>
                <div class="z-20 absolute top-[34%] right-[23%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/GRAPH1-184x122.svg" id="graph-elem-1" class="2xl:w-[174px] 2xl:h-[112px] 3xl:w-[184px] 3xl:h-[122px]"/>
                </div>
                <!-- bottom group elements -->
                <div class="z-10 absolute bottom-[30%] left-1/2 transform -translate-x-1/2 -translate-y-[65%]">
                    <img src="/images/BULB-68.svg" id="round-elem-8" class="2xl:w-[58px] 2xl:h-[58px] 3xl:w-[68px] 3xl:h-[68px]"/>
                </div>
                <div class="z-20 absolute bottom-[8%] right-[37%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/MAN2-124.png" id="round-elem-9" class="2xl:w-[114px] 2xl:h-[114px] 3xl:w-[123px] 3xl:h-[124px]"/>
                </div>
                <div class="z-20 absolute bottom-[8%] right-[32%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/LADY4-66.png" id="round-elem-10" class="2xl:w-[56px] 2xl:h-[56px] 3xl:w-[66px] 3xl:h-[66px]"/>
                </div>
                <div class="z-20 absolute -bottom-20 right-[20%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/MAN3-134.png" id="round-elem-11" class="2xl:w-[124px] 2xl:h-[124px] 3xl:w-[134px] 3xl:h-[134px]"/>
                </div>
                <div class="z-20 absolute bottom-[8%] right-[15%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/CURSOR-45.svg" id="round-elem-12" class="2xl:w-[35px] 2xl:h-[35px] 3xl:w-[45px] 3xl:h-[45px]"/>
                </div>
                <div class="z-20 absolute bottom-[2%] right-[7%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/MAN4-78.png" id="round-elem-13" class="2xl:w-[68px] 2xl:h-[68px] 3xl:w-[78px] 3xl:h-[78px]"/>
                </div>
                <div class="z-20 absolute bottom-[7%] right-[5%] transform -translate-x-[38%] -translate-y-full">
                    <img src="/images/GRAPH2-184x122.svg" id="graph-elem-2" class="2xl:w-[174px] 2xl:h-[112px] 3xl:w-[184px] 3xl:h-[122px]"/>
                </div>
            </div>
        </div>
    </div>
</template>
