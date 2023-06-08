import { defineStore } from 'pinia';

export const useTaskStore = defineStore('TaskStore', {
  // State / REF / DATA
  state: () => ({
    created:null,
    updated:null,
    success:null,
    success:null,
  }),

 
  // Getters / COMPUTED
  getters: {
    
    // doubleCount: (state) => state.count * 2,
  },

  // Actions / METHOD
  actions: {
   
  },
});
