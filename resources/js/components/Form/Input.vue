<template>
  <input
    :type="props.type"
    :placeholder="props.placeholder"
    :value="props.modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  />
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
  type: { type: String, default: "text" },
  placeholder: { type: String, required: false },
  modelValue: { required: true }
});
</script>

<style lang="scss" scoped>
</style>
