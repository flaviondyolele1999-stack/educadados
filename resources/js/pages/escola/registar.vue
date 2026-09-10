<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    nome: '',
    provincia: '',
    municipio: '',
    telefone: ''
});

function submit() {
    form.post(route('escola.register.store'), {
        onError: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>

    <Head title="Registar Escola" />

    <div class="grid grid-cols-1 lg:grid-cols-2 min-h-screen">
        <!-- Lado informativo -->
        <div class="flex flex-col justify-center items-center bg-blue-700 text-white p-10">
            <h1 class="text-6xl mb-6">📊</h1>
            <h2 class="text-3xl font-bold mb-2 text-center">Ajude o Estado de Angola</h2>
            <p class="text-center text-lg leading-relaxed max-w-md">
                Plataforma pública para fornecer e gerir informações estatísticas escolares em todo território nacional.
            </p>
        </div>

    
        <!-- Lado do formulário -->
        <div class="flex justify-center items-center p-10 bg-white dark:bg-gray-900 min-h-screen">
            <form @submit.prevent="submit" class="w-full max-w-2xl space-y-6 bg-white p-8 rounded-xl">
                <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Registrar Escola</h1>

                <InputError :message="form.errors.msg" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input v-model="form.name" type="text" placeholder="Nome do Responsável" required
                            :disabled="form.processing"
                            class="p-3 border rounded-lg bg-white text-gray-800 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-60"
                            :class="form.errors.name ? 'border-red-500' : 'border-gray-300'" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div>
                        <input v-model="form.email" type="email" placeholder="Email" required
                            :disabled="form.processing"
                            class="p-3 border rounded-lg bg-white text-gray-800 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-60"
                            :class="form.errors.email ? 'border-red-500' : 'border-gray-300'" />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div>
                        <input v-model="form.password" type="password" placeholder="Senha" required minlength="6"
                            :disabled="form.processing"
                            class="p-3 border rounded-lg bg-white text-gray-800 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-60"
                            :class="form.errors.password ? 'border-red-500' : 'border-gray-300'" />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div>
                        <input v-model="form.password_confirmation" type="password" placeholder="Confirmar Senha"
                            required minlength="6" :disabled="form.processing"
                            class="p-3 border border-gray-300 rounded-lg bg-white text-gray-800 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-60" />
                    </div>

                    <div>
                        <input v-model="form.nome" type="text" placeholder="Nome da Escola" required
                            :disabled="form.processing"
                            class="p-3 border rounded-lg bg-white text-gray-800 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-60"
                            :class="form.errors.nome ? 'border-red-500' : 'border-gray-300'" />
                        <InputError :message="form.errors.nome" />
                    </div>

                    <div>
                        <input v-model="form.provincia" type="text" placeholder="Província" :disabled="form.processing"
                            class="p-3 border border-gray-300 rounded-lg bg-white text-gray-800 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-60" />
                    </div>

                    <div>
                        <input v-model="form.municipio" type="text" placeholder="Município" :disabled="form.processing"
                            class="p-3 border border-gray-300 rounded-lg bg-white text-gray-800 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-60" />
                    </div>

                    <div>
                        <input v-model="form.telefone" type="text" placeholder="Telefone" :disabled="form.processing"
                            class="p-3 border border-gray-300 rounded-lg bg-white text-gray-800 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-60" />
                    </div>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <a href="/" class="text-blue-600 hover:underline text-sm">← Voltar para a Página Inicial</a>
                    <button type="submit" :disabled="form.processing"
                        class="flex items-center justify-center gap-2 bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-800 transition disabled:opacity-70 disabled:cursor-not-allowed min-w-[180px]">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        {{ form.processing ? 'Registrando...' : 'Registrar Escola' }}
                    </button>
                </div>
            </form>
        </div>

    </div>
</template>
