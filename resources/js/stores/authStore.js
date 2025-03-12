import { defineStore } from 'pinia';
import apiClient from '@/services/apiClient';
import { useRouter } from 'vue-router';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    router: useRouter(),
  }),

  getters: {
    isAdmin: (state) => state.user?.role === 'admin',
  },

  actions: {
    
    async register(userData) {
      const response = await apiClient.post('/register', userData);
      this.token = response.data.token;
      localStorage.setItem('token', response.data.token);
      this.fetchUser(response.data.user);
      this.router.push('/');
    },
    
    async login(credentials) {
      const response = await apiClient.post('/login', credentials);
      this.token = response.data.token;
      localStorage.setItem('token', response.data.token);
      this.fetchUser(response.data.user);
      this.router.push('/');
    },
    
    async forgetPassword(email){
        const response = await apiClient.post('/forgot-password', email);
    },

    async changePassword(data){
        const response = await apiClient.post('/reset-password', data);
    },

    async fetchUser(user) {
      const response = await apiClient.get('/user');
      this.user = response.data;
      localStorage.setItem('user', JSON.stringify(response.data));
    },

    logout() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
    }
  }
});
