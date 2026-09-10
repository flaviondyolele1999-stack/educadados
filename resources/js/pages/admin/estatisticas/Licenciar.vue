<template>
    <LayoutPrincipal>
        <div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <div class="max-w-3xl mx-auto">
                <div class="mb-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2">
                        📄 Licenciamento
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                        Estado da licença de uso da plataforma para a sua instituição.
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-8 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                                {{ escola.nome }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ [escola.municipio, escola.provincia].filter(Boolean).join(', ') || 'Localização não informada' }}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                            Licença Ativa
                        </span>
                    </div>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Plano</dt>
                            <dd class="font-medium text-gray-800 dark:text-white">Gratuito</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Registada desde</dt>
                            <dd class="font-medium text-gray-800 dark:text-white">{{ dataRegisto }}</dd>
                        </div>
                    </dl>

                    <p class="text-sm text-gray-600 dark:text-gray-300 border-t dark:border-gray-700 pt-4">
                        O acesso à plataforma é gratuito para todas as escolas registadas em Angola. Enquanto a sua
                        escola mantiver os dados atualizados através da declaração anual, a licença permanece ativa.
                    </p>
                </div>
            </div>
        </div>
    </LayoutPrincipal>
</template>

<script setup>
import { computed } from 'vue'
import LayoutPrincipal from '@/pages/LayoutPrincipal.vue'

const props = defineProps({
    escola: { type: Object, required: true },
})

const dataRegisto = computed(() => {
    if (!props.escola.created_at) return '—'
    return new Date(props.escola.created_at).toLocaleDateString('pt-PT', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    })
})
</script>
