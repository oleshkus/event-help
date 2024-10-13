<script setup>
import { usePage, router } from "@inertiajs/vue3";
import { inject, ref } from 'vue';
import { Money, Reading, Setting, SwitchButton, User } from "@element-plus/icons-vue";

const user = ref(usePage().props.user);
const page = inject('page');
const dialogVisible = ref(false);
const logged = usePage().props.logged_in;
</script>

<template>
    <el-menu mode="horizontal" class="flex items-center justify-between">
        <div class="left flex items-center">
            <el-menu-item index="1" @click="page = 'tab-home'">{{ $t('ui.tabs.home') }}</el-menu-item>
            <el-menu-item index="2" v-if="logged" @click="page = 'tab-events'">{{ $t('ui.tabs.events') }}</el-menu-item>
        </div>
        <div class="right flex items-center">
            <p v-if="logged" class="mr-3">{{ user.name }}</p>
            <el-sub-menu index="3">
                <template #title>
                    <el-icon><User /></el-icon>
                    {{ $t('ui.tabs.account') }}
                </template>
                <el-sub-menu index="4">
                    <template #title>
                        <el-icon><Reading /></el-icon>
                        {{ $t('ui.tabs.language') }}
                    </template>
                    <el-menu-item v-for="locale in $i18n.availableLocales" :key="`locale-${locale}`" @click="$i18n.locale = locale">
                        {{ locale }}
                    </el-menu-item>
                </el-sub-menu>
                <div class="logged" v-if="usePage().props.logged_in">
                    <el-menu-item @click="dialogVisible = true">
                        <el-icon><Setting /></el-icon>
                        {{ $t('ui.tabs.settings') }}
                    </el-menu-item>
                    <el-menu-item>
                        <el-icon><Money /></el-icon>
                        {{ $t('ui.tabs.subscription') }}
                    </el-menu-item>
                    <el-menu-item @click="router.post('/logout')">
                        <el-icon><SwitchButton /></el-icon>
                        {{ $t('ui.tabs.exit') }}
                    </el-menu-item>
                </div>
                <div v-else>
                    <el-menu-item @click="router.get('/login')">{{ $t('ui.tabs.signin') }}</el-menu-item>
                    <el-menu-item @click="router.get('/register')">{{ $t('ui.tabs.register') }}</el-menu-item>
                </div>
            </el-sub-menu>
        </div>
    </el-menu>

    <el-dialog
        v-model="dialogVisible"
        title="Tips"
        width="500"
    >
        <span>This is a message</span>
        <template #footer>
            <div class="dialog-footer">

            </div>
        </template>
    </el-dialog>
</template>

<style scoped>
</style>
