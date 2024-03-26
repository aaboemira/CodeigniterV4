<!DOCTYPE html>
<html>
<head>
  <title>FCM Test</title>
  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.9.0/firebase-app.js";
    import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.9.0/firebase-messaging.js";

    const firebaseConfig = {
    apiKey: "AIzaSyDtslIP0eJDKiMCvdRILZOs2-dh74hrwDg",
    authDomain: "appnotifications-8184c.firebaseapp.com",
    projectId: "appnotifications-8184c",
    storageBucket: "appnotifications-8184c.appspot.com",
    messagingSenderId: "244036737371",
    appId: "1:244036737371:web:c5c1eaa56f6b6b17e7627e",
    measurementId: "G-ES1L5JSM1J"
  };

    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    function requestPermissionAndGetToken() {
      Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
          console.log('Notification permission granted.');

          getToken(messaging, { vapidKey: 'BKV8GO3fZBEKZcCD8YbMcyVZ7gacKtHCG2GAfvL0RaHUTTbWBa4dHcKAUye4jHg_aq5jnOhmqo3ViQISm0ofXFE' }).then((currentToken) => {
            if (currentToken) {
              console.log('FCM registration token:', currentToken);
            } else {
              console.log('No registration token available. Request permission to generate one.');
            }
          }).catch((err) => {
            console.error('An error occurred while retrieving token. ', err);
          });
        } else {
          console.log('Unable to get permission to notify.');
        }
      });
    }

    requestPermissionAndGetToken();
  </script>
  <script>
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('<?php echo base_url("firebase-messaging-sw.js"); ?>')
        .then((registration) => {
          console.log('Service worker registration succeeded:', registration);
        })
        .catch((error) => {
          console.log('Service worker registration failed:', error);
        });
    } else {
      console.log('Service workers are not supported.');
    }
  </script>
</head>
<body>
  <h1>FCM Test</h1>
</body>
</html>
