import Swal from 'sweetalert2';

export const showSwal = (title, text, icon, confirmButtonText, config = {}) => {
  const swalConfig = {
    title: title,
    text: text,
    icon: icon,
    confirmButtonText: confirmButtonText,
    ...config // Spread the additional properties from the config object
  };
  return Swal.fire(swalConfig); // Return the Swal promise
};

export default showSwal;
