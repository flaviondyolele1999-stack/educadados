<template>
    <LayoutPrincipal>
        <div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">
                        {{ props.escola?.nome }}
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Painel de Declaração Anual — Ano {{ currentYear }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-sm text-gray-700 dark:text-gray-200">
                        Estado da declaração:
                        <span :class="statusBadgeClass" class="ml-2 px-2 py-1 rounded-full font-semibold text-xs">
                            {{ declarationStatusLabel }}
                        </span>
                    </div>

                    <button disabled title="Funcionalidade em desenvolvimento"
                        class="bg-white dark:bg-gray-800 border px-3 py-2 rounded shadow text-sm opacity-50 cursor-not-allowed">
                        📄 Comprovativo (em breve)
                    </button>
                </div>
            </div>

            <!-- Últimos números declarados -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Alunos</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ indicators.students ?? '—' }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Professores</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ indicators.teachers ?? '—' }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Turmas</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ indicators.classes ?? '—' }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Cursos</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ indicators.courses ?? '—' }}</p>
                </div>
            </div>

            <!-- Checklist -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Checklist anual</h2>
                    <span class="text-sm text-gray-500 dark:text-gray-300">Progresso: {{ checklistProgress }}%</span>
                </div>

                <ol class="space-y-3">
                    <li v-for="(step, idx) in checklist" :key="step.key" class="flex items-start gap-3">
                        <div class="mt-1">
                            <div :class="[
                                'w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-semibold',
                                step.done ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'
                            ]">
                                {{ step.done ? '✓' : idx + 1 }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div class="font-medium text-gray-800 dark:text-white">{{ step.title }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ step.hint }}</div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ step.description }}</p>
                        </div>
                    </li>
                </ol>

                <!-- Ações -->
                <div class="mt-6 flex flex-wrap gap-3">
                    <button @click="openEditDeclaration"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:opacity-95">
                        ✏️ Preencher declaração
                    </button>

                    <button @click="submitDeclaration" :disabled="submitting"
                        class="px-4 py-2 bg-indigo-600 text-white rounded disabled:opacity-50">
                        {{ submitting ? 'Enviando...' : (declarationStatus === 'submitted' ? 'Atualizar declaração' : 'Submeter declaração') }}
                    </button>
                </div>
            </div>

            <!-- Históricos / Avisos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow">
                    <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">Comunicados Oficiais</h3>
                    <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                        <li v-for="note in notices" :key="note.id" class="p-3 rounded border dark:border-gray-700">
                            <div class="font-medium">{{ note.title }}</div>
                            <p class="text-sm mt-2">{{ note.body }}</p>
                        </li>
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow">
                    <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">Histórico de Declarações</h3>
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-xs text-gray-500">
                                <th class="py-2">Ano</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in history" :key="row.year" class="border-t dark:border-gray-700">
                                <td class="py-2">{{ row.year }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                        Submetido
                                    </span>
                                </td>
                                <td class="py-2">
                                    <button disabled title="Funcionalidade em desenvolvimento"
                                        class="text-sm text-gray-400 cursor-not-allowed">Baixar (em breve)</button>
                                </td>
                            </tr>
                            <tr v-if="history.length === 0">
                                <td colspan="3" class="py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Nenhuma declaração enviada ainda.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Indicadores Personalizados -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow mt-6">
                <h3 class="text-lg font-semibold mb-1 text-gray-800 dark:text-white">Indicadores Personalizados</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Destaque outros números da sua escola (ex: taxa de aprovação, bolsas atribuídas) que não fazem
                    parte da declaração anual.
                </p>

                <ul v-if="estatisticas.length" class="space-y-3 mb-6">
                    <li v-for="item in estatisticas" :key="item.id"
                        class="border dark:border-gray-700 rounded-lg p-4">
                        <template v-if="editingId === item.id">
                            <form @submit.prevent="submitEdit(item)" class="space-y-3">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div>
                                        <input v-model="editForm.titulo" type="text" placeholder="Título" required
                                            :disabled="editForm.processing"
                                            class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:text-white"
                                            :class="editForm.errors.titulo ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'" />
                                        <InputError :message="editForm.errors.titulo" />
                                    </div>
                                    <div>
                                        <input v-model="editForm.valor" type="text" placeholder="Valor" required
                                            :disabled="editForm.processing"
                                            class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:text-white"
                                            :class="editForm.errors.valor ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'" />
                                        <InputError :message="editForm.errors.valor" />
                                    </div>
                                    <div>
                                        <input v-model="editForm.descricao" type="text" placeholder="Descrição (opcional)"
                                            :disabled="editForm.processing"
                                            class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white" />
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <button type="submit" :disabled="editForm.processing"
                                        class="flex items-center gap-2 px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-50">
                                        <LoaderCircle v-if="editForm.processing" class="h-3 w-3 animate-spin" />
                                        Guardar
                                    </button>
                                    <button type="button" @click="cancelEdit"
                                        class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-sm rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </template>
                        <template v-else>
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="font-semibold text-gray-800 dark:text-white">
                                        {{ item.titulo }}: <span class="text-blue-600 dark:text-blue-400">{{ item.valor }}</span>
                                    </div>
                                    <p v-if="item.descricao" class="text-sm text-gray-500 dark:text-gray-400">{{ item.descricao }}</p>
                                </div>
                                <div class="flex gap-3 shrink-0">
                                    <button @click="startEdit(item)" class="text-sm text-blue-600 hover:underline">Editar</button>
                                    <button @click="removeEstatistica(item)" class="text-sm text-red-600 hover:underline">Remover</button>
                                </div>
                            </div>
                        </template>
                    </li>
                </ul>
                <p v-else class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Ainda não adicionou nenhum indicador personalizado.
                </p>

                <form @submit.prevent="createEstatistica" class="border-t dark:border-gray-700 pt-4 space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <input v-model="newStatForm.titulo" type="text" placeholder="Título" required
                                :disabled="newStatForm.processing"
                                class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:text-white"
                                :class="newStatForm.errors.titulo ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'" />
                            <InputError :message="newStatForm.errors.titulo" />
                        </div>
                        <div>
                            <input v-model="newStatForm.valor" type="text" placeholder="Valor" required
                                :disabled="newStatForm.processing"
                                class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:text-white"
                                :class="newStatForm.errors.valor ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'" />
                            <InputError :message="newStatForm.errors.valor" />
                        </div>
                        <div>
                            <input v-model="newStatForm.descricao" type="text" placeholder="Descrição (opcional)"
                                :disabled="newStatForm.processing"
                                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white" />
                        </div>
                    </div>
                    <button type="submit" :disabled="newStatForm.processing"
                        class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 disabled:opacity-50">
                        <LoaderCircle v-if="newStatForm.processing" class="h-4 w-4 animate-spin" />
                        {{ newStatForm.processing ? 'Adicionando...' : '+ Adicionar indicador' }}
                    </button>
                </form>
            </div>

            <!-- Modal -->
            <div v-if="showDeclarationModal"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg w-full max-w-lg p-6 relative">
                    <button @click="closeModal"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">✖</button>

                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">
                        Declaração Anual da Escola (Rascunho)
                    </h2>

                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium">Total de Alunos</label>
                            <input v-model.number="declarationForm.alunos" type="number"
                                class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-800 dark:text-white" min="0"
                                required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Total de Professores</label>
                            <input v-model.number="declarationForm.professores" type="number"
                                class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-800 dark:text-white" min="0"
                                required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Total de Turmas</label>
                            <input v-model.number="declarationForm.turmas" type="number"
                                class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-800 dark:text-white" min="0" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Total de Cursos</label>
                            <input v-model.number="declarationForm.cursos" type="number"
                                class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-800 dark:text-white" min="0" />
                        </div>

                        <div class="flex justify-between items-center gap-3 mt-6">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                <span v-if="draftExists">Rascunho carregado ✔</span>
                                <span v-else>Nenhum rascunho salvo</span>
                            </div>

                            <div class="flex gap-3">
                                <button type="button" @click="closeModal"
                                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                                    Fechar
                                </button>

                                <button type="button" @click="saveAndClose"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Salvar e fechar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </LayoutPrincipal>
</template>
<script setup>
import { ref, reactive, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import LayoutPrincipal from "@/pages/LayoutPrincipal.vue";
import InputError from "@/components/InputError.vue";
import { LoaderCircle } from "lucide-vue-next";
import axios from "axios";

const props = defineProps({
    currentYear: { type: Number, default: new Date().getFullYear() },
    escola: Object,
    estatisticas: { type: Array, default: () => [] }
});

// A escola já tem números declarados guardados na base de dados?
const jaDeclarou = Boolean(props.escola?.total_alunos) || Boolean(props.escola?.total_professores);

const declarationStatus = ref(jaDeclarou ? "submitted" : "pending");
const submitting = ref(false);

const checklist = reactive([
    { key: "fill-data", title: "Preencher dados anuais", hint: "Dados gerais", description: "Informar números.", done: jaDeclarou },
    { key: "review", title: "Rever e validar", hint: "Revisão interna", description: "Confirmar dados.", done: jaDeclarou },
    { key: "submit", title: "Submeter declaração", hint: "Envio final", description: "Enviar ao sistema.", done: jaDeclarou },
]);

const notices = ref([
    { id: 1, title: 'Como funciona', body: 'Mantenha os números da sua escola atualizados sempre que houver mudanças no total de alunos, professores, turmas ou cursos. Pode atualizar a declaração a qualquer momento.' },
]);

const history = ref(jaDeclarou ? [{ year: props.currentYear, status: 'submitted' }] : []);

// Indicadores com os últimos números declarados (vêm da própria escola)
const indicators = reactive({
    students: props.escola?.total_alunos ?? null,
    teachers: props.escola?.total_professores ?? null,
    classes: props.escola?.total_classes ?? null,
    courses: props.escola?.total_cursos ?? null,
});

// Rascunho local (guardado no navegador, ainda não enviado ao sistema)
const draftKey = `declaracao-draft-${props.escola?.id ?? 'sem-escola'}`;
const draftExists = ref(typeof window !== 'undefined' && !!localStorage.getItem(draftKey));

// Modal
const showDeclarationModal = ref(false);
const declarationForm = reactive({
    alunos: 0,
    professores: 0,
    turmas: 0,
    cursos: 0
});

// Computeds
const checklistProgress = computed(() => Math.round((checklist.filter(s => s.done).length / checklist.length) * 100));
const declarationStatusLabel = computed(() => declarationStatus.value === "submitted" ? "Submetido" : "Pendente");
const statusBadgeClass = computed(() => declarationStatus.value === "submitted" ? "bg-blue-600 text-white" : "bg-yellow-400 text-black");

// Abrir modal com o rascunho salvo, ou os últimos números já declarados
function openEditDeclaration() {
    const draft = typeof window !== 'undefined' ? localStorage.getItem(draftKey) : null;
    const base = draft ? JSON.parse(draft) : {
        alunos: indicators.students ?? 0,
        professores: indicators.teachers ?? 0,
        turmas: indicators.classes ?? 0,
        cursos: indicators.courses ?? 0,
    };
    declarationForm.alunos = base.alunos ?? 0;
    declarationForm.professores = base.professores ?? 0;
    declarationForm.turmas = base.turmas ?? 0;
    declarationForm.cursos = base.cursos ?? 0;
    showDeclarationModal.value = true;
}

// Fechar modal
function closeModal() {
    showDeclarationModal.value = false;
}

function saveAndClose() {
    if (typeof window !== 'undefined') {
        localStorage.setItem(draftKey, JSON.stringify({ ...declarationForm }));
    }
    draftExists.value = true;
    checklist.find(s => s.key === "fill-data").done = true;
    showDeclarationModal.value = false;
}

// Submeter declaração (salva aqui)
async function submitDeclaration() {
    // Validação simples
    if (declarationForm.alunos <= 0 || declarationForm.professores <= 0) {
        alert("Preencha pelo menos Alunos e Professores com valores maiores que 0.");
        return;
    }

    const payload = {
        alunos: declarationForm.alunos,
        professores: declarationForm.professores,
        turmas: declarationForm.turmas,
        cursos: declarationForm.cursos
    };

    try {
        submitting.value = true;
        const url = `/escolas/${props.escola.id}/declaracao`;
        const response = await axios.post(url, payload);

        if (response.data.success) {
            // Atualiza indicadores visuais
            indicators.students = payload.alunos;
            indicators.teachers = payload.professores;
            indicators.classes = payload.turmas;
            indicators.courses = payload.cursos;

            // Atualiza checklist e status
            checklist.find(s => s.key === "fill-data").done = true;
            checklist.find(s => s.key === "review").done = true;
            checklist.find(s => s.key === "submit").done = true;
            declarationStatus.value = "submitted";

            // Limpa o rascunho local, já que foi enviado
            if (typeof window !== 'undefined') {
                localStorage.removeItem(draftKey);
            }
            draftExists.value = false;

            // Atualiza histórico (uma entrada por ano)
            const existing = history.value.find(row => row.year === props.currentYear);
            if (existing) {
                existing.status = "submitted";
            } else {
                history.value.unshift({ year: props.currentYear, status: "submitted" });
            }

            showDeclarationModal.value = false;
            alert(response.data.message);
        }
    } catch (e) {
        console.error(e);
        alert("Erro ao submeter declaração.");
    } finally {
        submitting.value = false;
    }
}

// Indicadores personalizados (CRUD)
const newStatForm = useForm({ titulo: "", valor: "", descricao: "" });

function createEstatistica() {
    newStatForm.post(route("admin.estatisticas.store"), {
        preserveScroll: true,
        onSuccess: () => newStatForm.reset(),
    });
}

const editingId = ref(null);
const editForm = useForm({ titulo: "", valor: "", descricao: "" });

function startEdit(item) {
    editingId.value = item.id;
    editForm.titulo = item.titulo;
    editForm.valor = item.valor;
    editForm.descricao = item.descricao;
    editForm.clearErrors();
}

function cancelEdit() {
    editingId.value = null;
}

function submitEdit(item) {
    editForm.put(route("admin.estatisticas.update", item.id), {
        preserveScroll: true,
        onSuccess: () => { editingId.value = null; },
    });
}

function removeEstatistica(item) {
    if (!confirm(`Remover o indicador "${item.titulo}"?`)) return;
    router.delete(route("admin.estatisticas.destroy", item.id), { preserveScroll: true });
}
</script>
