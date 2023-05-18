<template>
  <form :action="props.action" :method="props.method" enctype="multipart/form-data">
    <slot></slot>
  </form>
</template>

<script setup>
import { defineProps } from 'vue';
const props = defineProps({
    action:{
        type:String,
        required:false
    },
    method:{
        type:String,
        required:false
    }
})
</script>

<style lang="scss" scoped>

</style>