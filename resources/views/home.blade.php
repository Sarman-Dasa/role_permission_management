<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Token</title>
</head>

<body>
    <center>
        <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()"
            class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
    </center>

    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyANC3Kw-vdOw0JuDU5_0SK0kY1rSaaO9N0",
            authDomain: "push-notification-5bac1.firebaseapp.com",
            projectId: "push-notification-5bac1",
            storageBucket: "push-notification-5bac1.appspot.com",
            messagingSenderId: "204238685037",
            appId: "1:204238685037:web:3cc4d00c92ba05de0cd064",
            measurementId: "G-Y40JE78PM6"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken()
                })
                .then(function(token) {
                    console.log(token);
                });
        }
    </script>
</body>

</html>
