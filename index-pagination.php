<?php

include_once('connection.php');

//get page number 
if(($_GET['page no']) && $_GET['page_no'] !== ""){
    $page_no = $_GET['page_no'];
}
else
{
    $page_no = 1;
}

//total rows or records to display
$total_records_per_page = 10;
// get the page offset for the LIMIT query 
$offset = ($page_no - 1) * $total_records_per_page;


//get previous page 
$previous_page = $page_no - 1;

//get the next page 
$next_page = $page_no + 1;


//get the total count of records 
$result_count = mysqli_query($conn, "SELECT COUNT(*) as total_records FROM complaint")
or die (mysqli_error($conn));

//total records 
$records = mysqli_fetch_array($result_count);

//store total_records to a variable
$total_records = $records['total_records'];

//get the total pages 
$total_no_of_pages = ceil($total_records / $total_records_per_page);


//query string
$sql = "SELECT * FROM ebarnagaymodatabase LIMIT $offset , 
$total_records_per_page";

//result
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=16.0">
<title>Pagination</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
    <div class="container mt-5">
    <h1> PHP Pagination </h1>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Employee ID</th>
            <th>Department</th>
        </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_array($result)){?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php } mysqli_close($conn) ?>
        </tbody>
    </table>

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item"><a class="page-link <?= ($page_no <= 1)? 
            'disabled' : '';?>"  <?= ($page_no > 1)? 
            'href=?page_no='.$previous_page : '';?> >Previous</a></li>


            <?php for($counter = 1; $counter <= $total_no_of_pages; $counter++)
            { ?>

                <?php if($page_no !== $counter){?>
                 <li class="page-item"><a class="page-link" 
            href="?page_no=<?=$counter; ?>"><?= $counter; ?></a></li>
                    <?php }else { ?>
                        <li class="page-item"><a class="page-link active"> 
            <?= $counter; ?></a></li>
                    <?php } ?>
            <?php } ?>
                
        
            <li class="page-item"><a class="page-link" 
            href="#">2</a></li>
            <li class="page-item"><a class="page-link" 
            href="#">3</a></li>
            <li class="page-item"><a class="page-link <?= ($page_no >= $total_no_of_pages)? 
            'disabled' : '';?>" <?= ($page_no < $total_no_of_pages)? 
            'href=?page_no='.$next_page : '';?> >Next</a></li>
        </ul>
    </nav>
    <div class="p-10">
        <strong>Page <?= $page_no; ?> of <?=
        $total_no_of_pages; ?></strong>
    </div>
</body>
</html>