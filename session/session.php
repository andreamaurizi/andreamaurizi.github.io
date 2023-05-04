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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap"
      rel="stylesheet"
    />
    <script src="../Script/session.js"></script>
    <title>Document</title>
</head>
<body>
<nav class="navbar">
      <div class="navbar__container">
        <a href="/" id="navbar__logo"
          ><img src="/Img/lego-icon-12.ico" id="logo" /> ReBuild</a
        >
        <div class="navbar__toggle" id="mobile-menu">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </div>
        <ul class="navbar__menu">
          <li class="navbar__item">
            <a href="/" class="navbar__links">Home</a>
          </li>
          <li class="navbar__item">
            <a href="/tech.html" class="navbar__links">Tech</a>
          </li>
          <li class="navbar__item">
            <a href="/" class="navbar__links">Products</a>
          </li>
          <li class="navbar__btn">
            <a href="/SignUp_SignIn/Login_Registration.html" class="button"
              >Area Riservata</a
            >
          </li>
        </ul>
      </div>
    </nav>
    
    <?php if (isset($_SESSION["user_id"])): ?>

        <div class="container">
            <form action="" class="search-bar">
                <input type="text" placeholder="Enter Lego Set Name" name="q">
                <button type="submit" onclick = "searchBrickset()"><img src="/Img/search.png" ></button>
            </form>
        </div>
        <div id="zonaDinamica"></div>
    
    <?php else: ?>
        
        <script>
        
        setTimeout(function(){
            window.location.href=
            "./Login_Registration.html";
        }, 10);
        
        </script>
    <?php endif; ?>

    <script src="../Script/app.js"></script>
</body>
</html>