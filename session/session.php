<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../CSS/session.css" />
  <link rel="stylesheet" href="../CSS/popup.css" />
  <link rel="stylesheet" href="../CSS/mySet.css" />
  <link rel="stylesheet" href="../CSS/progress-bar.css">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../CSS/dialog.css" />
  <link rel="website icon" type="png" href="/Img/lego-icon-12.ico" />
  <script src="../Script/session.js"></script>
  <title>Ricrea il tuo Brick Set</title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
  <script>
    $(document).ready(function () {
      $(".navbar__links").click(function () {
        $(".div_dinamico").load(this.innerHTML + ".html",
          function (responseTxt, statusTxt, xhr) {
            if (statusTxt == "error") alert("Errore" + xhr.status + ":" + xhr.statusText);
          });
      })

      $(".button").click(function () {
        $(".div_dinamico").load(this.innerHTML + ".html",
          function (responseTxt, statusTxt, xhr) {

            if (statusTxt == "error") alert("Errore" + xhr.status + ":" + xhr.statusText);
          });
      })

      $(".alertClose").click(function() {
        $(".alert").hide();
      })

      $("#placeholder").click(function(){
        $(this).removeAttr('placeholder');
      })



      $("#Camera").click(function(){
        if (document.getElementById("Camera").innerHTML !== "Camera"){
          var mainArea = document.querySelector('.main-area');
          mainArea.style.display = 'block';
          $(".videoDiv").css("z-index","10000");
          $(".Camera").css("z-index","10000");
        }
        else{
          var mainArea = document.querySelector('.main-area');
          mainArea.style.display = 'none';
          $(".videoDiv").css("z-index","-10000");
        }
      })
    })
  </script>
  
  <script>
    $(document).ready(function() {
      $('.search-bar').submit(function(event) {
        event.preventDefault(); // Impedisce il comportamento predefinito del modulo

        // Invia i dati del modulo a setUtente.php tramite Ajax
        $.ajax({
          type: 'POST',
          url: 'setUtente.php',
          data: $(this).serialize(),
          success: function(response) {
            var alert = $('.alert.success');
            alert.find('.alertText').html(response);
            alert.show();
            setTimeout(function(){
              alert.hide();
              location.reload();
            },3000);
          },
        });        
      });
    });
  </script>

  <script>
     function caricaFile() {
      var input = document.createElement('input');
      input.type = 'file';
      input.click();
      
      input.onchange = function() {
        var file = input.files[0];
        if (file) {
          inviaFile(file);
        }
      };
    }
    
    function inviaFile(file) {
      var formdata = new FormData();
      formdata.append('image', file);
      
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'brickognizeCamera.php', true);
      
      xhr.send(formdata);

      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Azioni da eseguire dopo l'invio del file
          localStorage.setItem("brickognizeData", xhr.responseText);
            $(".div_dinamico").load("brickognize.html",
                function (responseTxt, statusTxt, xhr) {
                    

                    if (statusTxt == "error") alert("Errore" + xhr.status + ":" + xhr.statusText);
                });
        }
      };
    }
  </script>

  <script></script>

</head>
 
<body>
    <nav class="navbar">
      <div class="navbar__container">
        <a href="/" id="navbar__logo"><img src="/Img/lego-icon-12.ico" id="logo" /> ReBuild</a>
        <div class="navbar__toggle" id="mobile-menu">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </div>
        <ul class="navbar__menu">
          <li class="navbar__item">
            <p class="navbar__links">Recenti</p>
          </li>
          <li class="navbar__item">
            <p class="navbar__links">MySet</p>
          </li>
          <li class="navbar__item">
            <p class="navbar__links">Logout</p>
          </li>
          <li class="navbar__btn">
            <p class="button">ReBuild</p>
          </li>
          <div class="cssload-container" style = "display:none">
              <div class="cssload-zenith" ></div>
          </div>
        </ul>
      </div>
    </nav>
  

    
  <?php if (isset($_SESSION["user_id"])): ?>

    <div class="main-area"></div>

    <div class="container">
      <form id="search-bar" action="setUtente.php" class="search-bar" method="POST" onsubmit="mostraCustomAlert();">
        <input type="text" placeholder="Enter Lego Set Name" name="set" id="placeholder">
        <button type="submit"><img src="/Img/search.png" id="lente"></button>
      </form>


      <div class="searchDiv">
        <button id="Camera" class="Camera" onclick="openCamera()">Camera</button>
        <button id= "File" class="File" onclick="caricaFile()">Seleziona un file</button>
      </div>


      <div class="videoDiv">
        <video id="video" ></video>
        <canvas id="canvas" width="300" height="300" style="display:none"></canvas>
      </div>

      

      <label>
        <input type="checkbox" class="alertCheckbox" autocomplete="off" />
        <div class="alert success" style="display:none">
          <span class="alertClose"><img src="../Img/close.png"  id="close-img"></span>
          <span class="alertText"><br class="clear"/></span>
        </div>
      </label>
    </div>

    <div class="div_dinamico"></div>

    

    


    

  <?php else: ?>

    

    <script>
      setTimeout(function () {
        window.location.href =
          "../SignUp_SignIn/Login_Registration.html";
      }, 10);

    </script>
  <?php endif; ?>

  <script src="../Script/app.js"></script>
   
</body>

</html>

