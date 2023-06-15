<template>
    <draggable
        :list="list"
        :disabled="!enabled"
        item-key="id"
        class="flex flex-col flex-wrap p-4"
        ghost-class="ghost"
        :move="checkMove"
        @start="dragging = true"
        @end="onListOrderChange"
    >
        <template #item="{ element }">
            <div
                class="bg-white rounded-md shadow-md p-4 cursor-move hover:bg-blue-500 transition duration-300"
                :class="{ 'not-draggable': !enabled }"
            >
                {{ element.title }}
            </div>
        </template>
    </draggable>
</template>

<script setup>
import draggable from "vuedraggable";
import { ref, onMounted, inject  } from "vue";
import axios from "axios";

const enabled = ref(true);
const dragging = ref(false);
const display = ref("Transition");
const $localStorage = inject('$localStorage');
const access_token = $localStorage.getItem('access_token');
const list = ref([]);

const checkMove = function (e) {
    console.log("Future index: " + e.draggedContext.futureIndex);
};
const saveOrder = function(){
    console.log(list.value,'save order');
    axios.post('/api/updateTaskOrder',{ tasks:list.value },{
        headers: {
            'Authorization': `Bearer ${access_token}`
        }
    }).then((response) =>{
        console.log(shit);
    }).catch((error) =>{

    })
}

const onListOrderChange = function () {
    console.log("SHIT");
    list.value.forEach((item, index) => {
        item.order = index;
    });
    saveOrder();
    console.log(list.value);
};


/** PROPS */

const props = defineProps({
    tasks: {
        type: [Array, Object],
    },
});

onMounted(() => {
    list.value = props.tasks;
});

// Initialize the list order on component mount
//   onListOrderChange();
</script>

<style scoped>
.buttons {
    margin-top: 35px;
}

.ghost {
    opacity: 0.5;
    background: #c8ebfb;
}

.not-draggable {
    cursor: no-drop;
}
</style>
