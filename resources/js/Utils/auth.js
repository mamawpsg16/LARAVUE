import { getItem } from "./localStorage.js";
export default {
    getAuth: async function () {
        return {
            user_id: getItem("user_id"),
            access_token: getItem("access_token"),
        };
    },

    isUserAuthenticated: async function() {
        try {
          const user_id = getItem("user_id");
          const access_token = getItem("access_token");
      
          if (user_id && access_token) {
            // Check token expiration if necessary
            // Example: const isTokenExpired = checkTokenExpiration(access_token);
            //         if (isTokenExpired) return false;
      
            return true;
          }
      
          return false;
        } catch (error) {
          console.error(error);
          return false;
        }
      }
      

    // logout: async function(){
    //     let user_id = getCookie("user_id");
    //     let logout = await axios.post('/api/logout',{ id: user_id});
    //     return false;
    //     deleteCookie('user_id');
    //     deleteCookie('access_token');
    //     return logout;
    // }
};
