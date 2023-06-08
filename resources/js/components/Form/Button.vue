<template>
    <!-- <button @click="handleClick">Click</button> -->
    <!-- <button @click="$emit('button-click','FUCK THIS')">Click</button> -->
    <button @click="$emit('button-click','FUCK THIS')">Click</button>
</template>

<script setup>
// import { defineEmits } from 'vue';
const emit = defineEmits(['button-click'])

// const handleClick = function(){
//     // Emit the button-click event with a custom data
//     emit('button-click','Shit');
// }
</script>

<style lang="scss" scoped>

</style>