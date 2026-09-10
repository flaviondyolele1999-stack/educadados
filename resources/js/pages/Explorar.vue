<script setup lang="ts">
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import { Bar } from 'vue-chartjs'

// Registra módulos do Chart.js
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

// Props do servidor
const props = defineProps<{
  estatisticas: {
    titulo: string
    valor: string
    descricao: string
    ano: string
  }[]
}>()

// Extrai os títulos (categorias) e valores reais recebidos do backend
const categorias = props.estatisticas.map(item => item.titulo)
const dados = props.estatisticas.map(item => parseFloat(item.valor.replace(/\./g, '').replace(',', '.')) || 0)

const cores = ['#3b82f6', '#10b981', '#f59e0b']

const chartData = ref({
  labels: categorias,
  datasets: [
    {
      label: 'Valores',
      backgroundColor: cores,
      data: dados
    }
  ]
})

const chartOptions = ref({
  responsive: true,
  plugins: {
    legend: { display: false },
    title: {
      display: true,
      text: 'Indicadores Educacionais',
      color: '#246fd8ff',
      font: {
        size: 18
      }
    },
    tooltip: {
      enabled: true
    }
  },
  scales: {
    y: {
      beginAtZero: true
    }
  }
})
</script>

<template>
  <Head title="Explorar Estatísticas - EducaDados" />

  <main class="min-h-screen bg-gray-50 dark:bg-gray-900 py-16 px-6 md:px-12">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-4xl font-bold text-center text-gray-800 dark:text-white mb-6">
        📈 Estatísticas Educacionais
      </h1>
      <p class="text-center text-gray-600 dark:text-gray-300 mb-12">
        Dados compilados de diversas escolas para apoiar decisões e políticas públicas.
      </p>

      <!-- Botão Voltar -->
      <div class="mb-8">
        <a href="/"
           class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-white transition">
          ← Voltar para o Início
        </a>
      </div>

      <!-- Cartões com estatísticas -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="(item, index) in props.estatisticas" :key="index"
             class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition hover:scale-105 hover:shadow-lg">
          <h2 class="text-xl font-semibold text-blue-600 dark:text-blue-400 mb-2">
            {{ item.titulo }} ({{ item.ano }})
          </h2>
          <p class="text-3xl font-bold text-gray-800 dark:text-white mb-1">
            {{ item.valor }}
          </p>
          <p class="text-sm text-gray-600 dark:text-gray-300">
            {{ item.descricao }}
          </p>
        </div>
      </div>

      <!-- Gráfico de barras -->
      <div class="mt-16 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
       <!-- Gráfico de barras -->
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <Bar :data="chartData" :options="chartOptions" />
      </div>
      </div>
    </div>
  </main>
</template>
