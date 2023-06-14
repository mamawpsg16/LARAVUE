<template>
    <BaseModal v-model="isModalOpen">
        <template #modal-title>Edit Comment</template>
        <EditComment
            @comment="getEditedComment"
            @close-modal="emitCloseModal"
            :comment="edit_comment"
        />
    </BaseModal>
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
                            <div class="flex items-center">
                                <h4 class="text-sm font-medium">
                                    {{ comment.user?.username }}
                                </h4>
                                <span class="text-xs text-gray-500 mx-2">
                                    <!-- Added mx-2 class for spacing -->
                                    {{
                                        $date.humanReadable(comment.updated_at)
                                    }}
                                </span>
                            </div>
                            <p class="text-sm">
                                {{ comment.comment }}
                            </p>
                            <p
                                v-if="comment.user_id == auth_user_id"
                                class="space-x-3"
                            >
                                <button
                                    class="text-sm"
                                    type="button"
                                    @click="toggleModal(comment)"
                                >
                                    Edit
                                </button>
                                <button
                                    class="text-sm"
                                    type="button"
                                    @click="deleteComment(comment.id, task_id)"
                                >
                                    Delete
                                </button>
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
                            class="form-textarea mt-1 text-blue-500 p-2"
                            rows="3"
                            id="comment"
                            placeholder="Write your comment here..."
                            @input="handleMentionUser"
                        ></textarea>
                        <ul v-if="showDropdown" class="cursor-pointer">
                            <li v-for="username in usernames" :key="username" @click="insertMention(username)">@{{ username.username }}</li>
                        </ul>
                        <span v-if="errors.comment" class="text-red-500">{{
                            errors.comment[0]
                        }}</span>
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
import { ref, inject, onMounted } from "vue";
import axios from "axios";
import { showCustomToast } from "../../Utils/toast";
import { useTaskStore } from "../../stores/TaskStore";
import BaseModal from "../../components/BaseModal.vue";
import EditComment from "./EditComment.vue";
import Swal from "../../Utils/swal.js";
// import EditComment from './EditComment.vue'
/** DATA */
const comment = ref(null);
const auth_user_id = ref(null);
const edit_comment = ref(null);
const usernames = ref([]);
const showDropdown = ref(false);
const formattedComment = ref(comment.value);
const mentionedUsers = ref([])
const defaultImage =
    "http://laravue-practical.local/storage/defaults/no-profile.png";
const taskStore = useTaskStore();
const isModalOpen = ref(false);
const errors = ref([]);
const $localStorage = inject("$localStorage");
const access_token = $localStorage.getItem("access_token");

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
const toggleModal = function (comment = null) {
    edit_comment.value = comment;
    isModalOpen.value = !isModalOpen.value;
};

const emitCloseModal = (data) => {
    isModalOpen.value = data;
};

const getUserDetails = function () {
    // console.log(localStorage.getItem('access_token'));
    // return false;
    let access_token = $localStorage.getItem("access_token");
    axios
        .get("/api/profile", {
            headers: {
                Authorization: `Bearer ${access_token}`,
            },
        })
        .then((response) => {
            auth_user_id.value = response.data.id;
        })
        .catch((error) => {
            console.log(error);
        });
};

const handleMentionUser = function(){
    // console.log('wtf');
    const mentionRegex = /@(\w+)/g;
    const matches = comment.value.match(mentionRegex);
    console.log(matches);
    if (matches && matches.length > 0) {
        console.log(matches,matches[matches.length - 1],'AYO')
        const lastMatch = matches[matches.length - 1];
        const username = lastMatch.substr(1); // Remove the "@" symbol
        fetchMatchUsers(username);
    } else {
        showDropdown.value = false;
    }
   
};

const fetchMatchUsers = function(match_string){
    axios.post('/api/getMatchedUsers',{ user_match : match_string, 'task_id' : props.task_id }, { headers:{
        'Authorization': `Bearer ${access_token}` 
    }}).then((response) => {
        console.log(response);
        usernames.value = response.data;
        showDropdown.value = true;
    }).catch((error) => {
        console.log(error);
        showDropdown.value = false;
    })
};

const insertMention = function(username) {
      const mention = `@${username.username} `;
      comment.value = comment.value.replace(/@(\w+)$/, mention);
      mentionedUsers.value.push(username.username);
      showDropdown.value = false;
};
const getEditedComment = (data) => {
    let commentIndex = props.comments.findIndex(
        (comment) => data.comment_id == comment.id
    );
    if (commentIndex !== -1) {
        props.comments[commentIndex].comment = data.comment;
    }
};

const deleteComment = function (comment_id, task_id) {
    Swal("Delete Comment!", "Are you sure?", "warning", "Yes", {
        showCancelButton: true,
        cancelButtonText: "No",
        customClass: {
            confirmButton: "btn-success",
            cancelButton: "btn-danger",
        },
    }).then((result) => {
        if (result.value) {
            axios
                .delete("/api/task/" + comment_id, {
                    headers: {
                        Authorization: `Bearer ${access_token}`,
                    },
                })
                .then((response) => {
                    const index = props.comments.findIndex(
                        (comment) => comment.id === comment_id
                    );
                    if (index !== -1) {
                        console.log(index, "INDEX");
                        props.comments.splice(index, 1);
                        showCustomToast("success", "Comment Deleted!");
                    }
                })
                .catch((error) => {});
        }
    });
};

const storeComment = function (e) {
    e.preventDefault();

    axios
        .post(
            "/api/comment",
            {
                task_id: props.task_id,
                comment: comment.value,
                user_id: localStorage.getItem("user_id"),
                mentioned_usernames: mentionedUsers.value
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
            mentionedUsers.value = [];
            comment.value = null;
            console.log(response);
        })
        .catch((error) => {
            if (error.response?.status === 422) {
                // Handle validation errors
                // if (error.response.data.errors.not_exists?.length) {
                //     showCustomToast(
                //         "error",
                //         error.response.data.errors.not_exists,
                //         {}
                //     );
                // }

                errors.value = error.response.data.errors;
            } else {
                // Handle other errors
                console.log(error);
            }
        });
};
onMounted(() => {
    getUserDetails();
});
</script>

<style lang="scss" scoped></style>
