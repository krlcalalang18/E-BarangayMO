<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5" >
    <form method="post" name="search">
        <input type="text" placeholder="Search Data">
        <button class="btn btn-dark btn-sm" name="submit"> Search </button>
</form> 
<div class="container my-5">
    <table class="table">
        
    <?php 
        if(isset($_POST['submit'])){
            $search=$_POST['search'];
        }

    ?>

</table>
</body>
</html>
