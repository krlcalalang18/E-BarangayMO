<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error!</title>
    <link rel="stylesheet" href="adminblockererror.css">

</head>
<body>
    <div class="container">
        <div class="popup">
            <img src="caution.png">
            <h2>Error!</h2>
            <p>Your Admin login credentials are incorrect.</p>
            <?php
               echo "<br><center><a class='btn' href='adminBlockerPage.html'>Back to login</a></center><br>";
            ?>
        </div>
    </div>

</body>
</html>



