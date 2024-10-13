<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import type { FormInstance, FormRules } from 'element-plus';
import { useI18n } from 'vue-i18n';
import axios from 'axios';
import EventCard from "@/Components/EventCard.vue";

const { t } = useI18n();
const dialogVisible = ref(false);
const loading = ref(true);
const events = ref([]);
const form = reactive({
    title: '',
    start_time: '',
});
const rules = reactive<FormRules>({
    title: [
        { required: true, message: t('fields.validation.required'), trigger: 'blur' },
        { max: 80, message: t('fields.validation.max', { max: 80 }), trigger: 'blur' },
    ],
    start_time: [
        { required: true, message: t('fields.validation.required'), trigger: 'change' },
    ],
});
const formRef = ref<FormInstance>();
const errorMessage = ref('');

const fetchEvents = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/events');
        events.value = response.data;
    } catch (error) {
        console.error('Error fetching events:', error);
    } finally {
        setTimeout(() => {
            loading.value = false;
        }, 1000);
    }
};

const handleSubmit = async () => {
    if (!formRef.value) return;
    await formRef.value.validate(async (valid) => {
        if (valid) {
            try {
                await axios.post('/api/events', form);
                await fetchEvents();
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

const handleOpen = (id: number) => {
    console.log(`Open event with id: ${id}`);
};

const handleDelete = async (id: number) => {
    try {
        await axios.delete(`/api/events/${id}`);
        await fetchEvents();
    } catch (error) {
        console.error('Error deleting event:', error);
    }
};

onMounted(() => {
    fetchEvents();
});
</script>

<template>
    <el-button-group class="mt-5">
        <el-button type="primary" @click="dialogVisible = true">{{ t('ui.actions.create') }}</el-button>
        <el-button type="danger" plain>{{ t('ui.actions.deleteAll') }}</el-button>
    </el-button-group>

    <div v-loading="loading" class="events-container">
        <el-empty v-if="!loading && events.length === 0" description="You have no events" />
        <div class="container grid xs:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mt-5">
            <event-card v-for="event in events" :key="event.id" :id="event.id" :title="event.title" :start_time="event.start_time" @open="handleOpen(event.id)" @delete="handleDelete(event.id)" />
        </div>
    </div>

    <el-dialog v-model="dialogVisible" :title="t('ui.actions.createEvent')" width="500">
        <el-form ref="formRef" :model="form" :rules="rules" class="flex justify-center flex-col" label-width="auto" status-icon>
            <el-form-item label="Название события" prop="title">
                <el-input v-model="form.title" />
            </el-form-item>
            <el-form-item label="Дата и время начала" prop="start_time">
                <el-date-picker v-model="form.start_time" type="datetime" placeholder="Выберите дату и время" style="width: 100%" />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="handleSubmit">Создать</el-button>
                <el-button @click="handleReset">Сбросить</el-button>
            </el-form-item>
        </el-form>
        <template #footer>
            <div class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ t('ui.actions.cancel') }}</el-button>
                <el-button type="primary" @click="handleSubmit">{{ t('ui.actions.confirm') }}</el-button>
            </div>
        </template>
        <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
    </el-dialog>
</template>

<style scoped>
.events-container {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    position: relative;
}
</style>
