// firebase-messaging-sw.js
importScripts('https://www.gstatic.com/firebasejs/8.0.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.0.0/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyDtslIP0eJDKiMCvdRILZOs2-dh74hrwDg",
    authDomain: "appnotifications-8184c.firebaseapp.com",
    projectId: "appnotifications-8184c",
    storageBucket: "appnotifications-8184c.appspot.com",
    messagingSenderId: "244036737371",
    appId: "1:244036737371:web:c5c1eaa56f6b6b17e7627e",
    measurementId: "G-ES1L5JSM1J"
});

const messaging = firebase.messaging();
