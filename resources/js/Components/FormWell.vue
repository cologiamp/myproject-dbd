<script setup>

</script>

<template>
    <div>
        <div class="overflow-hidden bg-aaron-900 sm:rounded-lg ">
            <div class="px-1 py-1 sm:p-2 flex flex-row-reverse">
                <slot name="loading"></slot>
            </div>
            <div class="px-2 py-2 sm:p-4 flex">
                <slot/>
            </div>
        </div>
    </div>
</template>
