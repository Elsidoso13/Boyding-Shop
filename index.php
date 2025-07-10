<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Pagina/funciones/conecta.php';
$con = conecta();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Consulta corregida basada en tu estructura de BD
$sql = "SELECT nombre_producto, costo_producto, stock, archivo, id_producto, codigo FROM productos WHERE eliminado = 0";

// Usar pg_query para PostgreSQL con recurso
$result = pg_query($con, $sql);

if (!$result) {
    die("Error en la consulta SQL: " . pg_last_error($con));
}

// Convertir resultado a array
$productos = [];
while ($row = pg_fetch_assoc($result)) {
    $productos[] = $row;
}

// Liberar resultado
pg_free_result($result);

include $_SERVER['DOCUMENT_ROOT'] . '/Pagina/public/administrador/menu2.php';

$usuario = $_SESSION["id"] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda en Línea</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFF8DC;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2F4F4F;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header h1 {
            margin: 0;
            font-size: 1.5em;
        }

        .search-bar {
            display: flex;
            align-items: center;
        }

        .search-bar input {
            padding: 8px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
            width: 200px;
        }

        .search-bar button {
            padding: 8px 15px;
            background-color: #228B22;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .promotions-container {
            position: relative;
            width: 100%;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .promotions-container img {
            max-width: 600px;
            max-height: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            position: absolute;
            display: none;
        }
        .promotions-container img.active {
            display: block;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            margin: 20px auto;
            max-width: 1200px;
        }

        .product-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-details {
            padding: 15px;
            text-align: center;
        }

        .product-details h3 {
            margin: 0 0 10px;
            font-size: 1.2em;
            color: #333;
        }

        .product-details p {
            margin: 5px 0;
            font-size: 1em;
            color: #555;
        }

        .product-details button {
            background-color: #2F4F4F;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .producto input[type="number"] {
            width: 80px;
            padding: 5px;
            margin-top: 10px;
            border-radius: 5px;
            border: none;
            background-color: #444;
            color: white;
            text-align: center;
        }
        .producto input[type="number"]:focus {
            outline: 2px solid #1e90ff;
        }
    </style>
</head>
<body>
    <div class="promotions-container">
        <?php
        $directory = "Promociones/plantillas/";
        $images = glob($directory . "*.{jpg,png,gif,jpeg}", GLOB_BRACE);

        foreach ($images as $image) {
            echo "<img src='$image' alt='Promoción'>";
        }
        ?>
    </div>

    <div class="products-grid">
        <?php foreach ($productos as $row): ?>
            <div class="product-card">
                <img src="/Pagina/Productos/images/<?php echo htmlspecialchars($row["archivo"]); ?>" alt="Imagen del producto">
                <div class="product-details">
                    <h3><?php echo htmlspecialchars($row["nombre_producto"]); ?></h3>
                    <p>$<?php echo number_format($row["costo_producto"], 2); ?></p>
                    <p>Stock: <?php echo htmlspecialchars($row["stock"]); ?></p> 
                    <p>Código: <?php echo htmlspecialchars($row["codigo"]); ?></p> 
                    <form id="form_carrito" method="POST" >
                        <input type="number" id="cantidad_<?php echo $row['id_producto']; ?>" placeholder="Cantidad" min="1">
                        <button type="button" onclick="agregarAlCarrito(<?php echo $row['id_producto']; ?>, document.getElementById('cantidad_<?php echo $row['id_producto']; ?>').value)">
                            Agregar al carrito
                        </button>
                    </form>
                    <form action="/Pagina/productos/detalle_producto.php" method="GET">
                        <input type="hidden" name="id" value="<?php echo $row['id_producto']; ?>">
                        <button type="submit">Detalles del producto</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const images = document.querySelectorAll(".promotions-container img");
            if (images.length === 0) return;

            let currentIndex = 0;

            images[currentIndex].classList.add("active");

            function showRandomImage() {
                images[currentIndex].classList.remove("active");
                currentIndex = Math.floor(Math.random() * images.length);
                images[currentIndex].classList.add("active");
            }

            setInterval(showRandomImage, 5000);
        });
    </script>
    <script>
        function agregarAlCarrito(id_producto, cantidad) {
            if (!cantidad || cantidad <= 0) {
                alert("Por favor, ingresa una cantidad válida");
                return;
            }

            $.ajax({
                url: 'pedidos/agregar_carrito.php',
                type: 'POST',
                data: {
                    ID_PRODUCTO: id_producto, 
                    cantidad: cantidad
                },
                success: function(response) {
                    alert(response);
                    // Limpiar el campo de cantidad después de agregar
                    document.getElementById('cantidad_' + id_producto).value = '';
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    alert("Error al agregar al carrito");
                }
            });
        }
    </script>
</body>
</html>