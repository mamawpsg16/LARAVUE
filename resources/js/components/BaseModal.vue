<template>
    <Teleport to="#modal">
      <Transition name="fade">
        <div
          class="fixed inset-0 flex items-center justify-center z-50 my-3"
          v-if="modelValue"
        >
          <div class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>
          <div
            :class="modalClass"
            class="bg-white rounded-lg p-8 relative max-h-screen"
            ref="modalCardRef"
          >
            <div class="flex items-start justify-between mb-4">
              <div>
                <p class="text-base text-lg font-medium">
                  <slot name="modal-title" />
                </p>
              </div>
              <button class="text-black" @click="closeModal">
                <svg
                  class="h-5 w-5"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <!-- Close button SVG code -->
                </svg>
              </button>
            </div>
            <slot></slot>
          </div>
        </div>
      </Transition>
    </Teleport>
  </template>
  

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { onClickOutside } from "@vueuse/core";
const props = defineProps({
    modalClass: {
        type: String,
        default: "w-full xs:w-3/4 sm:w-3/4 md:w-2/3 lg:w-6/12",
    },
    modelValue: {
        type: Boolean,
        default: false,
    },
});

/** EMIT */
const emit = defineEmits(["update:modelValue"]);

/** VUE USE ONCLICK OUTSIDE */
const modalCardRef = ref(null);
onClickOutside(modalCardRef, () => closeModal());

/** CLOSE MODAL */
const closeModal = () => {
    emit("update:modelValue", false);
};

/** KEYBOARD LISTENER */
const handleKeyboard = (e) => {
    console.log("close");
    if (e.key === "Escape") {
        closeModal();
    }
};

/** LIFECYCLE HOOKS */
onMounted(() => {
    document.addEventListener("keyup", handleKeyboard);
});
onUnmounted(() => {
    document.removeEventListener("keyup", handleKeyboard);
});
</script>
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
