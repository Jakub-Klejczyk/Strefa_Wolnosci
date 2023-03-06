<script script lang="ts">
import axios from "axios";
import Section from "../../types/Section";
import DeleteMode from "../DeleteMode.vue";
import SectionsForm from "./SectionsForm.vue";

export default {
    data() {
        return {
            sections: [] as Section[],
            section: {
                id: 0,
                section: "",
                text: "",
            } as Section,
            error: "",
            response: "",
            deleteMode: false,
            editMode: false,
            isEdit: false,
            validationError: "",
        };
    },
    name: "sections-section",
    components: { DeleteMode, SectionsForm },
    methods: {
        async fetchSections() {
            await axios({
                method: "get",
                url: "/api/sections",
            })
                .then((res) => {
                    this.sections = res.data;
                })
                .catch((err) => {
                    console.log(err.message);
                    this.error = err.message;
                });
        },
        async deleteSection(id: number) {
            await axios
                .delete(`/api/sections/${id}`)
                .then((res) => {
                    this.response = res.data.message;
                    this.deleteMode = false;
                    this.fetchSections();
                })
                .catch((err) => {
                    console.log(err.message);
                    this.error = err.message;
                });
        },
        async submitSection() {
            if (!this.isEdit) {
                if (this.section.section && this.section.text) {
                    await axios({
                        method: "post",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        url: "/api/sections/",
                        data: this.section,
                    })
                        .then((res) => {
                            this.response = res.data.message;
                            this.editMode = false;
                            this.fetchSections();
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
                if (this.section.section && this.section.text) {
                    await axios({
                        method: "put",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        url: `/api/sections/${this.section.id}`,
                        data: this.section,
                    })
                        .then((res) => {
                            this.response = res.data.message;
                            this.editMode = false;
                            this.fetchSections();
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
            this.section.id = id;
        },
        openEditMode(section?: Section) {
            this.clearFrom();
            this.editMode = true;

            if (section) {
                this.isEdit = true;
                this.section.id = section.id;
                this.section.section = section.section;
                this.section.text = section.text;
            }
        },
        clearFrom() {
            this.section.section = "";
            this.section.text = "";
        },
    },
    async created() {
        await this.fetchSections();
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
            <h2>Sekcje</h2>
            <p class="response">{{ response }}</p>
            <button @click="openEditMode()">Dodaj sekcje</button>
        </div>
        <table>
            <tr>
                <th>Sekcja</th>
                <th>Akcje</th>
            </tr>
            <tr v-for="section in sections" :key="section.id">
                <td>{{ section.section }}</td>
                <td>
                    <font-awesome-icon
                        class="icon"
                        icon="fa-solid fa-pen-to-square"
                        @click="openEditMode(section)"
                    />
                    <font-awesome-icon
                        class="icon"
                        icon="fa-solid fa-trash"
                        @click="openDeleteMode(section.id)"
                    />
                </td>
            </tr>
        </table>
    </section>
    <delete-mode
        v-if="deleteMode == true"
        :text="'użytkownika'"
        :delete-object="() => deleteSection(section.id)"
        @close-delete-mode="() => (deleteMode = false)"
    />
    <sections-form
        v-if="editMode == true"
        :object="section"
        :error="validationError"
        :on-object="() => submitSection()"
        :text="isEdit == true ? 'Edytuj' : 'Dodaj'"
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
