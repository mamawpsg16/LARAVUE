<template>
    <aside
        class="w-64 fixed left-0 top-0 h-screen z-40  mt-16 border-r"
        :class="{ hidden: !props.sidebarOpen, block: props.sidebarOpen }"
        aria-label="Sidebar"
    >
        <div
            class="overflow-y-auto pt-2 px-2 border-b  bg-gray-900 h-full"
        >
            <!-- <p class="text-black-700 text-lg font-bold">Main Menu</p> -->
            <ul class="space-y-2 mt-4 px-3 text-white  ">
           
                <li  v-for="link in links.value" :key="link.id"  :class="{ 'text-blue-500': isActive(link.route) }">
                    <router-link
                        :to="link.route"
                        class="flex items-center p-2 text-base font-normal  rounded-lg  hover:bg-gray-700"
                        >
                        <i :class="link.icon"></i>
                        <span class="flex-1 ml-3 whitespace-nowrap"
                            >  {{ link.name }}</span
                        >
                        </router-link
                    >
                </li>
            </ul>
        </div>
    </aside>
</template>

<script setup>
import { useRoute } from "vue-router";
import { onMounted, ref, reactive } from 'vue'
import axios from 'axios';
import { getItem } from "../../Utils/localStorage";
const route = useRoute();
const isActive = (url) => {
    return route.path === url;
};

const props = defineProps({
    sidebarOpen: Boolean,
});

const links =  reactive({})
const module_permissions =  reactive({})
// [
//     { id: 1, name: "Tasks", url: "/tasks", 'svg':`<svg class="flex-shrink-0 w-5 h-5  transition duration-75 " style="color: rgb(247, 247, 247);" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-task" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M2 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5V3a.5.5 0 0 0-.5-.5H2zM3 3H2v1h1V3z" fill="#f7f7f7"></path> <path d="M5 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM5.5 7a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 4a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9z" fill="#f7f7f7"></path> <path fill-rule="evenodd" d="M1.5 7a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5V7zM2 7h1v1H2V7zm0 3.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5H2zm1 .5H2v1h1v-1z" fill="#f7f7f7"></path> </svg>` }
// ];
const getModules = async function(){
    axios.get('/api/modules',{headers: {
                    Authorization: `Bearer ${getItem("access_token")}`,
                }})
    .then((response) =>{
        const { modules, permissions } = response.data;
        console.log(response)
        links.value = modules;
        module_permissions.value = permissions;
    }).catch((error) =>{
        console.log(error)
    })
}

onMounted(() =>{
    getModules();
})
</script>
