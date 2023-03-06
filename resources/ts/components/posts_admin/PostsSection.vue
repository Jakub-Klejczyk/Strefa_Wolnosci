<script script lang="ts">
import axios from "axios";
import Post from "../../types/Post";
import DeleteMode from "../DeleteMode.vue";
import PostsForm from "./PostsForm.vue";

export default {
    data() {
        return {
            posts: [] as Post[],
            post: {
                id: 0,
                title: "",
                text: "",
                img: "",
                created_at: "",
                updated_at: "",
            } as Post,
            error: "",
            response: "",
            deleteMode: false,
            editMode: false,
            isEdit: false,
            validationError: "",
        };
    },
    name: "posts-section",
    components: { DeleteMode, PostsForm },
    methods: {
        async fetchPosts() {
            await axios({
                method: "get",
                url: "/api/news",
            })
                .then((res) => {
                    this.posts = res.data;
                })
                .catch((err) => {
                    console.log(err.message);
                    this.error = err.message;
                });
        },
        async deletePost(id: number) {
            await axios
                .delete(`/api/news/${id}`)
                .then((res) => {
                    this.response = res.data.message;
                    this.deleteMode = false;
                    this.fetchPosts();
                })
                .catch((err) => {
                    console.log(err.message);
                    this.error = err.message;
                });
        },
        async submitPosts() {
            if (!this.isEdit) {
                if (this.post.title && this.post.text) {
                    console.log(this.post);
                    await axios({
                        method: "post",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        url: "/api/news/",
                        data: this.post,
                    })
                        .then((res) => {
                            this.response = res.data.message;
                            this.editMode = false;

                            this.fetchPosts();
                        })
                        .catch((err) => {
                            console.log(err.message);
                            this.error = err.message;
                        });
                } else {
                    this.validationError = "Należy uzupełnić wszystkie pola.";
                }
            }

            if (this.isEdit) {
                if (this.post.title && this.post.text) {
                    await axios({
                        method: "put",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        url: `/api/news/${this.post.id}`,
                        data: this.post,
                    })
                        .then((res) => {
                            this.response = res.data.message;
                            this.editMode = false;
                            console.log(res);
                            this.fetchPosts();
                            this.isEdit = false;
                        })
                        .catch((err) => {
                            console.log(err.message);
                            this.error = err.message;
                        });
                } else {
                    this.validationError = "Należy uzupełnić wszystkie pola.";
                }
            }
        },
        openDeleteMode(id: number) {
            this.deleteMode = true;
            this.post.id = id;
        },
        openEditMode(post?: Post) {
            this.clearFrom();
            this.editMode = true;

            if (post) {
                this.isEdit = true;
                this.post.id = post.id;
                this.post.title = post.title;
                this.post.text = post.text;
                this.post.img = post.img;
                this.post.created_at = post.created_at;
            }
        },
        clearFrom() {
            this.post.title = "";
            this.post.text = "";
            this.post.img = "";
            this.post.created_at = "";
        },
    },
    async created() {
        await this.fetchPosts();
    },
    watch: {
        response() {
            setTimeout(() => {
                this.response = "";
            }, 10000);
        },
        validationError() {
            setTimeout(() => {
                this.validationError = "";
            }, 10000);
        },
    },
};
</script>

<template>
    <section>
        <div class="action">
            <h2>Wpisy</h2>
            <p class="response">{{ response }}</p>
            <button @click="openEditMode()">Dodaj wpis</button>
        </div>
        <table>
            <tr>
                <th>Wpis</th>
                <th>Stworzono</th>
                <th>Akcje</th>
            </tr>
            <tr v-for="post in posts" :key="post.id">
                <td>{{ post.title }}</td>
                <td>{{ post.created_at.substring(0, 10) }}</td>
                <td>
                    <font-awesome-icon
                        class="icon"
                        icon="fa-solid fa-pen-to-square"
                        @click="openEditMode(post)"
                    />
                    <font-awesome-icon
                        class="icon"
                        icon="fa-solid fa-trash"
                        @click="openDeleteMode(post.id)"
                    />
                </td>
            </tr>
        </table>
    </section>
    <delete-mode
        v-if="deleteMode == true"
        :text="'użytkownika'"
        :delete-object="() => deletePost(post.id)"
        @close-delete-mode="() => (deleteMode = false)"
    />
    <posts-form
        v-if="editMode == true"
        :object="post"
        :error="validationError"
        :on-object="() => submitPosts()"
        :text="isEdit == true ? 'Edytuj' : 'Dodaj'"
        @close-edit-mode="
            () => {
                editMode = false;
                isEdit = false;
            }
        "
        @on-file-selected="
            (img) => {
                post.img = img;
            }
        "
    />
</template>

<style lang="scss" scoped>
@import "../../../scss/app.scss";

section {
    margin: 1rem;
    .action {
        display: flex;
        justify-content: space-between;
        width: calc(100vw - 292px);
        padding-bottom: 2rem;

        button {
            @include button($green);
        }

        .response {
            color: $green;
            font-weight: 500;
        }
    }

    table,
    th,
    td {
        width: 800px;
        text-align: center;
        border: 1px solid;
        border-collapse: collapse;

        .icon {
            cursor: pointer;
            width: 1.2rem;
            height: 1.2rem;
            margin: 0.5rem 1rem;
        }
    }

    th {
        padding: 0.5rem 1rem;
    }
}

.disable {
    pointer-events: none;
}
</style>
