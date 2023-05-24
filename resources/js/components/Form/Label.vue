<template>
    <label :for="label"><slot></slot></label>
</template>

<script setup>
const props = defineProps({ label:String });
</script>

<style lang="scss" scoped>

</style>