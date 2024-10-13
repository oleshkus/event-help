<script setup>

import BaseLayout from "@/Layouts/BaseLayout.vue";
import {Head} from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
</script>

<template>
    <Head title="Event" />
    <BaseLayout>
        <Header />

<!--        <component :is="components[page]" />-->
    </BaseLayout>
</template>

<style scoped>

</style>
