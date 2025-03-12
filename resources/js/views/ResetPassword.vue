<template>

    <section class="bg-gray-50">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            
            <div class="w-full p-6 bg-white rounded-lg shadow  md:mt-0 sm:max-w-md sm:p-8">
                <h2 class="mb-1 text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Changer le mot de passe
                </h2>
                <form @submit.prevent="changePassword" class="mt-4 space-y-4 lg:mt-5 md:space-y-5">
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Nouveau mot de passe</label>
                        <input v-model="password" type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5  " required="">
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Confirmer le mot de passe</label>
                        <input v-model="password_confirmation" type="password" name="password" id="password_confirmation" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5  " required="">
                    </div>
                    <p v-if="message" class="mt-4 text-green-500">{{ message }}</p>
                    <p v-if="error" class="mt-4 text-red-500">{{ error }}</p>
                    <button :disabled="loading" type="submit" class=" w-full px-6 py-2 min-w-[120px] text-center text-white bg-violet-600 border border-violet-600 rounded active:text-violet-500 hover:bg-transparent hover:text-violet-600 focus:outline-none focus:ring">
                        {{ loading ? "Réinitialisation..." : "Réinitialiser" }}
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/authStore';
import { useRoute, useRouter } from 'vue-router';

const authStore = useAuthStore();
const password = ref('');
const password_confirmation = ref('');
const email = ref('');
const token = ref('');
const route = useRoute();
const router = useRouter();
const message = ref('');
const error = ref('');
const loading = ref(false);

const changePassword = async () => {
    message.value = '';
    error.value = '';
    loading.value = true;
    try {
        await authStore.changePassword({email: email.value, token: token.value, password: password.value, password_confirmation: password_confirmation.value });

        message.value = " Mot de passe réinitialisé avec succès ! Redirection...";
        setTimeout(() => router.push('/login'), 3000);
    } catch (err) {
        console.log(err)
        error.value = err.response?.data?.message || "Une erreur s'est produite.";
    } finally {
        loading.value = false;
    }
};


onMounted(() => {
    email.value = route.query.email || '';
    token.value = route.params.token || '';

    console.log(email.value)
    console.log(token.value)
});
</script>
