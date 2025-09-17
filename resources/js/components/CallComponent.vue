<template>
  <div>
    <h2>Audio/Video Call Demo</h2>
    <video ref="localVideo" autoplay muted playsinline style="width: 300px;"></video>
    <video ref="remoteVideo" autoplay playsinline style="width: 300px;"></video>
    <br/>
    <button @click="startCall">Call UserB</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      localStream: null,
      peerConnection: null,
      servers: { iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] },
      userId: Math.floor(Math.random() * 1000) // Random ID for demo
    }
  },
  mounted() {
    // Listen for call signal
    window.Echo.private('call-channel')
      .listen('CallSignal', (e) => {
        if (e.to === this.userId) {
          this.receiveCall(e.signal, e.from);
        }
      });
  },
  methods: {
    async startCall() {
      const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
      this.localStream = stream;
      this.$refs.localVideo.srcObject = stream;

      this.peerConnection = new RTCPeerConnection(this.servers);
      this.localStream.getTracks().forEach(track => this.peerConnection.addTrack(track, this.localStream));

      this.peerConnection.ontrack = (event) => {
        this.$refs.remoteVideo.srcObject = event.streams[0];
      };

      const offer = await this.peerConnection.createOffer();
      await this.peerConnection.setLocalDescription(offer);

      // Send signal via Echo
      axios.post('/send-signal', {
        signal: offer,
        from: this.userId,
        to: this.userId === 1 ? 2 : 1 // simple demo: send to other user
      });
    },

    async receiveCall(signal, from) {
      const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
      this.localStream = stream;
      this.$refs.localVideo.srcObject = stream;

      this.peerConnection = new RTCPeerConnection(this.servers);
      this.localStream.getTracks().forEach(track => this.peerConnection.addTrack(track, this.localStream));

      this.peerConnection.ontrack = (event) => {
        this.$refs.remoteVideo.srcObject = event.streams[0];
      };

      await this.peerConnection.setRemoteDescription(signal);
      const answer = await this.peerConnection.createAnswer();
      await this.peerConnection.setLocalDescription(answer);

      axios.post('/send-signal', {
        signal: answer,
        from: this.userId,
        to: from
      });
    }
  }
}
</script>
