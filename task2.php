<?php
$price_type = $_POST['pprice_type'];
$price_min =$_POST['pprice_min'];
$price_max = $_POST['pprice_max'];
$sign = $_POST['psign'];
$number = $_POST['pnumber'];
$con = new mysqli("localhost", "root", "", "task1");
//$sql_filter = "SELECT * from pricelist where (CAST(". $price_type. " AS DECIMAL(10,2))  BETWEEN ". $price_min ." AND ". $price_max. ") AND  ((Sclad1 ". $sign. "". $number. ") OR (Sclad2 ". $sign. "". $number. ")) and REGEXP_LIKE(". $price_type .",'[0-9]+((\.|,)?[0-9]*)')";
//$stmt = $con->prepare("SELECT * from pricelist where (CAST(? AS DECIMAL(10,2))  BETWEEN ? AND ?) AND  ((Sclad1 ? ?) OR (Sclad2 ? ?)) and REGEXP_LIKE(?,'[0-9]+((\.|,)?[0-9]*)')");
if($stmt = $con->prepare("SELECT * from pricelist where (CAST(". $price_type. " AS DECIMAL(10,2))  BETWEEN ? AND ?) AND  ((Sclad1 >?) OR (Sclad2 >?)) and REGEXP_LIKE(". $price_type. ",'[0-9]+((\.|,)?[0-9]*)')"))
{
$stmt->bind_param('ddii',$price_min,$price_max,$number,$number);

$stmt->execute();
$stmt->bind_result($district1,$district2,$district3,$district4,$district5,$district6);
}
else {
    $error = $con->errno;
    echo $error; // 1054 Unknown column 'foo' in 'field list'
}



echo "<table ><tr><th>Наименование</th><th>Стоимость, руб</th><th>Стоимость опт, руб</th><th>Наличие на складе 1, шт</th><th>Наличие на складе 2, шт</th><th>Страна производства</th></tr>";
while ($stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . $district1 . "</td>";
            echo "<td>" . $district2 . "</td>";
            echo "<td>" . $district3 . "</td>";
            echo "<td>" . $district4 . "</td>";
            echo "<td>" . $district5 . "</td>";
            echo "<td>" . $district6 . "</td>";           
        echo "</tr>";
    }
    echo "</table>";    
?>