<template>
  

    <div class="m-8">
        <div class="px-4 sm:px-0">
            <h3 class="text-base/7 font-semibold text-gray-900">Les détails du paiement</h3>
        </div>
        <div class="mt-6 border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">Nom</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{payment.customer_name}}</dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">Email</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{payment.customer_email}}</dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">Montant</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{payment.amount}} {{ payment.currency }}</dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">Description</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{payment.description}}</dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">Statut</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{payment.status}}</dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">Date d'expiration</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{payment.expires_at}}</dd>
            </div>

            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">Le lien de paiement</dt>
                <a :href="payment.payment_link" target="_blank" class=" text-center text-indigo-700 border border-indigo-600 py-2 px-6 gap-2 rounded">Visiter le lien</a>                    
            </div>
            
        </dl>
        </div>
    </div>

    

</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '@/services/apiClient';
import { useRoute } from 'vue-router';

const payment = ref({});
const route = useRoute();

const fetchDetails = async () => {
    const response = await apiClient.get(`/payment-links/${route.params.id}`);
    payment.value = response.data;
};

onMounted(() => {
    console.log(route.params.id)
    fetchDetails()
});
</script>
