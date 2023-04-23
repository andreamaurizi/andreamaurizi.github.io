<?php
    session_start();

    print_r($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Home</h1>
    <?php if (isset($_SESSION["user_id"])): ?>
        
        <p> You are logged in.</p>
        <form>
            <input type="text" id="searchInput" placeholder="Enter LEGO set name">
            <button type="submit" onclick="searchBrickset()">Search</button>
        </form>
        <p>I tuoi set:</p>
        <p><a href="logout.php">Log out<a></p>
    
    <?php else: ?>
        
        <p><a href="Login_Registration.html">Log in</a></p>

    <?php endif; ?>

</body>
</html>