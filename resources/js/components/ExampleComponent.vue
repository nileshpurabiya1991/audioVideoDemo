<template>
  <div>
    <h1>Audio/Video Call Demo</h1>
    <div v-if="incomingCall">
      <p>Incoming call from: {{ incomingCall.caller }}</p>
      <button @click="acceptCall">Accept</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      incomingCall: null,
    };
  },
  mounted() {
    // Listen to private channel
    const receiverId = 'UserB'; // replace with actual user id
    window.Echo.private('call.' + receiverId)
      .listen('IncomingCall', (e) => {
        console.log('Incoming call event', e);
        this.incomingCall = e;
      });
  },
  methods: {
    acceptCall() {
      alert('Call accepted! Now you can integrate WebRTC');
      this.incomingCall = null;
    }
  }
};
</script>
