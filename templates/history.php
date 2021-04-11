<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, target-densitydpi=fevice-dpi, initial-scale=1.0">
    <link href="/css/main.css" rel="stylesheet" type="text/css">
</head>
<body>
    <h3>История покупок</h3>
    <?php
        foreach ($data as $row) {
            echo '<div>' . $row['dt'] . ' - ' . $row['amount'] . '</div>';
        }
    ?>
    <a href="/"><button class="btn">назад</button></a>
</body>
</html>
