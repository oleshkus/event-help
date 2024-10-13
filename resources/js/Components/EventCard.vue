<script setup>
import { format } from "date-fns";

const props = defineProps({
    id: Number,
    title: String,
    start_time: String,
});

defineEmits({
    open: null,
    delete: null,
});
</script>

<template>
    <el-card class="">
        <template #header>
            <div class="card-header">
                <span>{{ title }}</span>
            </div>
        </template>
        <p>{{ format(new Date(start_time), 'dd.MM.yyyy HH:mm') }}</p>
        <template #footer>
            <el-button type="primary" @click="$emit('open')" size="default">{{ $t('ui.actions.open') }}</el-button>
            <el-button type="danger" @click="$emit('delete')" plain size="default">{{ $t('ui.actions.delete') }}</el-button>
        </template>
    </el-card>
</template>

<style scoped>
</style>
