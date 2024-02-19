import {ref} from "vue";
import {useThrottleFn} from "@vueuse/core";
import {usePage} from "@inertiajs/vue3";
export let autoS = ref(1);

export async function autosaveT(form,submitUrl){
    // return useThrottleFn((form, submitUrl)=>{
    //
    // },2000)
    return autosave(form, submitUrl)
}


export async function autosave(form,submitUrl){
    autoS.value = 2;
    return await axios.put(submitUrl, {
        ...form,
        autosave: true
    })
        .then((response) => {
            usePage().props.errors = null;
            //Autosave actually takes a fraction of a second, but we delay to 1s to make it clear to user it actually happened
            setTimeout(() => autoS.value = 1,1000);
            return response.data.model;
        })
        .catch((error) => {
            console.log(error);
            usePage().props.errors = error.response.data.errors
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
