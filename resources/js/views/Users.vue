<template>
  <div>
    <h2 class=" m-4 flex items-center justify-center p-4 text-2xl tracking-tighttext-3xl font-bold tracking-tight">Liste des utilisateurs</h2>

  </div>

  <div class="flex items-center justify-center">
   <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded-lg border border-gray-200">
            <thead>
                <tr class="border-b">
                    <th class="px-6 py-4 text-left text-gray-600 font-medium">Nom</th>
                    <th class="px-6 py-4 text-left text-gray-600 font-medium">Email</th>
                    <th class="px-6 py-4 text-left text-gray-600 font-medium">Role</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id" class="border-b">
                    <td class="px-6 py-4">{{ user.name }}</td>
                    <td class="px-6 py-4">{{ user.email }}</td>
                    <td class="px-6 py-4">{{ user.role.name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '@/services/apiClient';

const users = ref([]);


const fetchUsers = async () => {
  const response = await apiClient.get('/all-users');
  users.value = response.data.users;
};

onMounted(fetchUsers);
</script>
