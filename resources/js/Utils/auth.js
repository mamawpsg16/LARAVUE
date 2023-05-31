import { getItem, removeItem } from './localStorage.js'
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
      },
      
      isAccessTokenInvalid: function () {
        const access_token = getItem('access_token');
        const user_id = getItem('user_id');
        if (!access_token) {
            removeItem("user_id");
            return true;
        }
        if (!user_id) {
            removeItem("access_token");
            return true;
        }

        // Decode the JWT to extract the expiration time
        const tokenParts = access_token.split(".");
        if (tokenParts.length !== 3) {
            // Invalid token format, handle accordingly
            removeItem("user_id");
            removeItem("access_token");
            return true;
        }

        const base64Url = tokenParts[1];
        const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
        const tokenData = JSON.parse(window.atob(base64));
        const expirationTime = tokenData.exp;

        // Check if the token has expired
        const currentTime = Math.floor(Date.now() / 1000); // Get current time in seconds
        if (expirationTime && currentTime >= expirationTime) {
            // Token has expired, perform logout actions
            removeItem("user_id");
            removeItem("access_token");
            return true;
        }

        return false;
    },
};
