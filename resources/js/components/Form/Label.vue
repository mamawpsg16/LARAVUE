<template>
    <label :for="label"><slot></slot></label>
</template>

<script setup>
import { defineProps } from 'vue';
const props = defineProps({ label:String });
</script>

<style lang="scss" scoped>

</style>