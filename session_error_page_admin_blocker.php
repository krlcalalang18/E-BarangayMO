<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error!</title>
    <link rel="stylesheet" href="adminblockererror.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
    <style>
        .container {
            max-width: 100%;
            margin-right: 0px;
            margin-left: 0px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="popup">
            <img src="caution.png">
            <h2>Error!</h2>
            <p>Your Admin login credentials are incorrect.</p>
            <?php
               echo "<br><center><a class='btn btn-danger w-100' href='adminBlockerPage.html'>Back to login</a></center><br>";
            ?>
        </div>
    </div>

</body>
</html>



