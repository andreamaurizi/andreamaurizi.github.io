let stream = null;



function prova() {
    var CameraButton = document.getElementById("Camera");
    var FileButton = document.getElementById("File");
    var SearchBar = document.getElementById("search-bar");
    if (CameraButton.innerHTML === "Camera") {
        CameraButton.innerHTML = "Take picture";
        FileButton.style.display = "none";
        SearchBar.style.display = "none";
        startCamera();
    } else {
        CameraButton.innerHTML = "Camera";
        FileButton.style.display = "inline";
        SearchBar.style.display = "flex";
        captureImage();
    }
}


        async function startCamera() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');


            stream = await navigator.mediaDevices.getUserMedia({video: true});

            video.srcObject = stream;
            video.play();

            

        }

        function captureImage() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');

            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/png');
            console.log(dataUrl);

            // Stop the video stream
            stream.getTracks().forEach(track => track.stop());
            video.srcObject = null;

            // Show the captured image on the page
            const img = new Image();
            img.src = dataUrl;
            document.body.appendChild(img);
        }