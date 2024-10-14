<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue";
import {Head} from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";

</script>

<template>
    <Head title="Welcome" />
    <BaseLayout>
        <Header />
        <h1>Home sweet home</h1>
    </BaseLayout>
</template>

<style scoped>

</style>
