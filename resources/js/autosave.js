import {ref} from "vue";
import {useThrottleFn} from "@vueuse/core";

export let autoS = ref(1);

export const autosaveT = useThrottleFn((form, submitUrl)=>{
    autosave(form, submitUrl)
},2000)


export function autosave(form,submitUrl){
    autoS.value = 2;
    form.transform((data)=>({
        ...data,
        autosave: true
    })).put(submitUrl,
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                //Autosave actually takes a fraction of a second, but we delay to 1s to make it clear to user it actually happened
                setTimeout(() => autoS.value = 1,1000);
            },
            onError:() => {
                //Chore: Make this work
                setTimeout(() => autoS.value = 3,1000);
            }
        }
    );
}

