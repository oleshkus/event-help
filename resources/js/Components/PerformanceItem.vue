<script setup>
import { ref } from "vue";
import { Delete, Edit } from "@element-plus/icons-vue";
import {ElPageHeader} from "element-plus";

const props = defineProps({
    title: String,
    duration: String,
    description: String,
    participants: String,
    location: String,
    end_time: String,
    start_time: String,
});

const durationInputMinutes = ref();
const durationInputHours = ref();
</script>

<template>
    <el-page-header :icon="null" title=" ">
        <template #content>
            <div class="flex items-center">
                <span class="text-large font-600 mr-3">{{ props.title }}</span>
            </div>
        </template>
        <template #extra>
            <div class="flex items-center">
                <el-button-group>
                    <el-button :icon="Delete" plain type="danger"></el-button>
                    <el-button :icon="Edit" class="ml-2" type="primary">Edit</el-button>
                </el-button-group>
            </div>
        </template>

        <el-descriptions :column="3" class="mt-5" size="large">
            <el-descriptions-item label="Title">{{ props.title }}</el-descriptions-item>
            <el-descriptions-item label="Duration">{{ props.duration }}</el-descriptions-item>
            <el-descriptions-item label="Approximate start time">
                <el-tag size="default">{{ props.end_time }}</el-tag>
            </el-descriptions-item>
            <el-descriptions-item label="Participants">{{ props.participants }}</el-descriptions-item>
            <el-descriptions-item label="Exit location">{{ props.location }}</el-descriptions-item>
            <el-descriptions-item label="Approximate end time">
                <el-tag size="default">{{ props.end_time }}</el-tag>
            </el-descriptions-item>
        </el-descriptions>
        <p class="mt-4 text-sm w-full">{{ props.description }}</p>
    </el-page-header>
</template>

<style lang="scss">
</style>
