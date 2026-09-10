<template>
  <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Sidebar -->
    <aside :class="[
      'fixed md:relative z-20 h-full bg-gray-700 dark:bg-gray-800 shadow-lg flex flex-col transition-transform w-64',
      openSidebar ? 'translate-x-0' : '-translate-x-full'
    ]">
      <div class="p-4 font-bold text-gray-100 text-xl border-b dark:border-gray-700">
        EducaDados
      </div>
      <nav class="flex-1 p-4 space-y-2">
        <Link :href="route('admin.estatisticas.index')"
              class="block p-2 text-sm text-gray-100 rounded hover:bg-gray-600 dark:hover:bg-gray-700">
          Declarar Dados
        </Link>
        <Link :href="route('escola.licenciar')"
              class="block p-2 text-sm text-gray-100 rounded hover:bg-gray-600 dark:hover:bg-gray-700">
          Licenciar Escola
        </Link>
        <!-- outros links... -->
      </nav>
    </aside>

    <!-- Conteúdo principal -->
    <div class="flex-1 flex flex-col">
      <header class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 shadow text-gray-800">
        <button @click="openSidebar = !openSidebar" class="md:hidden p-2 rounded bg-gray-200 dark:bg-gray-700">☰</button>
        <h1 class="text-lg font-bold">EducaDados - Painel da Escola</h1>

        <div class="flex items-center space-x-4">
          <button class="relative">🔔</button>

          <div class="relative" ref="dropdownRef">
            <div class="flex items-center space-x-2 cursor-pointer" @click="dropdownOpen = !dropdownOpen">
              <span class="font-medium dark:text-gray-300">{{ props?.escola?.nome }}</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>

            <div v-if="dropdownOpen"
                 class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 border rounded-md shadow-lg z-50 py-1">
              <!-- Botão de logout com POST -->
              <button @click="logout"
                      class="w-full text-left px-4 py-2 text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">
                Sair
              </button>
            </div>
          </div>
        </div>
      </header>

      <!-- Área principal -->
      <main class="flex-1 overflow-y-auto">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'


const dropdownOpen = ref(false)
const openSidebar = ref(true)
const dropdownRef = ref(null)

const { props } = usePage()

// Fecha o dropdown ao clicar fora
function handleClickOutside(event) {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    dropdownOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('pointerdown', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('pointerdown', handleClickOutside)
})

// Logout via Inertia POST
function logout() {
  router.post(route('logout'))
}
</script>
