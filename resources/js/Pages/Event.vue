<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue";
import { Head } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
import { defineProps } from "vue";
import { useI18n } from "vue-i18n";
import { CirclePlusFilled } from "@element-plus/icons-vue";

const { t } = useI18n();
const goBack = () => {
    window.history.back();
};

const props = defineProps({
    event: Object,
});
</script>

<template>
    <Head title="Event"/>
    <BaseLayout>
        <Header/>
        <div class="wrapper mt-5 flex flex-col md:flex-row justify-between items-center">
            <el-page-header @back="goBack" :title="t('ui.actions.back')">
                <template #content>
                    <span class="text-large font-600 mr-3">{{ event.id }} | {{ event.title }}</span>
                </template>
            </el-page-header>
            <el-button type="primary" :icon="CirclePlusFilled" size="default" @click="editEvent">{{ t('ui.actions.new_performance') }}</el-button>
        </div>
    </BaseLayout>
</template>

<style scoped>
</style>
