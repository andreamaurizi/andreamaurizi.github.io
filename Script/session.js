let stream = null;



function openCamera() {
    var CameraButton = document.getElementById("Camera");
    var FileButton = document.getElementById("File");
    
    if (CameraButton.innerHTML === "Camera") {
        CameraButton.innerHTML = "Take picture";
        FileButton.style.display = "none";
        
        startCamera();
    } else {
        CameraButton.innerHTML = "Camera";
        FileButton.style.display = "inline";
        captureImage();
    }
}


async function startCamera() {
    const video = document.getElementById('video');

    stream = await navigator.mediaDevices.getUserMedia({ video: true });

    video.srcObject = stream;
    video.play();

}

function captureImage() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');

    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataUrl = canvas.toDataURL('image/png');
    console.log(dataUrl);

    // Il video stream viene fermato
    stream.getTracks().forEach(track => track.stop());
    video.srcObject = null;


    // Mettiamo l'immagine in una variabile img
    const img = new Image();
    img.src = dataUrl;

    

    // Trasformiamo l'immagine della canvas in un oggetto blob
    canvas.toBlob(function(blob) {
        // Creiamo la variabile file
        var file = new File([blob], 'captured-image.jpg', { type: 'image/jpeg' });

        //console.log(file);

        
        var formData = new FormData();
        formData.append("image", file, "captured-image.jpg");

        
        // Mandiamo l'oggetto formData al php con la fetch
        fetch('brickognizeCamera.php', {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            // Controlliamo se la risposta ha avuto successo
            if (response.ok) {
                return response.text();
            } else {
                throw new Error('Error: ' + response.status);
            }
        })
        .then(function(data) {
            //console.log(data);
            localStorage.setItem("brickognizeData", data);
            $(".div_dinamico").load("brickognize.html",
                function (responseTxt, statusTxt, xhr) {
                    

                    if (statusTxt == "error") alert("Errore" + xhr.status + ":" + xhr.statusText);
                });
        })
        .catch(function(error) {
            console.error(error);
        });

        
    }, "image/jpeg");



}



function getImg(setId) {
    $.ajax({
        type: 'POST',
        url: 'MySet.php',
        data: {
            setId: setId
        },
        success: function (response) {
            var risposta = response.split("|");
            var nome = risposta[0];
            var imageURL = risposta[1];
            if(imageURL=="nessuna immagine"){
                imageURL = "https://theminifigclub.com/wp-content/uploads/2021/05/5.12.21-Shocked-Audience.png"
            }
            settaImmagine(nome, imageURL, setId);
        },
    });

};

// Come getImg ma per rebuild
function getImg2(setId, percentuale, parti_totali, parti_mancanti) {
    $.ajax({
        type: 'POST',
        url: 'MySet.php',
        data: {
            setId: setId
        },
        success: function (response) {
            var risposta = response.split("|");
            var nome = risposta[0];
            var imageURL = risposta[1];
            if(imageURL=="nessuna immagine"){
                imageURL = "https://theminifigclub.com/wp-content/uploads/2021/05/5.12.21-Shocked-Audience.png"
            }
            console.log(percentuale);
            settaImmagine2(nome, imageURL, setId, percentuale, parti_totali, parti_mancanti);
        },
    });

};



function settaImmagine(nome, imageURL, setId) {
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
    newImage.alt = setId;
    // Crea l'elemento <span> per il testo
    var newText = document.createElement("span");
    newText.textContent = nome;
    newText.className = "Set-Name";

    // Aggiungi l'immagine e il testo come figli del nuovo elemento <li>
    newItem.appendChild(newImage);
    newItem.appendChild(newText);

    // Aggiungi il nuovo elemento <li> alla lista
    elementList.appendChild(newItem);

}

// Come settaImmagine ma per rebuild
function settaImmagine2(nome, imageURL, setId, percentuale, parti_totali, parti_mancanti) {
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
    newImage.alt = setId;
    // Crea l'elemento <span> per il testo
    var newText = document.createElement("span");
    newText.textContent = nome;
    newText.className = "Set-Name";

    var percentualeText = document.createElement("span");
    percentuale =  percentuale.toFixed(1);
    percentualeText.textContent = percentuale + "%";
    percentualeText.className = "Percentuale";

    var partiText = document.createElement("span");
    partiText.textContent = parti_mancanti + " parti mancanti su " + parti_totali;
    partiText.className = "Parti";

    

    // Aggiungi l'immagine e il testo come figli del nuovo elemento <li>
    newItem.appendChild(newImage);
    
    // esclusivo a rebuild
    newItem.appendChild(percentualeText);
    newItem.appendChild(partiText);

    newItem.appendChild(newText);

    

    // Aggiungi il nuovo elemento <li> alla lista
    elementList.appendChild(newItem);

}


function handleElementClick(event) {
    var elementId = event.target.id;

    if(elementId == "lista"){
        return 0;
    }

    var dialogContent = document.getElementById('setName');
    dialogContent.textContent = elementId;

    var imgUrl = document.getElementById(elementId).getAttribute("src");

    document.getElementById("img-content").src = imgUrl;

    var dialog = document.getElementById('dialog');
    dialog.style.display = 'block';

    var mainArea = document.querySelector('.main-area');
    mainArea.style.display = 'block';

    var setId = document.getElementById(elementId).getAttribute("alt");

    var jsonString = localStorage.getItem("myData");

    var oggetti = JSON.parse(jsonString);

    var part_idArray = [];
    var quantityArray = [];
    
    for(var i = 0; i < oggetti.length; i++){
        part_idArray.push(oggetti[i].part_id);
        quantityArray.push(oggetti[i].total_value);
    }
    
    $.ajax({
        type: 'POST',
        url: 'listaparti.php',
        data: {
            setId: setId
        },
        success: function (response) {
            var oggetto = JSON.parse(response);
            for (var i = 0; i < oggetto.length; i++) {
                var part_id = oggetto[i].part_id;
                var quantity = oggetto[i].quantity;
                var myQuantity = 0;
                var index = part_idArray.indexOf(part_id);
                if(index == -1){
                    myQuantity = 0;
                }else{
                    myQuantity = quantityArray[index];
                }




                var elementList = document.getElementById("dialog-content");

                // Crea un nuovo elemento <li>
                var newItem = document.createElement("li");
                newItem.classList.add("part-item");
                newItem.className = "part-id"
                var partquantity = part_id + " : " + quantity +"   "+      "(" + myQuantity + ")";



                var newText = document.createElement("span");
                newText.textContent = partquantity;
                newText.className = "part-name";
                newText.id = part_id;
               
                myQuantity = parseInt(myQuantity);
                quantity = parseInt(quantity);
                newItem.appendChild(newText);
                
                // Aggiungi il nuovo elemento <li> alla lista
                elementList.appendChild(newItem);
                
                if(myQuantity < quantity){

                    var elementoSpan = $("#"+ part_id);
                    elementoSpan.css("color","red"); 

                }
            }
        }
    });
}

// L'handleElementClick di Brickognize
function handleElementClickBrickognize(event) {
    var elementId = event.target.id;

    if(elementId == "lista"){
        return 0;
    }
    var set = document.getElementById(elementId).getAttribute("alt");

    var data = new URLSearchParams();
    data.append("set", set);
  
    // Mandiamo la richiesta con fetch
    fetch("setUtenteBrickognize.php", {
      method: "POST",
      body: data
    })
      .then(function(response) {
        if (response.ok) {
          return response.text();
        } else {
          throw new Error("Error: " + response.status);
        }
      })
      .then(function(result) {
        console.log(result);
        var alert = $('.alert.success');
                    alert.find('.alertText').html(result);
                    alert.show();
                    setTimeout(function(){
                        alert.hide();
                        location.reload();
                      }, 3000);
      })
      .catch(function(error) {
        console.log(error);
      });
    
    var dialogList = document.getElementById('lista');
    dialogList.innerHTML = '';
}

// Funzione per chiudere l'elemento di dialogo
function closeDialog() {
    var dialog = document.getElementById('dialog');
    dialog.style.display = 'none';

    var mainArea = document.querySelector('.main-area');
    mainArea.style.display = 'none';

    var dialogList = document.getElementById('dialog-content');
    dialogList.innerHTML = '';
}






