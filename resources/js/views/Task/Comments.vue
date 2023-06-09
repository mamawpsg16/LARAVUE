<template>
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-lg space-y-4 p-4">
            <h3 class="text-lg font-medium">Comments</h3>
            <!-- Comments list -->
            <template v-if="comments?.length">
                <!-- Single comment -->
                <template v-for="comment in comments" :key="comment.id">
                    <div class="flex space-x-4">
                        <div class="w-10 h-10 rounded-full overflow-hidden">
                            <img
                                :src="comment.user?.profile_picture"
                                alt="User Profile"
                                class="object-cover w-full h-full"
                            />
                        </div>
                        <div class="flex flex-col">
                            <h4 class="text-sm font-medium">
                                {{ comment.user?.username }}
                            </h4>
                            <p class="text-sm">
                                {{ comment.comment }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $date.humanReadable(comment.updated_at) }}
                            </p>
                        </div>
                    </div>
                </template>
            </template>
            <div class="mt-4">
                <!-- Comment form -->
                <form class="space-y-2" @submit.prevent="storeComment">
                    <div class="flex flex-col">
                        <label for="comment" class="text-sm font-medium"
                            >Your Comment:</label
                        >
                        <textarea
                            v-model="comment"
                            class="form-textarea mt-1"
                            rows="3"
                            id="comment"
                            placeholder="Write your comment here..."
                        ></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-md"
                        >
                            Add Comment
                        </button>
                    </div>
                </form>

                <!-- Add more comments as needed -->
            </div>
        </div>
    </div>
</template>

<script setup>
/** IMPORTS */
import { ref } from "vue";
import axios from "axios";
import { showCustomToast } from "../../Utils/toast";
import { useTaskStore } from "../../stores/TaskStore";
/** DATA */
const comment = ref(null);
const defaultImage =
    "http://laravue-practical.local/storage/defaults/no-profile.png";
const taskStore = useTaskStore();
/** PROPS */
const props = defineProps({
    comments: {
        type: Array,
    },
    task_id: {
        type: Number,
    },
});

/** METHODS/FUNCTIONS */
const storeComment = function (e) {
    e.preventDefault();

    axios
        .post(
            "/api/task/comment",
            {
                task_id: props.task_id,
                comment: comment.value,
                user_id: localStorage.getItem("user_id"),
            },
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem(
                        "access_token"
                    )}`,
                },
            }
        )
        .then((response) => {
            comment.value = null;
            showCustomToast("success", "Commented", {
                // position: 'bottom-right',
            });
            taskStore.$state.updated = Math.random() * 50000;
            console.log(response);
        })
        .catch((response) => {
            console.log(response);
        });
};
</script>

<style lang="scss" scoped></style>
