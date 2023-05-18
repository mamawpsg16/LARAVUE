<template>
    <Form>
        <div>
            <div>
                <label label="title">Title</label>
                <Input placeholder="Enter title" v-model="task.title"/>
                <br>
                <span v-if="errors.title" class="error">{{ errors.title[0] }}</span>
            </div>
            <div>
                <label label="Description">Description</label>
                <Input placeholder="Enter description" v-model="task.description"/>
                <br>
                <span v-if="errors.description" class="error">{{ errors.description[0] }}</span>
            </div>
            <div>
                <label label="Due Date">Due Date</label>
                <Input type="date" placeholder="Enter title" v-model="task.due_date"/>
                <br>
                <span v-if="errors.due_date" class="error">{{ errors.due_date[0] }}</span>
            </div>
            <button @click.prevent="update">Save</button>
        </div>
    </Form>
</template>

<script setup>
import Input from '../../components/Form/Input.vue'
import Form from '../../components/Form/Form.vue'
import Label from '../../components/Form/Label.vue'
import axios from 'axios';
import { showCustomToast } from '../../Utils/toast.js';
import { useRoute, useRouter } from 'vue-router'
import { reactive, ref } from 'vue';
import { onMounted } from 'vue';

const router = useRouter()
const route = useRoute()
const errors = ref([]);
const task = ref([])

const getTasks = () => {
    const access_token = localStorage.getItem("bearer_token");
    const task_id = route.params.id;
    axios
        .get(`/api/tasks/${task_id}`, {
            headers: {
                Authorization: `Bearer ${access_token}`,
            },
        })
        .then((response) => {
          task.value = response.data
          // task.description = response.data.description;
        });
};
const update = () => {
    const access_token = localStorage.getItem('bearer_token');
    // console.log(access_token);
    // return false;
    axios
      .put(`/api/tasks/${task.value.id}`, {title: task.value.title, description: task.value.description, due_date: task.value.due_date},
      {
        headers: {
            Authorization: `Bearer ${access_token}`
        }
      })
      .then((response) => {
        console.log(response);
        // Check if the response contains success message
        if (response.data) {
            // Store the token in local storage
            
            showCustomToast('success', 'Task Updated!', {
           
              // position: 'bottom-right',
            });
            // console.log(task.value.id);
            // router.push({ name: '/task/',  params: { id: task.value.id } });
            router.push(`/task/${task.value.id}`) 
          // Success, redirect or show a success message
        }
      })
      .catch((error) => {
        console.log(error.response);
        // Handle request error
        if (error.response?.status === 422) {
          // Handle validation errors
          console.log('ERROR');
          console.log(error.response.data.errors);
          console.log('SHIT');
          if(error.response.data.errors.not_exists?.length){
            showCustomToast('error',error.response.data.errors.not_exists,{ })
          }
          console.log(error.response.data.errors);
          errors.value = error.response.data.errors;
        } else {
          // Handle other errors
          console.log(error);
        }
      });
};

onMounted(() =>{
  getTasks();
})

  


</script>

<style lang="scss" scoped>

</style>