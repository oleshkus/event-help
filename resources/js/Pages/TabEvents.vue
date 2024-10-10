<script setup lang="ts">
import { ref, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import dayjs from 'dayjs';
import type { ComponentSize, FormInstance, FormRules } from 'element-plus';

interface RuleForm {
    eventName: string;
    date: string;
}

const dialogVisible = ref(false);
const formSize = ref<ComponentSize>('default');
const ruleFormRef = ref<FormInstance | null>(null);
const ruleForm = reactive<RuleForm>({
    eventName: '',
    date: '',
});

const rules = reactive<FormRules<RuleForm>>({
    eventName: [
        { required: true, message: 'Название события обязательно', trigger: 'blur' },
        { min: 3, message: 'Название события должно быть не менее 3 символов', trigger: 'blur' },
    ],
    date: [
        { required: true, message: 'Дата обязательна', trigger: 'change' },
        { validator: (rule, value, callback) => {
                if (dayjs(value).isBefore(dayjs(), 'day')) {
                    callback(new Error('Дата не может быть в прошлом'));
                } else {
                    callback();
                }
            }, trigger: 'change' }
    ]
});

const submitForm = async (formEl: FormInstance | null) => {
    if (!formEl) return;
    await formEl.validate((valid) => {
        if (valid) {
            console.log('submit!');
        } else {
            console.log('error submit!');
        }
    });
};

const resetForm = (formEl: FormInstance | null) => {
    if (!formEl) return;
    formEl.resetFields();
};
</script>

<template>
    <el-button-group class="mt-5">
        <el-button type="primary" @click="dialogVisible = true">{{ $t('ui.actions.create') }}</el-button>
        <el-button type="danger" plain>{{ $t('ui.actions.deleteAll') }}</el-button>
    </el-button-group>

    <el-dialog v-model="dialogVisible" :title="$t('ui.actions.createEvent')" width="500">
        <el-form class="flex justify-center flex-col" ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="auto" :size="formSize" status-icon>
            <el-form-item label="Название события" prop="eventName">
                <el-input v-model="ruleForm.eventName" />
            </el-form-item>
            <el-form-item label="Дата события" prop="date">
                <el-date-picker v-model="ruleForm.date" type="date" placeholder="Выберите дату" style="width: 100%" />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="submitForm(ruleFormRef)">Создать</el-button>
                <el-button @click="resetForm(ruleFormRef)">Сбросить</el-button>
            </el-form-item>
        </el-form>
        <template #footer>
            <div class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ $t('ui.actions.cancel') }}</el-button>
                <el-button type="primary" @click="submitForm(ruleFormRef)">{{ $t('ui.actions.confirm') }}</el-button>
            </div>
        </template>
    </el-dialog>
</template>

<style scoped>
</style>
