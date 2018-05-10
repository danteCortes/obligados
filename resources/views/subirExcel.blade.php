<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Subir Archivo Excel</title>
</head>
<body>
  {!!Form::open(['url'=>'buscar', 'files'=>true])!!}
    <input type="file" name="obligados" required><br>
    <button type="submit">Subir y Buscar</button>
  {!!Form::close()!!}
</body>
</html>