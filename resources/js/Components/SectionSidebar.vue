<script setup>
import { ref, provide, inject, onBeforeMount, onMounted, onBeforeUnmount } from "vue";

//SCROLL HIDE/SHOW CONST
const OFFSET = 10

const props = defineProps({
    sidebarItems: {
        type: Object,
        required: true
    },
    //SCROLL HIDE/SHOW PROPS
    showNavbar: {
        type: Boolean,
        default: true
    },
    lastScrollPosition: {
        type: Number,
        default: 0
    },
    scrollValue: {
        type: Number,
        default: 0
    },
});

const initialSectionKey = inject("onloadKey");
const selectedSectionId = ref(1);

function sectionsClick(index, item) {
    selectedSectionId.value = index

    Object.keys(props.sidebarItems).forEach(key => {
        props.sidebarItems[key].current = false;
    });

    item.current = true;
}

provide("selectedSectionId", selectedSectionId);

onBeforeMount(() => {
    selectedSectionId.value = initialSectionKey.value
});

//SCROLL HIDE/SHOW MOUNTED and BEFOREDESTROY
onMounted( () => {
    props.lastScrollPosition = window.pageYOffset;
    window.addEventListener('scroll', props.onScroll);
    const viewportMeta = document.createElement('meta');
    viewportMeta.name = 'viewport';
    viewportMeta.content = 'width=device-width, initial-scale=1';
    document.head.appendChild(viewportMeta);
});

onBeforeUnmount( () => {
    window.removeEventListener('scroll', props.onScroll)
});

const onScroll = () => {
    alert("Scroll: " + props.showNavbar + ', ' + props.lastScrollPosition + ', ' + window.pageYOffset);
    if (window.pageYOffset < 0) {
        return
    }
    if (Math.abs(window.pageYOffset - props.lastScrollPosition) < OFFSET) {
        return
    }
    props.showNavbar = window.pageYOffset < props.lastScrollPosition;
    props.lastScrollPosition = window.pageYOffset;
    //this.$emit("showNavbar", 67) ;
    alert("Scroll: " + props.showNavbar + ', ' + props.lastScrollPosition + ', ' + window.pageYOffset);
}

// write Composition API code just like in a normal setup()
// but no need to manually return everything
const count = ref(0)
const inc = () => {
    count.value++;
//    alert(toArray(count));
    alert(JSON.stringify(count));
}
</script>

<style>

.scrollable-container {
    height: 100vh;
    overflow-y: scroll;
}

.content {
    height: 1000px;
}

.to-hide {
    min-height: 500px;
}

</style>

<template>


    <div class="sticky top-0 cursor-pointer" v-for="(item, index) in props.sidebarItems"
        :key="item.name"
        :id="index"
        @click="sectionsClick(index, item)">
        <div class="flex items-center p-2 text-aaron-50 gap-x-3 rounded-md text-sm leading-6 font-semibold group"
             :class="[item.current ? '' : 'hidden']">
            <div class="rounded-full w-11 h-11 py-2 text-center hover:bg-aaron-400 group-hover:bg-aaron-400"
                :class="[item.current ? 'bg-aaron-400' : 'bg-aaron-950']">
                    {{ index }}
            </div>
            <span class="ms-3 text-base group-hover:text-aaron-50">{{ item.name }}</span>
        </div>
    </div>


    <div :class="{ 'invisible': !showNavbar }"
         @click="onScroll"
         class="sticky top-0 flex items-center p-2 text-aaron-50 gap-x-3 rounded-md text-sm leading-6 font-semibold group">
        <div class="rounded-full w-11 py-2 text-center hover:bg-aaron-400 group-hover:bg-aaron-400 bg-aaron-400">
            9
        </div>
        <span class="ms-3 text-base group-hover:text-aaron-50">{{ lastScrollPosition }}</span>
    </div>


    <div class="md:p-4 sm:ml-80">
        <div class="p-4">
            <slot></slot>
        </div>
    </div>

</template>
