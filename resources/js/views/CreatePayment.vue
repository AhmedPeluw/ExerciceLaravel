<template>
  
    <form @submit.prevent="createPayment">
        <div class="space-y-12 m-4">

        <div class="border-b border-gray-900/10 pb-12">
            <h2 class="text-base/7 font-semibold text-gray-900">Création du lien de paiement</h2>

            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div v-if="!successCheck && switchSend" class="mt-5 sm:col-span-full flex items-center justify-between p-5 leading-normal text-red-600 bg-red-100 rounded-lg" role="alert">
                    <p>{{ errorMessage }}</p>
                </div>

                <div v-if="successCheck && switchSend" class="mt-5 sm:col-span-full flex items-center justify-between p-5 leading-normal text-green-600 bg-green-100 rounded-lg" role="alert">
                    <p>Le lien de paiement créé a été crée avec succès</p>

                    
                </div>
                <div class="sm:col-span-full">
                    <label for="first-name" class="block text-sm/6 font-medium text-gray-900">Nom</label>
                    <div class="mt-2">
                        <input v-model="payment.customer_name" type="text" name="first-name" id="first-name" autocomplete="given-name" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                </div>

                <div class="sm:col-span-full">
                    <label for="email" class="block text-sm/6 font-medium text-gray-900">Email</label>
                    <div class="mt-2">
                        <input v-model="payment.customer_email" id="email" name="email" type="email" autocomplete="email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                </div>

                <div class="col-span-full">
                    <label for="montant" class="block text-sm/6 font-medium text-gray-900">Montant (Euro)</label>
                    <div class="mt-2">
                        <input v-model="payment.amount" type="number" name="montant" id="montant" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                </div>

                <div class="col-span-full">
                    <label for="description" class="block text-sm/6 font-medium text-gray-900">Description</label>
                    <div class="mt-2">
                        <textarea v-model="payment.description" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" name="" id="" cols="10" rows="5"></textarea>
                    </div>
                </div>
                <div class="col-span-full">
                    <label for="date_expiration" class="block text-sm/6 font-medium text-gray-900">Date d'expiration</label>
                    <div class="mt-2">
                        <input v-model="payment.expires_at" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" type="datetime-local" required />
                    </div>
                </div>

                <div class="col-span-full">
                    <button type="submit" class="flex cursor-pointer items-center text-indigo-700 border border-indigo-600 py-2 px-6 gap-2 rounded inline-flex items-center">Créer un lien de paiement</button>    
                </div>

            </div>
        </div>
        
    
        </div>

        
    </form>
    

</template>

<script setup>
import { ref } from 'vue';
import apiClient from '@/services/apiClient';

const payment = ref({
  customer_name: '',
  customer_email: '',
  amount: '',
  currency: 'EUR',
  description: '',
  expires_at: '',
});

const errorMessage = ref("")
const successCheck = ref(false)
const switchSend = ref(false)

const createPayment = async () => {
    
    try {
        const response = await apiClient.post('/payment-links', payment.value);
        successCheck.value = true
    } catch (error) {
        errorMessage.value = ""
        
        console.error("Erreur lors de la création du paiement:", error);
        
        // Show error message
        if (error.response && error.response.data.message) {
            errorMessage.value = error.response.data.message
        } else {
            errorMessage.value = "Une erreur s'est produite lors de la création du paiement."
        }
        successCheck.value = false
    }

    switchSend.value = true
};
</script>
