import { toast } from 'vue3-toastify';

export const showCustomToast = (type, message, options = {}) => {
    const defaultOptions = {
      autoClose: 2000,
      theme: 'colored',
      limit: 2,
      pauseOnHover: false,
      pauseOnFocusLoss: false,
      pauseOnBodyClick: false,
      position: toast.POSITION.BOTTOM_RIGHT
    };
  
    const mergedOptions = { ...defaultOptions, ...options };
    toast[type](message, mergedOptions);
};
  

export default showCustomToast;