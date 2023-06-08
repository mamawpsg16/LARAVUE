<template>
  <input
    :type="type"
    :placeholder="placeholder"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  />

  <!-- <input type="text"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  > -->
</template>

<script setup>

defineProps({
  type: { type: String, default: "text" },
  placeholder: { type: String, required: false },
  modelValue: { required: true }
});
</script>

<style lang="scss" scoped>
</style>
