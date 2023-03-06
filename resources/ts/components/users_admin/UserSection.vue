<script script lang="ts">
import axios from "axios";
import User from "../../types/User";
import DeleteMode from "../DeleteMode.vue";
import UserForm from "./UserForm.vue";

export default {
    data() {
        return {
            response: "",
            users: [] as User[],
            user: {
                id: 0,
                name: "",
                password: "",
                password2: "",
            } as User,
            error: "",
            validationError: "",
            editMode: false,
            deleteMode: false,
            isEdit: false,
        };
    },
    name: "user-section",
    components: { DeleteMode, UserForm },
    methods: {
        async fetchUsers() {
            await axios({
                method: "get",
                url: "/api/users",
            })
                .then((res) => {
                    this.users = res.data;
                })
                .catch((err) => {
                    console.log(err.message);
                    this.error = err.message;
                });
        },
        async deleteUser(id: number) {
            await axios
                .delete(`/api/users/${id}`)
                .then((res) => {
                    this.response = res.data.message;
                    this.deleteMode = false;
                    this.fetchUsers();
                })
                .catch((err) => {
                    console.log(err.message);
                    this.error = err.message;
                });
        },
        async submitUser() {
            if (!this.isEdit) {
                if (this.formValidation()) {
                    await axios({
                        method: "post",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        url: "/api/users/",
                        data: this.user,
                    })
                        .then((res) => {
                            this.response = res.data.message;
                            this.editMode = false;
                            this.fetchUsers();
                        })
                        .catch((err) => {
                            console.log(err.message);
                            this.error = err.message;
                        });
                }
            }

            if (this.isEdit) {
                if (this.user.name) {
                    await axios({
                        method: "put",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        url: `/api/users/${this.user.id}`,
                        data: this.user,
                    })
                        .then((res) => {
                            this.response = res.data.message;
                            this.editMode = false;
                            this.fetchUsers();
                        })
                        .catch((err) => {
                            console.log(err.message);
                            this.error = err.message;
                        });
                } else {
                    this.validationError =
                        "Należy uzupełnić nazwę użytkownika.";
                }
            }
        },
        formValidation() {
            this.user.name = this.user.name.trim();
            if (this.user.password && this.user.password2) {
                this.user.password = this.user.password.trim();
                this.user.password2 = this.user.password2.trim();
            }

            if (
                !this.user.name ||
                !this.user.password ||
                !this.user.password2
            ) {
                this.validationError = "Należy uzupełnić wszystkie pola.";
                return false;
            }

            if (this.user.password != this.user.password2) {
                this.validationError = "Hasła powinny być takie same.";
                return false;
            }

            if (
                !this.user.password.match(
                    /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/
                )
            ) {
                this.validationError =
                    "Hasło powinno składać się z co najmniej 6 znaków, jednej litery i jednej cyfry.";
                return false;
            }

            return true;
        },
        openDeleteMode(id: number) {
            this.deleteMode = true;
            this.user.id = id;
        },
        openEditMode(user?: User) {
            this.clearFrom();
            this.editMode = true;

            if (user) {
                this.isEdit = true;
                this.user.id = user.id;
                this.user.name = user.name;
            }
        },
        clearFrom() {
            this.user.name = "";
            this.user.password = "";
            this.user.password2 = "";
        },
    },
    async created() {
        await this.fetchUsers();
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
    <section :class="deleteMode == true || editMode == true ? 'disable' : ''">
        <div class="action">
            <h2>Użytkownicy</h2>
            <p class="response">{{ response }}</p>
            <button @click="openEditMode()">Dodaj użytkownika</button>
        </div>
        <table>
            <tr>
                <th>Nazwa</th>
                <th>Akcje</th>
            </tr>
            <tr v-for="user in users" :key="user.id">
                <td>{{ user.name }}</td>
                <td>
                    <font-awesome-icon
                        class="icon"
                        icon="fa-solid fa-pen-to-square"
                        @click="openEditMode(user)"
                    />
                    <font-awesome-icon
                        class="icon"
                        icon="fa-solid fa-trash"
                        @click="openDeleteMode(user.id)"
                    />
                </td>
            </tr>
        </table>
    </section>
    <delete-mode
        v-if="deleteMode == true"
        :text="'użytkownika'"
        :delete-object="() => deleteUser(user.id)"
        @close-delete-mode="() => (deleteMode = false)"
    />
    <user-form
        v-if="editMode == true"
        :object="user"
        :error="validationError"
        :on-object="() => submitUser()"
        :text="
            isEdit == true
                ? [
                      'Edytuj',
                      'Zostaw puste pole, jeśli nie chcesz zmieniać hasła.',
                  ]
                : ['Dodaj']
        "
        @close-edit-mode="
            () => {
                editMode = false;
                isEdit = false;
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
        width: 400px;
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
