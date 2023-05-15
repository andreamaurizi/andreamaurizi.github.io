async function takePicture() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const stream = await navigator.mediaDevices.getUserMedia({video: true});

    video.srcObject = stream;
    video.play();

    const track = stream.getVideoTracks()[0];
    const imageCapture = new ImageCapture(track);

    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataUrl = canvas.toDataURL('image/png');
    console.log(dataUrl);

    track.stop();
    stream.getTracks().forEach(track => track.stop());
}