<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, target-densitydpi=fevice-dpi, initial-scale=1.0">
    <link href="/css/main.css" rel="stylesheet" type="text/css">
</head>
<body>
    <form action="/add" method="post">
        дата:<br />
        <input name="dt" type="date" class="inp" value="<?=$dt?>" required/><br />
        пробег:<br />
        <input name="distance" type="number" class="inp" /><br />
        сумма:<br />
        <input name="summa" type="number" class="inp" required/><br />
        цена:<br />
        <input name="price" type="number" class="inp" step="0.01" value="<?=$price?>" required/><br />
        <input value="записать" type="submit" class="btn"/>
    </form>
    <a href="/"><button class="btn">назад</button></a>
</body>
</html>
