<script setup>
import { ref, reactive, watch } from "vue";
import { ElInput, ElInputNumber, ElButton, ElForm, ElFormItem, ElPageHeader, ElTag, ElMessage } from "element-plus";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const props = defineProps({
    performance: Object,
});

const formState = reactive({
    title: '',
    durationHours: 0,
    durationMinutes: 0,
    description: '',
    participants: '',
    location: '',
    start_time: '',
    end_time: ''
});

// Синхронизация ��окального состояния с props
watch(() => props.performance, (newVal) => {
    Object.assign(formState, newVal);
}, { immediate: true });

const rules = {
    title: [
        { required: true, message: t('form.validation.required'), trigger: 'blur' }
    ],
    participants: [
        { required: true, message: t('form.validation.required'), trigger: 'blur' }
    ],
    durationHours: [
        { type: 'number', min: 0, max: 23, required: true, nullable: true, message: t('form.validation.required'), trigger: 'blur' }
    ],
    durationMinutes: [
        { type: 'number', min: 0, max: 59, required: true, nullable: true, message: t('form.validation.required'), trigger: 'blur' }
    ]
};

const formRef = ref(null);

const savePerformance = () => {
    formRef.value.validate((valid) => {
        if (valid) {
            // Сохранение данных
            ElMessage.success('Форма успешно сохранена.');
        } else {
            ElMessage.error('Пожалуйста, заполните все обязательные поля.');
            return false;
        }
    });
};
</script>

<template>
    <el-page-header>
        <template #content>
            <div class="flex items-center">
                <span class="text-sm font-600">{{ t('ui.actions.edit') }}</span>
            </div>
        </template>
        <template #extra>
            <div class="flex items-center">
                <el-button type="primary" @click="savePerformance">{{ t('ui.actions.save') }}</el-button>
            </div>
        </template>
        <el-form :model="formState" :rules="rules" ref="formRef" class="mt-5" label-width="auto">
            <el-form-item :label="t('text.title')" prop="title">
                <el-input v-model="formState.title" :placeholder="t('text.title')"></el-input>
            </el-form-item>
            <el-form-item :label="t('text.participants')" prop="participants">
                <el-input v-model="formState.participants" :placeholder="t('text.participants')"></el-input>
            </el-form-item>
            <el-form-item :label="t('text.duration')" prop="durationHours">
                <el-input-number v-model="formState.durationHours" :placeholder="t('text.hours')" :label="t('text.hours')"></el-input-number>
                <el-input-number v-model="formState.durationMinutes" :placeholder="t('text.minutes')" :label="t('text.minutes')"></el-input-number>
            </el-form-item>
            <el-form-item :label="t('text.location')">
                <el-input v-model="formState.location" :placeholder="t('text.location')"></el-input>
            </el-form-item>
            <el-form-item :label="t('text.description')">
                <el-input type="textarea" v-model="formState.description" :placeholder="t('text.description')"></el-input>
            </el-form-item>
            <el-form-item :label="t('text.start_time')">
                <el-tag size="default">{{ formState.start_time }}</el-tag>
            </el-form-item>
            <el-form-item :label="t('text.end_time')">
                <el-tag size="default">{{ formState.end_time }}</el-tag>
            </el-form-item>
        </el-form>
    </el-page-header>
</template>

<style lang="scss">
</style>
