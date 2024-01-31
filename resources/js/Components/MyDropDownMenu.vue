<script>
Vue.component('MyDropdownMenu', {
    template: `
<li class="font-semibold text-sm list-none block">
<div class="flex items-center justify-between mb-2 cursor-pointer" @click="toggleShow">
    {{ title }}
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" :class="{'rotate-90' : showMenu}" class="my-dropdown-menu" viewBox="0 0 24 24">
        <path fill="none" d="M0 0h24v24H0V0z"/><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
    </svg>
</div>
<ul class="ml-4 overflow-hidden my-dropdown-menu font-normal" :class="this.class">
    <slot></slot>
</ul>
</li>
`,

    props: ["title", "height"],
    data() {
        return {
            showMenu: false
        };
    },

    methods: {
        toggleShow() {
            this.showMenu = !this.showMenu;
        }
    },

    computed: {
        class() {
            return this.showMenu ? this.height : "h-0";
        }
    }
})

const app = new Vue({
    el: '#my-app',
})

</script>
