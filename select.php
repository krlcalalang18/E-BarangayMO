<?php

if(isset($_POST["emp_id"]))  
{
    $output = '';

    $connect = mysqli_connect("localhost", "root", "", "ebarangaydatabase");  
    $query = "SELECT c.complaintID, CONCAT(u.firstName, ' ', u.lastName) AS ComplainantName, u.cellphoneNumber AS ComplainantCellphoneNo, c.complaintDateAndTime, c.complaintAddress, ct.cityName AS City, bs.barangayName AS Barangay, c.complaintDetails, c.complaintType, c.priorityLevel, c.complaintStatus, c.complaintEvidence, c.remarks, c.remarksEvidence
    FROM complaint c
    INNER JOIN user u ON c.citizenID = u.userID
    INNER JOIN barangay_station bs ON c.barangayID = bs.barangayID
    INNER JOIN city ct ON c.barangayID = bs.cityID
    WHERE id = '".$_POST["emp_id"]."'";  
    $result = mysqli_query($connect, $query);  


    $output .= '  
    <div class="table-responsive">  
         <table class="table table-bordered">';  
    while($row = mysqli_fetch_array($result))  
    {  
         $output .= '  
              <tr>  
                   <td width="30%"><label>Name</label></td>  
                   <td width="70%">'.$row["complaintID"].'</td>  
              </tr>  
              <tr>  
                   <td width="30%"><label>Address</label></td>  
                   <td width="70%">'.$row["ComplainantName"].'</td>  
              </tr>  
              <tr>  
                   <td width="30%"><label>Gender</label></td>  
                   <td width="70%">'.$row["ComplainantCellphoneNo"].'</td>  
              </tr>  
              <tr>  
                   <td width="30%"><label>Designation</label></td>  
                   <td width="70%">'.$row["complaintDateAndTime"].'</td>  
              </tr>  
              <tr>  
                   <td width="30%"><label>Age</label></td>  
                   <td width="70%">'.$row["complaintAddress"].' Year</td>  
              </tr>  
              <tr>  
                   <td width="30%"><label>Age</label></td>  
                   <td width="70%">'.$row["City"].' Year</td>  
              </tr>  
              <tr>  
                   <td width="30%"><label>Age</label></td>  
                   <td width="70%">'.$row["Barangay"].' Year</td>  
              </tr>  
              <tr>  
                   <td width="30%"><label>Age</label></td>  
                   <td width="70%">'.$row["complaintDetails"].' Year</td>  
              </tr>  
              <tr>  
                   <td width="30%"><label>Age</label></td>  
                   <td width="70%">'.$row["complaintType"].' Year</td>  
              </tr>  
              <tr>  
                   <td width="30%"><label>Age</label></td>  
                   <td width="70%">'.$row["priorityLevel"].' Year</td>  
              </tr>  
              <tr>  
                   <td width="30%"><label>Age</label></td>  
                   <td width="70%">'.$row["complaintStatus"].' Year</td>  
              </tr>  
              ';  
    }  
    $output .= "</table></div>";  
    echo $output;  

}