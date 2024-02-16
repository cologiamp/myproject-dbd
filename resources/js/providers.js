import {ref} from "vue";

export const filteredProviders = ref([]);
export function fetchOptions(search,loading)
{
    loading(true);
    axios.get('/api/providers/search?search=' + search).then(function (response){
        filteredProviders.value = response.data;
    })
    loading(false);
}
