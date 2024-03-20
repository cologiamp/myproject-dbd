<script setup>
import { onMounted } from 'vue';

function removeScaleOpacity() {
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
}

function removeAnimations() {
    document.getElementById('round-elem-1').classList.remove('animate-scale-inwards');
    document.getElementById('round-elem-2').classList.remove('animate-scale-inwards');
    document.getElementById('round-elem-3').classList.remove('animate-scale-inwards');
    document.getElementById('round-elem-4').classList.remove('animate-scale-inwards');
    document.getElementById('round-elem-5').classList.remove('animate-scale-inwards');
    document.getElementById('round-elem-6').classList.remove('animate-scale-inwards');
    document.getElementById('round-elem-7').classList.remove('animate-scale-inwards');
    document.getElementById('round-elem-8').classList.remove('animate-scale-inwards');
    document.getElementById('round-elem-9').classList.remove('animate-scale-inwards');
    document.getElementById('round-elem-10').classList.remove('animate-scale-inwards');
    document.getElementById('round-elem-11').classList.remove('animate-scale-inwards');
    document.getElementById('round-elem-12').classList.remove('animate-scale-inwards');
    document.getElementById('round-elem-13').classList.remove('animate-scale-inwards');
    document.getElementById('graph-elem-1').classList.remove('animate-fade-from-bottom-30');
    document.getElementById('graph-elem-2').classList.remove('animate-fade-from-bottom-30');
}

onMounted(()=> {
    //remove animations and scaling before applying animations on specific page
    removeAnimations();
    removeScaleOpacity();
});
</script>

<template>
    <div class="z-0 absolute top-1/2 transform -translate-y-1/2">
        <img src="/images/MAN1-550.png" id="round-elem-1" alt="" class="md:w-[350px] md:h-[350px] 2xl:w-[450px] 2xl:h-[450px] w-[550px] h-[550px] scale-0"/>
    </div>
    <!-- top group elements -->
    <div class="z-20 absolute md:top-[45%] md:right-[40%] 3xl:top-[35%] 3xl:right-[38%] top-[35%] right-[40%] transform -translate-x-[38%] -translate-y-full">
        <img src="/images/LOCK.svg" id="round-elem-2" class="md:w-[53px] md:h-[53px] 2xl:w-[73px] 2xl:h-[73px] w-[83px] h-[83px] scale-0"/>
    </div>
    <div class="z-20 absolute md:top-[20%] md:right-[32%] 3xl:top-[20%] 3xl:right-[30%] top-[20%] right-[33%] transform -translate-x-[38%] -translate-y-full">
        <img src="/images/LADY1-150.png" id="round-elem-3" class="md:w-[120px] md:h-[120px] 2xl:w-[140px] 2xl:h-[140px] w-[150px] h-[150px] scale-0"/>
    </div>
    <div class="z-20 absolute md:top-[11%] md:right-[24%] 3xl:top-[12%] 3xl:right-[24%] top-[12%] right-[25%] transform -translate-x-[38%] -translate-y-full">
        <img src="/images/LADY2-85.png" id="round-elem-4" class="md:w-[55px] md:h-[55px] 2xl:w-[75px] 2xl:h-[75px] w-[85px] h-[85px] scale-0"/>
    </div>
    <div class="z-20 absolute md:top-[20%] md:right-[20%] 3xl:top-[20%] 3xl:right-[21%] top-[18%] right-[20%] transform -translate-x-[38%] -translate-y-full">
        <img src="/images/GRAPH-56.svg" id="round-elem-5" class="md:w-[36px] md:h-[36px] 2xl:w-[46px] 2xl:h-[46px] w-[56px] h-[56px] scale-0"/>
    </div>
    <div class="z-20 absolute md:top-[26%] md:right-[5%] 3xl:top-[28%] 3xl:right-[11%] top-[23%] right-[9%] transform -translate-x-[38%] -translate-y-full">
        <img src="/images/LADY3-135.png" id="round-elem-6" class="md:w-[105px] md:h-[105px] 2xl:w-[125px] 2xl:h-[125px] w-[135px] h-[135px] scale-0"/>
    </div>
    <div class="z-20 absolute md:top-[37%] md:right-[6%] 3xl:top-[37%] 3xl:right-[8%] top-[35%] right-[6%] transform -translate-x-[38%] -translate-y-full">
        <img src="/images/MATHS-SYMBOLS.svg" id="round-elem-7" class="md:w-[46px] md:h-[46px] 2xl:w-[56px] 2xl:h-[56px] w-[66px] h-[66px] scale-0"/>
    </div>
    <div class="z-20 absolute md:top-[35%] md:right-[24%] 3xl:top-[35%] 3xl:right-[25%] top-[34%] right-[23%] transform -translate-x-[38%] -translate-y-full">
        <img src="/images/GRAPH1-184x122.svg" id="graph-elem-1" class="md:w-[164px] md:h-[102px]  2xl:w-[174px] 2xl:h-[112px] w-[184px] h-[122px] opacity-0"/>
    </div>
    <!-- bottom group elements -->
    <div class="z-10 absolute bottom-[30%] left-1/2 2xl:left-[53%] transform -translate-x-1/2 -translate-y-[65%]">
        <img src="/images/BULB-68.svg" id="round-elem-8" class="md:w-[48px] md:h-[48px] 2xl:w-[58px] 2xl:h-[58px] w-[68px] h-[68px] scale-0"/>
    </div>
    <div class="z-20 absolute md:bottom-[6%] md:right-[35%] 3xl:bottom-[12%] 3xl:right-[35%] bottom-[8%] right-[37%] transform -translate-x-[38%] -translate-y-full">
        <img src="/images/MAN2-124.png" id="round-elem-9" class="md:w-[104px] md:h-[104px] 2xl:w-[114px] 2xl:h-[114px] w-[123px] h-[124px] scale-0"/>
    </div>
    <div class="z-20 absolute md:bottom-[10%] md:right-[30%] 3xl:bottom-[13%] 3xl:right-[30%] bottom-[8%] right-[32%] transform -translate-x-[38%] -translate-y-full">
        <img src="/images/LADY4-66.png" id="round-elem-10" class="md:w-[46px] md:h-[46px] 2xl:w-[56px] 2xl:h-[56px] w-[66px] h-[66px] scale-0"/>
    </div>
    <div class="z-20 absolute md:bottom-0 md:right-[18%] 2xl:bottom-0 3xl:right-[20%] bottom-20 right-[20%] transform -translate-x-[38%] -translate-y-full md:-translate-y-10">
        <img src="/images/MAN3-134.png" id="round-elem-11" class="md:w-[114px] md:h-[114px] 2xl:w-[124px] 2xl:h-[124px] w-[134px] h-[134px] scale-0"/>
    </div>
    <div class="z-20 absolute md:bottom-[10%] md:right-[15%] 3xl:bottom-[13%] 3xl:right-[18%] bottom-[8%] right-[15%] transform -translate-x-[38%] -translate-y-full">
        <img src="/images/CURSOR-45.svg" id="round-elem-12" class="md:w-[25px] md:h-[25px] 2xl:w-[35px] 2xl:h-[35px] w-[45px] h-[45px] scale-0"/>
    </div>
    <div class="z-20 absolute md:bottom-[3%] md:right-[8%] 3xl:bottom-[6%] 3xl:right-[12%] bottom-[2%] right-[7%] transform -translate-x-[38%] -translate-y-full">
        <img src="/images/MAN4-78.png" id="round-elem-13" class="md:w-[58px] md:h-[58px] 2xl:w-[68px] 2xl:h-[68px] w-[78px] h-[78px] scale-0"/>
    </div>
    <div class="z-20 absolute md:bottom-[10%] md:right-[5%] 3xl:bottom-[12%] 3xl:right-[10%] bottom-[7%] right-[5%] transform -translate-x-[38%] -translate-y-full">
        <img src="/images/GRAPH2-184x122.svg" id="graph-elem-2" class="md:w-[164px] md:h-[102px] 2xl:w-[174px] 2xl:h-[112px] w-[184px] h-[122px] opacity-0"/>
    </div>
</template>
