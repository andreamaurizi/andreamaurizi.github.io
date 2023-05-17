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

            $(document).ready(function() {
                $.ajax({
                    type:"POST",
                    url:"brickognize.php",
                    data:{imgBase64:dataUrl},
                    success:function(msg){
                        console.log("image uploaded");
                        console.log(msg);
                    }
                })
            })

            
           
}



function getImg(setId,i){
    $.ajax({
        type: 'POST',
        url: 'MySet.php',
        data: {
            setId: setId
        },
        success: function(response) {
            var risposta = response.split(",");
            var nome = risposta[0];
            var imageURL = risposta[1];
            settaImmagine(nome, imageURL,i);
        },
    });
    
};




function settaImmagine(nome, imageURL,i){
    var elementList = document.getElementById("lista");

    // Crea un nuovo elemento <li>
    var newItem = document.createElement("li");
    newItem.classList.add("element-item");
    newItem.className = "element-id"

    // Crea l'elemento <img>
    var newImage = document.createElement("img");
    newImage.src = imageURL;
    newImage.alt = "Immagine";
    newImage.className = "list-img"
    newImage.id = nome;

    // Crea l'elemento <span> per il testo
    var newText = document.createElement("span");
    newText.textContent = nome;
    newText.className = "Set-Name"

    // Aggiungi l'immagine e il testo come figli del nuovo elemento <li>
    newItem.appendChild(newImage);
    newItem.appendChild(newText);

    // Aggiungi il nuovo elemento <li> alla lista
    elementList.appendChild(newItem);
    
}

