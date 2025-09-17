import './bootstrap.js';

let localStream = null;
let peerConnection = null;
const userId = window.USER_ID || null;

const iceServers = {
  iceServers: [
    { urls: 'stun:stun.l.google.com:19302' }
  ]
};

async function initLocalStream() {
  if (!localStream) {
    localStream = await navigator.mediaDevices.getUserMedia({ video:true, audio:true });
    const lv = document.getElementById('localVideo');
    if (lv) lv.srcObject = localStream;
  }
}

async function startCall(targetId) {
  if (!targetId) { alert('Enter target user id'); return; }
  await initLocalStream();
  peerConnection = new RTCPeerConnection(iceServers);
  localStream.getTracks().forEach(t => peerConnection.addTrack(t, localStream));

  peerConnection.ontrack = ev => {
    const rv = document.getElementById('remoteVideo');
    if (rv) rv.srcObject = ev.streams[0];
  };

  peerConnection.onicecandidate = ev => {
    if (ev.candidate) {
      window.axios.post('/call/ice', { to: parseInt(targetId), candidate: ev.candidate }).catch(console.error);
    }
  };

  const offer = await peerConnection.createOffer();
  await peerConnection.setLocalDescription(offer);
  await window.axios.post('/call/offer', { to: parseInt(targetId), offer }).catch(console.error);
  console.log('Offer sent to', targetId);
}

window.startCall = startCall;

if (userId) {
  window.Echo.private(`call.${userId}`)
    .listen('CallOffer', async (e) => {
      console.log('Offer received', e);
      await initLocalStream();
      peerConnection = new RTCPeerConnection(iceServers);
      localStream.getTracks().forEach(t => peerConnection.addTrack(t, localStream));
      peerConnection.ontrack = ev => {
        const rv = document.getElementById('remoteVideo');
        if (rv) rv.srcObject = ev.streams[0];
      };
      peerConnection.onicecandidate = ev => {
        if (ev.candidate) {
          window.axios.post('/call/ice', { to: e.fromUser, candidate: ev.candidate }).catch(console.error);
        }
      };
      // set remote offer
      await peerConnection.setRemoteDescription(e.offer);
      const answer = await peerConnection.createAnswer();
      await peerConnection.setLocalDescription(answer);
      await window.axios.post('/call/answer', { to: e.fromUser, answer }).catch(console.error);
    })
    .listen('CallAnswer', async (e) => {
      console.log('Answer received', e);
      if (peerConnection) await peerConnection.setRemoteDescription(e.answer);
    })
    .listen('IceCandidate', async (e) => {
      console.log('ICE received', e);
      try {
        if (peerConnection) await peerConnection.addIceCandidate(e.candidate);
      } catch (err) {
        console.warn('addIceCandidate error', err);
      }
    });
} else {
  console.warn('USER_ID not set on window');
}

// attach UI button
document.addEventListener('DOMContentLoaded', function(){
  const btn = document.getElementById('callBtn');
  if (btn) btn.addEventListener('click', function(){
    const t = document.getElementById('target').value;
    startCall(t);
  });
});
