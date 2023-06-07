<template>
    <Teleport to="#modal">
        <Transition name="fade">
            <div
                class="fixed inset-0 flex items-center justify-center z-50"
                v-if="modelValue"
            >
                <div class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>
                <div
                    :class="modalClass"
                    class="bg-white rounded-lg p-8 relative"
                    ref="modalCardRef"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <p class=" text-base"><slot name="modal-title" /></p>
                        </div>
                        <button class="text-gray-500" @click="closeModal">
                            <svg
                                class="h-5 w-5"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 5.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </button>
                    </div>
                    <slot></slot>
                    <div class="mt-4 flex justify-end">
                        <!-- <button
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                    @click="closeModal"
                  >
                    Close
                  </button> -->
                    </div>
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
        default: "",
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
