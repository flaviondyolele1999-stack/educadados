<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>
<template>
    
  <AuthBase title="Log in to your account" description="Enter your email and password below to log in">
    <Head title="Log in" />

    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
      {{ status }}
    </div>

    <form @submit.prevent="submit" class="flex flex-col gap-6">
      <div class="grid gap-6">
        <div class="grid gap-2">
          <label for="email" class="text-sm font-medium text-gray-700">Email</label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            required
            autofocus
            class="p-3 border border-gray-300 rounded-lg bg-white text-gray-900 w-full focus:outline-none focus:ring-2 focus:ring-blue-400"
            placeholder="email@example.com"
          />
          <InputError :message="form.errors.email" />
        </div>

        <div class="grid gap-2">
          <div class="flex items-center justify-between">
            <label for="password" class="text-sm font-medium text-gray-700">Palavra Passe</label>
            <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm">
              recuperar palavra-passe?
            </TextLink>
          </div>
          <input
            id="password"
            type="password"
            v-model="form.password"
            required
            class="p-3 border border-gray-300 rounded-lg bg-white text-gray-900 w-full focus:outline-none focus:ring-2 focus:ring-blue-400"
            placeholder="Password"
          />
          <InputError :message="form.errors.password" />
        </div>

        <div class="flex items-center gap-2">
          <input type="checkbox" id="remember" v-model="form.remember" />
          <label for="remember" class="text-sm text-gray-700">Relambra-se</label>
        </div>

        <button
          type="submit"
          class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded"
          :disabled="form.processing"
        >
          <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin inline-block mr-2" />
          Log in
        </button>
      </div>

      <div class="text-center text-sm text-gray-600">
        Ainda não tem conta?
        <a :href="route('escola.register')" class="text-blue-600 hover:underline">Registre a sua escola</a>
      </div>

      <!-- Botão de voltar para página principal -->
      <div class="text-center mt-4">
        <a href="/" class="text-sm text-blue-600 hover:underline">← Voltar para página principal</a>
      </div>
    </form>
  </AuthBase>
</template>
