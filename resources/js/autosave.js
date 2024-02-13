import {ref} from "vue";
import {useThrottleFn} from "@vueuse/core";
import Swal from "sweetalert2";
import {usePage} from "@inertiajs/vue3";

export let autoS = ref(1);

export const autosaveT = useThrottleFn((form, submitUrl)=>{
    autosave(form, submitUrl)
},2000)


export function autosave(form,submitUrl){
    autoS.value = 2;
    axios.put(submitUrl, {
        ...form,
        autosave: true
    })
        .then((response) => {
            //Autosave actually takes a fraction of a second, but we delay to 1s to make it clear to user it actually happened
            setTimeout(() => autoS.value = 1,1000);
        })
        .catch((error) => {
            setTimeout(() => autoS.value = 3,1000);
    });
}

export function __error(field, errorBag = 'default') {
    if (!usePage().props.errors.hasOwnProperty(errorBag)) {
        return null;
    }
    if (usePage().props.errors[errorBag].hasOwnProperty(field)) {
        return usePage().props.errors[errorBag][field][0];
    }
    return null;
}
