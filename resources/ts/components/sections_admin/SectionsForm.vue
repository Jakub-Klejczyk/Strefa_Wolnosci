<script script lang="ts">
import Editor from "@tinymce/tinymce-vue";

export default {
    data() {
        return {};
    },
    props: {
        object: { type: Object, required: true },
        onObject: { type: Function, required: true },
        error: String,
        text: { type: String, required: true },
    },
    components: { Editor },
};
</script>

<template>
    <form action="" @submit.prevent="() => onObject()">
        <h3>{{ text }} sekcję</h3>
        <div class="elem">
            <label for="name">Sekcja:</label>
            <input type="text" v-model="object.section" />
        </div>
        <div class="elem">
            <label for="name">Tekst:</label>
            <Editor
                v-model="object.text"
                api-key="uaghmy4zukvafc7ske27pkqp0u56q7x3zdp3ss7dz078k9xa"
            />
        </div>
        <p class="val-error">{{ error }}</p>
        <div class="btns">
            <input type="submit" class="add" value="Zapisz" />
            <button
                type="button"
                class="del"
                @click="$emit('close-edit-mode', null)"
            >
                Anuluj
            </button>
        </div>
    </form>
</template>

<style lang="scss" scoped>
@import "../../../scss/app.scss";

form {
    @include editMode;
    width: min(40%, 600px);

    .elem {
        padding-top: 1rem;
        display: flex;
        flex-direction: column;

        input {
            padding: 0.5rem;
            border: none;
            border-bottom: 1px solid black;
            &:focus {
                outline: none;
            }
        }
    }

    .val-error {
        padding-top: 1rem;
        font-weight: 500;
        color: $dark-red;
    }

    .info {
        font-weight: 300;
        font-size: 0.8rem;
    }
}
</style>
