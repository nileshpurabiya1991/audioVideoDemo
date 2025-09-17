<template>
  <div>
    <video ref="localVideo" autoplay muted playsinline></video>
    <video ref="remoteVideo" autoplay playsinline></video>
    <button @click="startCall">Start Call 1</button>
  </div>
</template>

<script>
export default {
 methods: {
  async startCall() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      alert("Camera/mic access is not available. Use https://localhost or https://IP.");
      return;
    }

    try {
      // 1️⃣ Get local audio/video
      // Request default audio & video
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true, video: true });

    // Attach stream to video element
    this.$refs.localVideo.srcObject = stream;
    console.log("✅ Media stream started", stream);


      // 2️⃣ Create WebRTC PeerConnection
      const peerConnection = new RTCPeerConnection();

      // 3️⃣ Add local tracks to PeerConnection
      stream.getTracks().forEach(track => peerConnection.addTrack(track, stream));

      // 4️⃣ Handle remote stream
      peerConnection.ontrack = (event) => {
        this.$refs.remoteVideo.srcObject = event.streams[0];
      };

      // 5️⃣ TODO: send offer/answer via Laravel Echo / Socket.IO

    } catch (err) {
      console.error("❌ Error accessing media devices:", err);
      alert("Failed to access camera/microphone. Check permissions.");
    }
  }
}

}
</script>
