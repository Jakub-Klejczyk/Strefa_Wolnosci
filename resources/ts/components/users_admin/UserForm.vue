<script script lang="ts">
export default {
    data() {
        return {};
    },
    props: {
        object: { type: Object, required: true },
        onObject: { type: Function, required: true },
        error: String,
        text: { type: Array, required: true },
    },
};
</script>

<template>
    <form action="" @submit.prevent="() => onObject()">
        <h3>{{ text[0] }} użytkownika</h3>
        <div class="elem">
            <label for="name">Nazwa:</label>
            <input type="text" v-model="object.name" />
        </div>
        <div class="elem">
            <label for="password">Hasło:</label>
            <input type="password" v-model="object.password" />
            <p class="info">{{ text[1] }}</p>
        </div>
        <div class="elem">
            <label for="password2">Powtórz hasło:</label>
            <input type="password" v-model="object.password2" />
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
    width: min(30%, 400px);

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
