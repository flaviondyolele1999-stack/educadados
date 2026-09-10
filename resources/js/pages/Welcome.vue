<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineProps<{
  estatisticas: {
    titulo: string
    valor: string
    descricao: string
    ano: string
  }[]
}>();

const passos = [
  {
    numero: '1',
    titulo: 'A escola regista-se',
    descricao: 'Em poucos minutos, a escola cria uma conta com os dados básicos: nome, província, município e contacto.',
  },
  {
    numero: '2',
    titulo: 'Declara os números do ano letivo',
    descricao: 'Total de alunos, professores, turmas e cursos. Pode atualizar sempre que os números mudarem.',
  },
  {
    numero: '3',
    titulo: 'Os dados tornam-se estatísticas públicas',
    descricao: 'A informação é agregada automaticamente e fica disponível para qualquer pessoa consultar em "Explorar Estatísticas".',
  },
];
</script>

<template>
  <Head title="EducaDados - Estatísticas Públicas" />

  <main class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-100 dark:from-gray-900 dark:to-gray-800">
    <!-- Hero -->
    <section class="max-w-4xl mx-auto pt-20 pb-10 px-6 text-center animate-fade-in">
      <h1 class="text-5xl md:text-6xl font-extrabold mb-6 text-gray-900 dark:text-white leading-tight">
        📊 Bem-vindo ao <span class="text-blue-600">EducaDados</span>
      </h1>
      <p class="text-lg md:text-xl text-gray-700 dark:text-gray-300 mb-10">
        A plataforma pública onde escolas de Angola declaram os seus números — alunos, professores, turmas e cursos —
        e onde qualquer pessoa pode consultar essas estatísticas educacionais de forma simples e gratuita.
      </p>

      <div class="flex justify-center flex-wrap gap-4">
        <a
          href="/login"
          class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition"
        >
          Entrar como Escola
        </a>
        <a
          href="/explorar"
          class="px-6 py-3 bg-white text-blue-600 font-medium rounded-lg border border-blue-600 shadow hover:bg-blue-50 dark:bg-transparent dark:text-white dark:border-white dark:hover:bg-white/10 transition"
        >
          Explorar Estatísticas
        </a>
      </div>

      <p class="mt-6 text-sm text-gray-600 dark:text-gray-400">
        Ainda não tem conta? <a href="/escola/registrar" class="text-blue-600 hover:underline font-medium">Registre a sua escola</a>
      </p>
    </section>

    <!-- Estatísticas em tempo real -->
    <section class="max-w-4xl mx-auto px-6 pb-16">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div
          v-for="item in estatisticas"
          :key="item.titulo"
          class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 text-center"
        >
          <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ item.valor }}</p>
          <p class="mt-1 font-medium text-gray-800 dark:text-white">{{ item.titulo }}</p>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ item.descricao }}</p>
        </div>
      </div>

      <p class="text-center mt-6">
        <a href="/explorar" class="text-blue-600 hover:underline text-sm font-medium">
          Ver gráficos e mais detalhes →
        </a>
      </p>
    </section>

    <!-- Como funciona -->
    <section class="bg-white dark:bg-gray-900 py-16 px-6">
      <div class="max-w-5xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-2">Como funciona</h2>
        <p class="text-center text-gray-600 dark:text-gray-400 mb-12">Três passos simples, do registo à estatística pública.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div v-for="passo in passos" :key="passo.numero" class="text-center">
            <div class="mx-auto mb-4 w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center text-xl font-bold">
              {{ passo.numero }}
            </div>
            <h3 class="font-semibold text-lg text-gray-800 dark:text-white mb-2">{{ passo.titulo }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ passo.descricao }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Para quem é -->
    <section class="py-16 px-6">
      <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-8">
          <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3">🏫 É uma escola?</h3>
          <ul class="space-y-2 text-gray-600 dark:text-gray-300 text-sm">
            <li>✓ Registe a sua escola gratuitamente</li>
            <li>✓ Declare e atualize os seus números quando quiser</li>
            <li>✓ Acompanhe o histórico das suas declarações</li>
            <li>✓ Destaque indicadores próprios no seu painel</li>
          </ul>
          <a href="/escola/registrar" class="inline-block mt-5 text-blue-600 hover:underline font-medium text-sm">
            Registrar a minha escola →
          </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-8">
          <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3">🔎 Quer consultar dados?</h3>
          <ul class="space-y-2 text-gray-600 dark:text-gray-300 text-sm">
            <li>✓ Não precisa de conta para consultar</li>
            <li>✓ Veja totais nacionais de alunos e professores</li>
            <li>✓ Explore os números em gráficos</li>
            <li>✓ Dados atualizados diretamente pelas escolas</li>
          </ul>
          <a href="/explorar" class="inline-block mt-5 text-blue-600 hover:underline font-medium text-sm">
            Explorar estatísticas →
          </a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-200 dark:border-gray-700 py-8 px-6 text-center text-sm text-gray-500 dark:text-gray-400">
      EducaDados — plataforma pública de estatísticas educacionais de Angola.
    </footer>
  </main>
</template>

<style scoped>
@keyframes fade-in {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.8s ease-out forwards;
}
</style>
