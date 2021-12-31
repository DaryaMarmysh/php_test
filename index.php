<!DOCTYPE html>
<html>

<head>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <title>task</title>
    <meta charset="utf-8" />
    <style>
    .max {
        background: red;
    }

    .min {
        background: green;
    }

    table,
    th,
    td {
        border: 1px solid black;
        border-spacing: 0;

    }

    th,
    td {

        padding: 4px;
    }
    </style>
</head>

<body>
    <?php
$conn = new mysqli("localhost", "root", "", "task1");
if($conn->connect_error){
    die("Ошибка: " . $conn->connect_error);
}
$sql = "Select * from pricelist";
$max_value_sql="Select MAX(CAST(PriceRUB AS DECIMAL(10,2)) ) as max from pricelist where  REGEXP_LIKE(PriceRUB,'[0-9]+((\.|,)?[0-9]*)')";
$min_value_sql="Select MIN(CAST(PriceOptRUB AS DECIMAL(10,2)) ) as min from pricelist where REGEXP_LIKE(PriceOptRUB,'[0-9]+((\.|,)?[0-9]*)')";
$max_value=$conn->query($max_value_sql);
$min_value=$conn->query($min_value_sql);
while($row = mysqli_fetch_array($min_value)){
 
    $min_value_result= $row['min'];
}
while($row = mysqli_fetch_array($max_value)){
 
    $max_value_result= $row['max'];
}
if($result = $conn->query($sql)){
    $rowsCount = $result->num_rows; // количество полученных строк
    echo "<p>Получено объектов: $rowsCount</p>";
    echo "<table ><tr><th>Наименование</th><th>Стоимость, руб</th><th>Стоимость опт, руб</th><th>Наличие на складе 1, шт</th><th>Наличие на складе 2, шт</th><th>Страна производства</th><th>примечание</th></tr>";
    foreach($result as $row){
        if($max_value_result==$row["PriceRUB"])
        {
            echo "<tr class='max'>";

        }
        else if($min_value_result==$row["PriceOptRUB"])
        {
            echo "<tr class='min'>";

        }
        else
        {
            echo "<tr>";
        }

            echo "<td>" . $row["Name"] . "</td>";
            echo "<td>" . $row["PriceRUB"] . "</td>";
            echo "<td>" . $row["PriceOptRUB"] . "</td>";
            echo "<td>" . $row["Sclad1"] . "</td>";
            echo "<td>" . $row["Sclad2"] . "</td>";
            echo "<td>" . $row["Country"] . "</td>";
            if($row["Sclad1"]<20 || $row["Sclad2"]<20 )
            {
                echo "<td>Осталось мало!! Срочно докупите!!!</td>";

            }
            else{
                echo "<td></td>";
            }
        echo "</tr>";
    }
    echo "</table>";    
} else{
    echo "Ошибка: " . $conn->error;
}
$sum_from_sklad1_and_sclad2="Select SUM(Sclad1)+SUM(Sclad2) from pricelist";
$avg_r="Select AVG(PriceRUB) as avg_price_rub from pricelist";
$avg_opt="Select AVG(PriceOptRUB) as avg_price_opt_rub from pricelist;";
$sum = $conn->query($sum_from_sklad1_and_sclad2);
$avg_r=$conn->query($avg_r);
$avg_opt=$conn->query($avg_opt);
while($row = mysqli_fetch_array($sum)){
 
    echo "<p><b>Всего товаров на складах</b>:". $row['SUM(Sclad1)+SUM(Sclad2)'] ."</p>";
}
while($row = mysqli_fetch_array($avg_r)){
 
    echo "<p><b>Средняя стоимость розничной цены товара</b>: ". round($row['avg_price_rub'],3) ."</p>";
}
while($row = mysqli_fetch_array($avg_opt)){
 
    echo "<p><b>Средняя стоимость оптовой цены товара</b>: ". round($row['avg_price_opt_rub'],3) ."</p>";
}
echo "<p><b>Задание 2. Фильтрация</b></p>";

//$conn->close();
?>
    <form name="form">
        <p style="display:inline;"><b>Показать товары, у которых </b>
            <select name="price_type" id="price_type" style="display:inline;">
                <option value="PriceRUB">Розничная цена</option>
                <option value="PriceOptRUB">Оптовая цена</option>
            </select>
            <b>от</b>
            <input type="text" class='field' name="min" id="price_min" pattern="^[0-9]([.,][0-9]{1,3})?$" value="0" />
            <b>до</b>
            <input type="text" class='field' name="max" id="price_max" pattern="[0-9]+((\.|,)?[0-9]*)"
                value="10000000" />
            <b>рублей и на складе</b>
            <select name="sign" id="sign" style="display:inline;">
                <option value=">">Больше</option>
                <option value="<">Меньше</option>
            </select>
            <input class='field' type="number" name="number" id="number" value="0" pattern="[0-9]+((\.|,)?[0-9]*)" />
            <input type="button" value="ПОКАЗАТЬ ТОВАРЫ" onclick="get_rows()">
        </p>
    </form>
    <script>
    function get_rows() {
        var fields = form.querySelectorAll('.field');
        for (var i = 0; i < fields.length; i++) {
            if (!fields[i].value) 
            {
                fields[i].value = 0;              
        }
        if (!/^[0-9]*([.,][0-9]{1,3})?$/.test(fields[i].value))
        {
            fields[i].value='0';
            alert('try again');
            return;
        }
    }
    
    var price_type = $('#price_type').val();
    var price_min = $('#price_min').val();
    var price_max = $('#price_max').val();
    var sign = $('#sign').val();
    var number = $('#number').val();


    $.ajax({
        type: "POST",
        url: "task2.php",
        data: {
            pprice_type: price_type,
            pprice_min: price_min,
            pprice_max: price_max,
            psign: sign,
            pnumber: number
        }
    }).done(function(row_answer) { 
        
        document.getElementById("res").innerHTML = row_answer;
       
    });
    }
    </script>
    <p id="res"></p>
    <?php
?>
</body>

</html>