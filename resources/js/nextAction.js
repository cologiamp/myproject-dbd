import {router} from '@inertiajs/vue3';

export function nextTab(sidebarItemsLength){
    const pathname = window.location.pathname;
    const search = window.location.search;

    let arr = search.split('&');
    let section = arr[1].slice(-1);
    let step = arr[0].slice(-1);

    if(parseInt(section) === sidebarItemsLength)
    {
        router.visit(pathname + '?step=' + (parseInt(step) +1) + '&section=1' )
    }
    else{
        router.visit(pathname + '?step=' + step + '&section=' + (parseInt(section) + 1) )
    }
}
