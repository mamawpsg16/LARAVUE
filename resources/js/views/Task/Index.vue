<template>
    <div>
        TASKS
        <router-link to="/task/create" >Create</router-link>
        <template v-if="tasks.length">
          <div v-for="task in tasks" :key="task.id">
           <p> {{ task.title }}</p>
           <p> {{ task.description }}</p>
           <p> {{ $date.humanReadable(task.due_date) }}</p>
           <p> {{ $date.humanReadable(task.due_date) }}</p>
           <router-link :to="`/task/${task.id}`">Show</router-link>
          </div>
        </template>
        <p v-else>No Tasks Yet...</p>
    </div>
</template>

<script setup>
import { ref, onMounted  } from 'vue';
// import Input from '../../components/Form/Input.vue';
import Label from '../../components/Form/Label.vue';

// import Input from '@js/components/Form/Input.vue';
import axios from 'axios';
   const tasks = ref([]);
   const getTasks = () => {
    const access_token = localStorage.getItem('bearer_token');
    axios.get('/api/tasks',{
      headers: {
            Authorization: `Bearer ${access_token}`
        }
    }).then((response) => {
      // console.log(response.data);
      tasks.value = response.data
    });
   }
   onMounted(() => {
    getTasks();
  })
</script>

<style lang="scss" scoped>

</style>