<template>
    <div>
        <form @submit.prevent="updateComment">
            <textarea
                id="comment"
                type="text"
                placeholder="Enter comment"
                v-model="comment"
                @input="handleMentionUser"
                rows="3"
                class="w-full px-4 py-2 mt-1 border rounded-md"
            ></textarea>
            <ul v-if="showDropdown" class="cursor-pointer">
                <li
                    v-for="username in usernames"
                    :key="username"
                    @click="insertMention(username)"
                >
                    @{{ username.username }}
                </li>
            </ul>
            <span v-if="errors.comment" class="text-red-500">{{
                            errors.comment[0]
                        }}</span>
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
import { ref, onMounted, inject } from "vue";
import axios from "axios";
import { useTaskStore } from "../../stores/TaskStore";
import { showCustomToast } from "../../Utils/toast.js";
const mentionedUsers = ref([])
/** DATA */
const comment = ref(null);
const taskState = useTaskStore();
const usernames = ref([]);
const showDropdown = ref(false);
const errors = ref([]);
const $localStorage = inject("$localStorage");
const access_token = $localStorage.getItem("access_token");
/** EMITS */
const emit = defineEmits(["close-modal", "comment"]);

/** METHODS */

const handleMentionUser = function () {
    // console.log('wtf');
    const mentionRegex = /@(\w+)/g;
    const matches = comment.value.match(mentionRegex);
    console.log(matches);
    if (matches && matches.length > 0) {
        console.log(matches, matches[matches.length - 1], "AYO");
        const lastMatch = matches[matches.length - 1];
        const username = lastMatch.substr(1); // Remove the "@" symbol
        fetchMatchUsers(username);
    } else {
        showDropdown.value = false;
    }
};

const fetchMatchUsers = function (match_string) {
    axios
        .post(
            "/api/getMatchedUsers",
            { user_match: match_string, task_id: props.comment.task_id },
            {
                headers: {
                    Authorization: `Bearer ${access_token}`,
                },
            }
        )
        .then((response) => {
            console.log(response);
            usernames.value = response.data;
            showDropdown.value = true;
        })
        .catch((error) => {
            console.log(error);
            showDropdown.value = false;
        });
};

const insertMention = function (username) {
    const mention = `@${username.username} `;
    comment.value = comment.value.replace(/@(\w+)$/, mention);
    mentionedUsers.value.push(username.username);
    showDropdown.value = false;
};

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
            emit("comment", {
                comment: comment.value,
                comment_id: props.comment.id,
            });
            mentionedUsers.value = [];
        })
        .catch((error) => {
            console.log(error);
            if (error.response?.status === 422) {
                // Handle validation errors
                errors.value = error.response.data.errors;
            }
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
