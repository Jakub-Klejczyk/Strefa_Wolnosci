<script script lang="ts">
export default {
    data() {
        return {};
    },
    props: {
        text: String,
        deleteObject: { type: Function, required: true },
    },
};
</script>

<template>
    <div class="container">
        <h3>Czy usunać {{ text }}?</h3>
        <div class="btns">
            <button class="add" @click="() => deleteObject()">Tak</button>
            <button class="del" @click="$emit('close-delete-mode', null)">
                Nie
            </button>
        </div>
    </div>
</template>

<style lang="scss" scoped>
@import "../../scss/app.scss";
.container {
    @include editMode;
}
</style>
