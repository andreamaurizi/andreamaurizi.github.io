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
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="website icon" type="png" href="/Img/lego-icon-12.ico" />
  <script src="../Script/session.js"></script>
  <title>Ricrea il tuo Brick Set</title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
  <script>
    $(document).ready(function () {
      $(".navbar__links").click(function () {
        $(".div_dinamico").load(this.innerHTML + ".html",
          function (responseTxt, statusTxt, xhr) {
            if (statusTxt == "success") alert("Caricamento terminato");
            if (statusTxt == "error") alert("Errore" + xhr.status + ":" + xhr.statusText);
          });
      })

      $(".alertClose").click(function() {
        $(".alert").hide()
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
            // Mostra il popup di successo
            var alert = $('.alert.success');
            alert.find('.alertText').html(response);
            alert.show();
            setTimeout(function(){
              alert.hide();
            }, 5000);
          },
        });        
      });
    });
  </script>
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
          <p class="navbar__links">ReBuild</p>
        </li>
        <li class="navbar__btn">
          <a href="/SignUp_SignIn/Login_Registration.html" class="button">
            Area Riservata</a>
        </li>
      </ul>
    </div>
  </nav>

  <?php if (isset($_SESSION["user_id"])): ?>

    <div class="container">
      <form action="setUtente.php" class="search-bar" method="POST" onsubmit="mostraCustomAlert();">
        <input type="text" placeholder="Enter Lego Set Name" name="set">
        <button type="submit"><img src="/Img/search.png"></button>
      </form>
      <div class="div_dinamico"></div>

      <label>
        <input type="checkbox" class="alertCheckbox" autocomplete="off" />
        <div class="alert success" style="display:none">
          <span class="alertClose"><img src="../Img/close.png"  id="close-img"></span>
          <span class="alertText"><br class="clear"/></span>
        </div>
      </label>

    


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