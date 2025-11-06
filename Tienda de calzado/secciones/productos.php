<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "53304917Mm$", "calzando_mexico");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Consultar los productos
$sql = "SELECT sku, nombre_producto, talla, color, temporada, categoria, piezas, costo FROM productos";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Productos | Calzando a México</title>
  <link rel="stylesheet" href="../index.css">
  <link rel="icon" href="../img/icono.png">
</head>
<body>

  <header>
    <div class="logo">
      <img src="../img/logo.png" alt="Calzando a México" />
    </div>
    <nav>
      <a href="../index.html" data-pagina="inicio.html">Inicio</a>
      <a href="../secciones/personal.html" data-pagina="personal.html">Personal</a>
      <a href="#" data-pagina="ventas.html"><strong>Productos</strong></a>
      <a href="../secciones/inventario.php" data-pagina="inventario.html">Inventario y ventas</a>
    </nav>
  </header>

  <main id="contenido">
    <h1>Listado de Productos</h1>
    <br>

    <section class="tabla-productos">
      <table>
        <thead>
          <tr>
            <th>SKU</th>
            <th>Nombre del Producto</th>
            <th>Talla</th>
            <th>Color</th>
            <th>Temporada</th>
            <th>Categoría</th>
            <th>Piezas</th>
            <th>Costo</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($resultado->num_rows > 0) {
              while ($fila = $resultado->fetch_assoc()) {
                  echo "<tr>
                          <td>{$fila['sku']}</td>
                          <td>{$fila['nombre_producto']}</td>
                          <td>{$fila['talla']}</td>
                          <td>{$fila['color']}</td>
                          <td>{$fila['temporada']}</td>
                          <td>{$fila['categoria']}</td>
                          <td>{$fila['piezas']}</td>
                          <td>$" . number_format($fila['costo'], 2) . "</td>
                        </tr>";
              }
          } else {
              echo "<tr><td colspan='8'>No hay productos registrados.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </section>
  </main>

  <footer>
    <p>© 2025 Calzando a México. Todos los derechos reservados.</p>
  </footer>

</body>
</html>

<?php
$conexion->close();
?>
