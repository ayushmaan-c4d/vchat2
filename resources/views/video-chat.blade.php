<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Video Chat</title>
    <style>
        #local-video, #remote-video {
            width: 45%;
            margin: 10px;
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <h1>Video Chat</h1>
    <div id="users-list">
        @foreach ($users as $user)
            <button onclick="startCall('{{ $user->id }}')">{{ $user->name }}</button>
        @endforeach
    </div>

    <video id="local-video" autoplay muted></video>
    <video id="remote-video" autoplay></video>

    <script src="https://cdn.jsdelivr.net/npm/simple-peer@9.9.0/simple-peer.min.js"></script>
    {{-- <script src="{{ mix('js/app.js') }}"></script>  --}} 
    <script type="module">
        import Echo from './laravel-echo';
        window.Pusher = require('pusher-js');

        const echo = new Echo({
            broadcaster: 'pusher',
            key: document.querySelector('meta[name="pusher-key"]').getAttribute('content'),
            cluster: 'ap2',
            encrypted: true
        });

        let localStream;
        let peer;
        const localVideo = document.getElementById('local-video');
        const remoteVideo = document.getElementById('remote-video');

        navigator.mediaDevices.getUserMedia({ video: true, audio: true })
            .then(stream => {
                localStream = stream;
                localVideo.srcObject = stream;
            });

        function startCall(userId) {
            peer = new SimplePeer({
                initiator: true,
                trickle: false,
                stream: localStream
            });

            peer.on('signal', data => {
                fetch('/video/call-user', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ userId: userId, signalData: data })
                });
            });

            peer.on('stream', stream => {
                remoteVideo.srcObject = stream;
            });

            echo.channel('user.' + userId)
                .listen('CallInitiated', event => {
                    peer.signal(event.signalData);
                });
        }

        echo.channel('user.' + userId)
            .listen('CallAccepted', event => {
                peer.signal(event.signalData);
            });
    </script>
</body>
</html>