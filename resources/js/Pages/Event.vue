<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue";
import {Head} from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
import {defineProps, ref, reactive, onMounted} from "vue";
import {useI18n} from "vue-i18n";
import {CirclePlusFilled, Delete, Minus, Plus} from "@element-plus/icons-vue";
import {ElEmpty, ElInput, ElButton, ElForm, ElFormItem, ElTooltip, ElMessage} from "element-plus";
import axios from "axios";
import PerformanceItem from "@/Components/PerformanceItem.vue";
import PerformanceItemForm from "@/Components/PerformanceItemForm.vue";
import {Inertia} from "@inertiajs/inertia";

const {t} = useI18n();
const goBack = () => {
    window.history.back();
};

const curTime = ref(new Date().toLocaleTimeString());
const curDate = ref(new Date().toLocaleDateString());

const props = defineProps({
    event: Object,
    performances: Array,
});

const loading = ref(true);
const isEditing = ref(false);
const newPerformance = reactive({
    title: '',
    expected_duration: {hours: 0, minutes: 0},
    location: '',
    participants: [],
    description: '',
    start_time: '',
    end_time: ''
});

const addParticipant = () => {
    newPerformance.participants.push('');
};

const removeParticipant = (index) => {
    newPerformance.participants.splice(index, 1);
};

const validateForm = () => {
    if (!newPerformance.title || (!newPerformance.expected_duration.hours && !newPerformance.expected_duration.minutes) || !newPerformance.participants.length) {
        ElMessage.error('Пожалуйста, заполните все обязательные поля.');
        return false;
    }
    return true;
};

const calculateTimes = () => {
    if (props.performances.length === 0) {
        newPerformance.start_time = props.event.start_time;
    } else {
        const lastPerformance = props.performances[props.performances.length - 1];
        const lastEndTime = new Date(lastPerformance.end_time);
        const durationMinutes = newPerformance.expected_duration.hours * 60 + newPerformance.expected_duration.minutes;
        newPerformance.start_time = new Date(lastEndTime.getTime() + durationMinutes * 60000).toLocaleTimeString();
    }
    const startTime = new Date(newPerformance.start_time);
    const durationMinutes = newPerformance.expected_duration.hours * 60 + newPerformance.expected_duration.minutes;
    newPerformance.end_time = new Date(startTime.getTime() + durationMinutes * 60000).toLocaleTimeString();
};

const savePerformance = async () => {
    if (!validateForm()) return;
    calculateTimes();
    try {
        await axios.post(`/api/events/${props.event.id}/performances`, {
            title: newPerformance.title,
            expected_duration: newPerformance.expected_duration.hours * 60 + newPerformance.expected_duration.minutes,
            location: newPerformance.location || 'Empty',
            participants: newPerformance.participants.length ? newPerformance.participants : ['Empty'],
            description: newPerformance.description || 'Empty',
            start_time: newPerformance.start_time,
            end_time: newPerformance.end_time
        });
        ElMessage.success('Выступление успешно сохранено.');
        isEditing.value = false;
        Inertia.reload();
        // Обновить список выступлений
    } catch (error) {
        ElMessage.error('Ошибка при сохранении выступления.');
        console.error('Ошибка при сохранении выступления:', error);
    }
};

onMounted(() => {
    loading.value = false;
    setInterval(() => {
        curTime.value = new Date().toLocaleTimeString();
        curDate.value = new Date().toLocaleDateString()
    }, 1000);
});
</script>

<template>
    <Head title="Event"/>
    <BaseLayout class="">
        <Header/>
        <div class="wrapper mt-5 flex flex-col md:flex-row justify-between items-center">
            <el-page-header @back="goBack" :title="t('ui.actions.back')">
                <template #content>
                    <span class="text-large font-600 mr-3">{{ event.id }} | {{ event.title }}</span>
                </template>
            </el-page-header>
            <div class="right">
                <el-button type="primary" :icon="CirclePlusFilled" size="default" @click="isEditing = true"
                           v-if="!isEditing">{{ t('ui.actions.new_performance') }}
                </el-button>
            </div>
        </div>

        <div class="flex justify-center text-center mx-auto flex-col w-fit">
            <p class="text-center">Текущее время и дата</p>
            <div class="time w-36 text-center px-6 py-4 border-2 rounded-md">
                <span>{{ curTime }}</span>
                <br>
                <span>{{ curDate }}</span>
            </div>
        </div>
        <div class="flex justify-center text-center mx-auto flex-col w-fit">
            <p class="text-center">{{ t('text.event_start') }}</p>
            <div class="time w-36 text-center px-6 py-4 border-2 rounded-md">
                <span>{{ props.event.start_time }}</span>
            </div>
        </div>

        <div v-loading="loading" class="wrapper">
            <el-empty v-if="!loading && performances.length === 0" description="Нет выступлений"/>
        </div>
        <div class="wrapper flex w-full items-center justify-center flex-col">
            <performance-item v-for="performance in performances" :key="performance.id" :performance="performance"
                              class="w-[70%] border-2 p-6 rounded-lg"></performance-item>
            <performance-item-form v-if="isEditing" :performance="newPerformance" @save="savePerformance"
                                   class="w-[70%] border-2 p-6 rounded-lg"></performance-item-form>
        </div>
    </BaseLayout>
</template>

<style scoped>
.participant-input {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}
</style>
