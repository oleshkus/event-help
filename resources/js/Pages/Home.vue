<script setup>
import { Head } from '@inertiajs/vue3'
import {provide, ref} from 'vue'
import TabEvents from "@/Pages/TabEvents.vue";
import TabHome from "@/Pages/TabHome.vue";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import Header from "@/Components/Header.vue";
const page = ref('tab-home')
provide('page', page)
const components = {
    'tab-home': TabHome,
    'tab-events': TabEvents
}
</script>

<template>
    <Head title="Welcome" />
    <BaseLayout>
        <Header />
            <h1> {{ page }} </h1>
            <component :is="components[page]" />
    </BaseLayout>
</template>

<style scoped>

</style>
