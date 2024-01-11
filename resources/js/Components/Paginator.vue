<script setup>
import { ref } from 'vue';


const props = defineProps({
    pagination: Object,
})

const emits = defineEmits(["pageChange"])

const page = ref(props.pagination.current_page)
const perPage = ref(props.pagination.per_page)

const pageChange = link => {

    let newPage;

    if (link.label.includes("Previous")) {
        newPage = props.pagination.current_page - 1
    } else if (link.label.includes("Next")) {
        newPage = props.pagination.current_page + 1
    } else {
        newPage = parseInt(link.label);
    }

    emits("pageChange", { page: newPage, perPage: perPage.value })

}

const perPageChange = () => {
    emits("pageChange", { page: page.value, perPage: perPage.value })
}

</script>

<template>
    <div class="w-full flex flex-row items-center justify-end">
        <div class="w-full flex flex-row items-center justify-end gap-12">
            <div class="flex flex-row items-center gap-4">
                <div v-for="(link, index) in pagination.links">
                    <button
                        v-if="link.url"
                        v-html="link.label.includes('Previous') ? 'previous' : link.label.includes('Next') ? 'next' : link.label"
                        :class="link.active ? 'bg-aaron-400 px-3 py-1 rounded-[20px]' : ''"
                        @click="pageChange(link)"
                    />
                    <span
                        v-else
                        v-html="link.label.includes('...') ? '...' : (link.label.includes('Previous') ? 'previous' : 'next')"
                        :class="link.label.includes('...') ? '' : 'text-sm'"
                        class="text-gray-400"
                        disabled
                    />
                </div>
            </div>
            <select @change="perPageChange" v-model="perPage" class="block w-fit border-aaron-300 py-1.5 bg-aaron-950 ring-aaron-300 rounded-md text-aaron-50 shadow-sm focus:ring-aaron-400 sm:max-w-xs sm:text-sm sm:leading-6 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none min-w-[160px]">
                <option :value="5">5 Per Page</option>
                <option :value="9" selected>9 Per Page</option>
                <option :value="10" >10 Per Page</option>
                <option :value="15" >15 Per Page</option>
                <option :value="20" >20 Per Page</option>
            </select>
        </div>
    </div>
</template>
