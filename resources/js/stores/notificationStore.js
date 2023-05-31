import { defineStore } from "pinia";
import { ref, computed } from 'vue'
export const useNotificationStore = defineStore("notification", () => {
    const unreadCount = ref(0);
  
    const doubleCount = computed(() => unreadCount.value * 2);

    function increment() {
        unreadCount.value++;
    }

    return { unreadCount, doubleCount, increment };
});
