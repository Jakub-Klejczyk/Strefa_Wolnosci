<script script lang="ts">
import UserSection from "../components/users_admin/UserSection.vue";
import SectionsSection from "../components/sections_admin/SectionsSection.vue";
import PostsSection from "../components/posts_admin/PostsSection.vue";

export default {
    data() {
        return {
            section: "users",
        };
    },
    name: "admin",
    components: {
        SectionsSection,
        UserSection,
        PostsSection,
    },
};
</script>

<template>
    <div class="container">
        <nav>
            <div class="header">
                <h3>Panel Administracyjny</h3>
                <p>Strefa Wolności</p>
            </div>
            <li @click="section = 'users'">Użytkownicy</li>
            <li @click="section = 'sections'">Sekcje</li>
            <li @click="section = 'posts'">Wpisy</li>
        </nav>

        <user-section v-if="section == 'users'" />
        <sections-section v-if="section == 'sections'" />
        <posts-section v-if="section == 'posts'" />
    </div>
</template>

<style lang="scss" scoped>
@import "../../scss/app.scss";
.container {
    display: flex;
}

nav {
    height: 100vh;
    width: 260px;
    background: linear-gradient(180deg, $dark-red 0%, $light-red 100%);
    color: white;

    .header {
        border-bottom: 1px solid white;
        padding: 1rem;
        p {
            font-weight: 300;
        }
    }

    li {
        padding: 1rem;
        list-style: none;
        &:hover {
            background-color: $light-red;
        }
    }
}
</style>
