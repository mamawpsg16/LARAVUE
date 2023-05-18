<template>
    <div>
        <router-link to="/login">Login</router-link> ||
        <router-link to="/register">Register</router-link> ||

        <router-link to="/tasks">Tasks</router-link> ||
        <router-link to="/login" @click="logout">Logout</router-link>
    </div>
</template>

<script setup>
import auth from "../../Utils/auth.js";
const logout = function () {
    auth.logout()
        .then((response) => {
            if(response.success){
                router.push({ name: "login" });
            }
            // Handle successful logout, such as redirecting to the login page
        })
        .catch((error) => {
            console.log(error,'DITO NGA');
            // Handle logout error
            console.error(error);
        });
};
</script>

<style scoped>
/* .shit{
    color:green;
} */
</style>
