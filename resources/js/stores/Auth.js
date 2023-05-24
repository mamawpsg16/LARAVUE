import { defineStore } from 'pinia';

export const useAuthStore = defineStore('AuthStore', {
  // State / REF / DATA
  state: () => ({
    auth_user:{},
  }),

 
  // Getters / COMPUTED
  getters: {
    
    // doubleCount: (state) => state.count * 2,
  },

  // Actions / METHOD
  actions: {
    setAuthUser(user) {
      this.auth_user = user;
    },
    $reset() {
      this.auth_user = {}; // Reset the auth_user state to an empty object
    },
  
  },
});
