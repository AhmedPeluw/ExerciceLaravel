<template>
  <div>
    <h2 class=" m-4 flex items-center justify-center p-4 text-2xl tracking-tighttext-3xl font-bold tracking-tight">Historique des Paiements</h2>
    
    <!-- Filtres -->
    <div class="mb-4 flex items-center justify-center">
      <select v-model="filters.status" class=" ml-4 mr-4 h-10 border-2 border-sky-500 focus:outline-none focus:border-sky-500 text-sky-500 rounded px-2 md:px-3 py-0 md:py-1 tracking-wider">
        <option value="">Tous</option>
        <option value="paid">Payé</option>
        <option value="pending">En attente</option>
        <option value="expired">Expiré</option>
      </select>

      <select v-model="filters.sort_order" class=" ml-4 mr-4 h-10 border-2 border-sky-500 focus:outline-none focus:border-sky-500 text-sky-500 rounded px-2 md:px-3 py-0 md:py-1 tracking-wider">
        <option value="desc">les plus récents</option>
        <option value="asc">les plus anciens</option>
      </select>

      <input class="bg-gray-50 mr-4 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600  p-2.5" v-model="filters.email" type="email" placeholder="Recherche par email" />
      <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600  p-2.5" v-model="filters.amount" type="number" placeholder="Montant" />

        <button type="button" class="flex ml-4 cursor-pointer items-center text-indigo-700 border border-indigo-600 py-2 px-6 gap-2 rounded inline-flex items-center" @click="fetchPayments()">Filtrer</button>                    
    </div>

  </div>

  <div class="flex items-center justify-center">
   <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded-lg border border-gray-200">
            <thead>
                <tr class="border-b">
                    <th class="px-6 py-4 text-left text-gray-600 font-medium">Nom</th>
                    <th class="px-6 py-4 text-left text-gray-600 font-medium">Email</th>
                    <th class="px-6 py-4 text-left text-gray-600 font-medium">Montant</th>
                    <th class="px-6 py-4 text-left text-gray-600 font-medium">Statut</th>
                    <th class="px-6 py-4 text-left text-gray-600 font-medium">Date d'expiration</th>
                    <th class="px-6 py-4 text-left text-gray-600 font-medium">Lien de paiement</th>
                    <th class="px-6 py-4 text-left text-gray-600 font-medium">Télécharger</th>
                    <th class="px-6 py-4 text-left text-gray-600 font-medium">Voir plus</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="payment in payments" :key="payment.id" class="border-b">
                    <td class="px-6 py-4">{{ payment.customer_name }}</td>
                    <td class="px-6 py-4">{{ payment.customer_email }}</td>
                    <td class="px-6 py-4">{{ payment.amount }} EUR</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ payment.status }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ payment.expires_at }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">
                        <a :href="payment.payment_link" target="_blank" class="flex items-center text-indigo-700 border border-indigo-600 py-2 px-6 gap-2 rounded inline-flex items-center">
                            <span>
                                Visiter le lien
                            </span>
                            <svg class="w-4 w-6 h-6 ml-2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                viewBox="0 0 24 24" >
                                <path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900">
                        <button class="flex cursor-pointer items-center text-indigo-700 border border-indigo-600 py-2 px-6 gap-2 rounded inline-flex items-center" v-if="payment.status === 'paid'" @click="downloadReceipt(payment.id)">Télécharger PDF</button>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900">
                        <button class="flex cursor-pointer items-center text-indigo-700 border border-indigo-600 py-2 px-6 gap-2 rounded inline-flex items-center" @click="viewDetails(payment.id)">Voir plus</button>                    
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="flex items-center justify-center" v-if="role === 'admin' && payments.length > 0">
  <button @click="exportCSV" type="button" class=" cursor-pointer mt-8 mr-4 ml-4 inline-flex items-center justify-center rounded-xl bg-green-600 py-3 px-6 font-dm text-base font-medium text-white  transition-transform duration-200 ease-in-out hover:scale-[1.02]">
    Exporter en CSV
</button>
<button @click="exportExcel" type="button" class=" cursor-pointer mt-8 inline-flex items-center justify-center rounded-xl bg-green-600 py-3 px-6 font-dm text-base font-medium text-white  transition-transform duration-200 ease-in-out hover:scale-[1.02]">
    Exporter en Excel
</button>
</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '@/services/apiClient';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';

const router = useRouter();
const payments = ref([]);
const filters = ref({
  status: '',
  email: '',
  amount: '',
  sort_by: 'created_at',
  sort_order: 'desc'
});

const role = JSON.parse(localStorage.getItem('user')).role.name

const user = JSON.parse(localStorage.getItem('user'))

console.log(JSON.parse(localStorage.getItem('user')))

const fetchPayments = async () => {
  const response = await apiClient.get('/payment-links', { params: filters.value });
  payments.value = response.data;
};

const downloadReceipt = async (paymentId) => {
  window.open(`http://127.0.0.1:8000/api/payments/${paymentId}/receipt`, '_blank');
};

const viewDetails = async (paymentId) => {

  router.push(`/payment-links/${paymentId}`);
};

const exportCSV = async () => {

  try {
        const response = await apiClient.get(`/export-payments/csv`, {
            responseType: 'blob',
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `payment_${user.name}.csv`);
        document.body.appendChild(link);
        link.click();
        link.remove();
    } catch (error) {
        console.error(" Error downloading file:", error);
    }
};

const exportExcel = async () => {

  try {
        const response = await apiClient.get(`/export-payments/excel`, {
            responseType: 'blob',
        });

        console.log(response)

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `payment_${user.id}.xlsx`);
        document.body.appendChild(link);
        link.click();
        link.remove();
    } catch (error) {
        console.error(" Error downloading file:", error);
    }
};


onMounted(fetchPayments);
</script>
