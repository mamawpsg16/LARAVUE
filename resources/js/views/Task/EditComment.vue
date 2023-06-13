<template>
    <div>
        <form @submit.prevent="updateComment">
            <textarea
                id="comment"
                type="text"
                placeholder="Enter comment"
                v-model="comment"
                rows="3"
                class="w-full px-4 py-2 mt-1 border rounded-md"
            ></textarea>
            <div class="flex justify-end space-x-1 mb-5">
                <button
                    type="button"
                    @click="closeModal"
                    class="px-4 py-2 mt-4 font-bold text-white bg-blue-500 rounded-md"
                >
                    Close
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 mt-4 font-bold text-white bg-blue-500 rounded-md"
                >
                    Save
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from 'axios';
import { useTaskStore } from "../../stores/TaskStore";
import { showCustomToast } from '../../Utils/toast.js'
/** DATA */
const comment = ref(null);
const taskState = useTaskStore();
/** EMITS */
const emit = defineEmits(["close-modal","comment"]);

/** METHODS */
const updateComment = function () {
    axios
        .put(
            `/api/comment/${props.comment.id}`,
            { comment: comment.value },
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem(
                        "access_token"
                    )}`,
                },
            }
        )
        .then((response) => {
            showCustomToast("success", "Comment Updated!", {
                    // position: 'bottom-right',
                });
            // taskState.$state.updated = Math.random() * 50000;
            emit("comment", { comment: comment.value, comment_id : props.comment.id});
        })
        .catch((error) => {
            console.log(error);
        });
};
const closeModal = function () {
    emit("close-modal", false);
};
const props = defineProps({
    comment: Object,
});

onMounted(() => {
    comment.value = props.comment.comment;
});
</script>

<style scoped></style>
