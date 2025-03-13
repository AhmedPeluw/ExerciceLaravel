<template>

  <nav class="bg-white border-gray-200 dark:bg-gray-900">
    <div class="flex flex-wrap items-center justify-between max-w-screen-xl mx-auto p-4">

      <div class="flex items-center md:order-2 space-x-1 md:space-x-2 rtl:space-x-reverse">
        <router-link v-if="!isAuthenticated" to="/login"
          class="text-gray-800 dark:text-white hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 md:px-5 md:py-2.5 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">
          Login
        </router-link>
        <router-link v-if="!isAuthenticated" to="/register"
          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 md:px-5 md:py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
          Register
        </router-link>
      </div>

      <div id="mega-menu" class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1">
        <ul class="flex flex-col mt-4 font-medium md:flex-row md:mt-0 md:space-x-8 rtl:space-x-reverse">
          <li v-if="isAuthenticated">
            <router-link to="/"
              class="block py-2 px-3  border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700">Dashboard</router-link>
          </li>
          <li v-if="isAuthenticated">
            <router-link to="/payment-links"
              class="block py-2 px-3 text-gray-900 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700">
              Liste des paiments
            </router-link>
          </li>
          <li v-if="isAuthenticated && user.role.name == 'admin'">
            <router-link to="/users"
              class="block py-2 px-3 text-gray-900 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700">
              Liste des utilisateurs
            </router-link>
          </li>
          <li v-if="isAuthenticated">
            <router-link to="/payment-links-create"
              class="block py-2 px-3 text-gray-900 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700">
              Création du lien de paiment
            </router-link>
          </li>
          <li v-if="isAuthenticated">
            <button
              class="block cursor-pointer py-2 px-3 text-gray-900 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
              @click="logout">Logout</button>
          </li>
          <li v-show="isAuthenticated">

            <button id="dropdownNotificationButton" data-dropdown-toggle="dropdownNotification"
              class="relative cursor-pointer inline-flex items-center text-sm font-medium text-center text-gray-500 focus:outline-none "
              type="button">
              <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 14 20">
                <path
                  d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z" />
              </svg>

              <div v-if="notifications.length > 0"
                class="absolute block w-3 h-3 bg-red-500 border-2 border-white rounded-full -top-0.5 start-2.5 "></div>
            </button>

            <!-- Dropdown menu -->
            <div id="dropdownNotification"
              class="z-20 hidden w-full max-w-sm bg-white divide-y divide-gray-100 rounded-lg shadow-sm "
              aria-labelledby="dropdownNotificationButton">
              <div class="block px-4 py-2 font-medium text-center text-gray-700 rounded-t-lg bg-gray-50 ">
                Notifications
              </div>
              <div v-show="notifications && notifications.length > 0" v-for="notification in notifications"
                :key=" notification.id" class="divide-y divide-gray-100 ">
                <a href="#" class="flex px-4 py-3 hover:bg-gray-100">
                  <div class="shrink-0">
                    <img class="rounded-full w-11 h-11"
                      src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                      alt="Jese image">
                    <div
                      class="absolute flex items-center justify-center w-5 h-5 ms-6 -mt-5 bg-blue-600 border border-white rounded-full ">
                      <svg class="w-2 h-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 18 18">
                        <path
                          d="M1 18h16a1 1 0 0 0 1-1v-6h-4.439a.99.99 0 0 0-.908.6 3.978 3.978 0 0 1-7.306 0 .99.99 0 0 0-.908-.6H0v6a1 1 0 0 0 1 1Z" />
                        <path
                          d="M4.439 9a2.99 2.99 0 0 1 2.742 1.8 1.977 1.977 0 0 0 3.638 0A2.99 2.99 0 0 1 13.561 9H17.8L15.977.783A1 1 0 0 0 15 0H3a1 1 0 0 0-.977.783L.2 9h4.239Z" />
                      </svg>
                    </div>
                  </div>
                  <div class="w-full ps-3">
                    <div class="text-gray-500 text-sm mb-1.5 ">{{ notification.customer_name }} a payé {{
                      notification.amount }} EUR</div>
                  </div>
                </a>
              </div>
              <div v-if="notifications && notifications.length == 0">
                <p class="p-4">Il n'y a pas des notifications</p>
              </div>
            </div>

          </li>
        </ul>
      </div>
    </div>
  </nav>

</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useAuthStore } from '@/stores/authStore';
import { useRouter } from 'vue-router';
import echo from '@/services/echo';

const authStore = useAuthStore();
const router = useRouter();
const user = JSON.parse(localStorage.getItem('user'))
const notifications = ref([]);

const isAuthenticated = computed(() => !!authStore.token);

const logout = () => {
  authStore.logout();
  router.push('/login');
};

onMounted(() => {
  console.log("Listening ...");

  // Listen to all events
  echo.channel('payments')
      .subscribed(() => {
          console.log("Successfully subscribed");
      })
      .listen('.NewPaymentNotification', (event) => {
          console.log("Received event:", event);
          notifications.value.push(event);
      })
      .error((error) => {
          console.error("Pusher subscription error:", error);
      });
});
</script>

<style scoped>
.navbar {
  background: #333;
  color: white;
  padding: 10px;
}
.navbar ul {
  list-style: none;
  display: flex;
  gap: 15px;
}
.navbar a, .navbar button {
  color: white;
  text-decoration: none;
  background: none;
  border: none;
  cursor: pointer;
}
.navbar a:hover, .navbar button:hover {
  text-decoration: underline;
}
</style>
