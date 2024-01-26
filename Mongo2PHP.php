<?php
    require 'vendor/autoload.php';

    $client = new MongoDB\Client("mongodb://localhost:27017");

    $database = $client->selectDatabase('mydb');
    $coll = $database->selectCollection("Stock_Data");

    $cursor = $coll->find([], ['limit' => 25]);
    $data = iterator_to_array($cursor);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Table Display</title>
   <style>
       table {
           width: 100%;
           border-collapse: collapse;
       }
       th {
           border: 1px solid #ddd;
           padding: 8px;
           text-align: left;
           cursor: pointer;
           text-decoration: underline;
           text-decoration-color: blue;
       }
       th:hover {
           background-color: #f2f2f2;
       }
       td {
           border: 1px solid #ddd;
           padding: 8px;
           text-align: left;
       }
   </style>
   <script>
       function sortTable(n) {
           var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
           table = document.getElementById("stockTable");
           switching = true;
           dir = "asc";
           while (switching) {
               switching = false;
               rows = table.rows;
               for (i = 1; i < (rows.length - 1); i++) {
                   shouldSwitch = false;
                   x = rows[i].getElementsByTagName("TD")[n];
                   y = rows[i + 1].getElementsByTagName("TD")[n];
                   if (dir == "asc") {
                       if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                           shouldSwitch = true;
                           break;
                       }
                   } else if (dir == "desc") {
                       if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                           shouldSwitch = true;
                           break;
                       }
                   }
               }
               if (shouldSwitch) {
                   rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                   switching = true;
                   switchcount++;
               } else {
                   if (switchcount == 0 && dir == "asc") {
                       dir = "desc";
                       switching = true;
                   }
               }
           }
       }
   </script>
</head>
<body>
   <table border="1" id="stockTable">
       <thead>
           <tr>
               <th onclick="sortTable(0)">Symbol</th>
               <th onclick="sortTable(1)">Name</th>
               <th onclick="sortTable(2)">Price</th>
               <th onclick="sortTable(3)">Change</th>
               <th onclick="sortTable(4)">Volume</th>
               <!-- Add more headers for each field in your collection -->
           </tr>
       </thead>
       <tbody>
           <?php foreach ($data as $document): ?>
               <tr>
                   <td><?php echo $document['Symbol']; ?></td>
                   <td><?php echo $document['Name']; ?></td>
                   <td><?php echo $document['Prices']; ?></td>
                   <td><?php echo $document['Changes']; ?></td>
                   <td><?php echo $document['Volume']; ?></td>
                   <!-- Add more cells for each field in your collection -->
               </tr>
           <?php endforeach; ?>
       </tbody>
   </table>
</body>
</html>
