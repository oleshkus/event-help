<script setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import { ref, reactive, markRaw, onMounted } from 'vue';
import { ElMessageBox, ElMessage } from 'element-plus';
import { useI18n } from 'vue-i18n';
import axios from 'axios';
import { Delete } from '@element-plus/icons-vue';
import EventCard from '@/Components/EventCard.vue';
import { Inertia } from '@inertiajs/inertia';
import { ElButton, ElButtonGroup, ElEmpty, ElForm, ElFormItem, ElInput, ElDatePicker, ElDialog } from 'element-plus';

// Переменные
const page = usePage();
const events = ref(page.props.value?.events || []);
const { t } = useI18n();
const dialogVisible = ref(false);
const loading = ref(true);
const form = reactive({
    title: '',
    start_time: '',
});

// Правила валидации
const rules = {
    title: [
        { required: true, message: t('form.validation.required'), trigger: 'blur' },
        { max: 80, message: t('form.validation.max', { max: 80 }), trigger: 'blur' },
    ],
    start_time: [
        { required: true, message: t('form.validation.required'), trigger: 'change' },
    ],
};
const formRef = ref(null);
const errorMessage = ref('');

const deleteAllConfirmation = () => {
    ElMessageBox.confirm(
        `${t('text.deleteAllEvents')}`,
        'Warning',
        {
            confirmButtonText: `${t('ui.actions.delete')}`,
            cancelButtonText: `${t('ui.actions.cancel')}`,
            type: 'warning',
            icon: markRaw(Delete),
        }
    )
        .then(() => {
            axios.delete('/api/events').then(() => {
                Inertia.reload();
                ElMessage({
                    type: 'success',
                    message: `${t('ui.actions.deleted')}`,
                });
            });
        })
        .catch(() => {
            ElMessage({
                type: 'info',
                message: 'Delete canceled',
            });
        });
}

const deleteConfirmation = (id) => {
    ElMessageBox.confirm(
        `${t('text.deleteEvent')}`,
        'Warning',
        {
            confirmButtonText: `${t('ui.actions.delete')}`,
            cancelButtonText: `${t('ui.actions.cancel')}`,
            type: 'warning',
            icon: markRaw(Delete),
        }
    )
        .then(() => {
            handleDelete(id);
            Inertia.reload();
            ElMessage({
                type: 'success',
                message: `${t('ui.actions.deleted')}`,
            });
        })
        .catch(() => {
            ElMessage({
                type: 'info',
                message: 'Delete canceled',
            });
        });
}

const handleOpen = async (id) => {
    Inertia.get(`/api/events/${id}`);
}

const handleDelete = async (id) => {
    await axios.delete(`/api/events/${id}`);
    Inertia.reload();
}

const handleSubmit = async () => {
    if (!formRef.value) return;
    await formRef.value.validate(async (valid) => {
        if (valid) {
            try {
                await axios.post('/api/events', form);
                Inertia.reload();
                dialogVisible.value = false;
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    errorMessage.value = 'Ошибка валидации: ' + error.response.data.message;
                } else {
                    console.error('Error creating event:', error);
                }
            }
        }
    });
};

const handleReset = () => {
    if (!formRef.value) return;
    formRef.value.resetFields();
};

onMounted(() => {
    loading.value = false;
});
</script>

<template>
    <Head title="Events"/>
    <BaseLayout>
        <Header/>
        <el-button-group class="mt-5">
            <el-button type="primary" @click="dialogVisible = true">{{ t('ui.actions.create') }}</el-button>
            <el-button type="danger" @click="deleteAllConfirmation" plain>{{ t('ui.actions.deleteAll') }}</el-button>
        </el-button-group>

        <div v-loading="loading" class="flex min-h-[20rem] justify-center events-container">
            <el-empty v-if="!loading && events.length === 0" :description="$t('text.noevents')"/>
            <div
                class="grid w-full grid-rows-2 xs:grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                <event-card @delete="deleteConfirmation(event.id)" @open="handleOpen(event.id)" v-for="event in events"
                            :key="event.id" :id="event.id" :title="event.title" :start_time="event.start_time"/>
            </div>
        </div>

        <el-dialog v-model="dialogVisible" :title="t('ui.actions.createEvent')" width="500">
            <el-form ref="formRef" :model="form" :rules="rules" class="flex justify-center flex-col" label-width="auto"
                     status-icon>
                <el-form-item label="Название события" prop="title">
                    <el-input v-model="form.title"/>
                </el-form-item>
                <el-form-item label="Дата и время начала" prop="start_time">
                    <el-date-picker v-model="form.start_time" type="datetime" placeholder="Выберите дату и время"
                                    style="width: 100%"/>
                </el-form-item>
            </el-form>
            <template #footer>
                <div class="dialog-footer">
                    <el-button type="danger" @click="handleReset">{{ t('ui.actions.reset') }}</el-button>
                    <el-button @click="dialogVisible = false">{{ t('ui.actions.cancel') }}</el-button>
                    <el-button type="primary" @click="handleSubmit">{{ t('ui.actions.create') }}</el-button>
                </div>
            </template>
            <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
        </el-dialog>
    </BaseLayout>
</template>

<style scoped>
.events-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    position: relative;
    margin-top: 20px;
    width: 100%;
    box-sizing: border-box;
}
</style>
