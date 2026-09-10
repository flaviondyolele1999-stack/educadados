<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-gray-700 p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link :href="route('home')" class="flex flex-col items-center gap-2 font-medium">
                        <div class="mb-1 flex h-9 w-9 items-center justify-center rounded-md">
                            <h1 class="text-6xl mb-6">📊</h1>
                        </div>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">Sistema de Estatistica Escolar</h1>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
