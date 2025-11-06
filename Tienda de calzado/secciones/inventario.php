<!DOCTYPE html> 
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistema de Calzando a México</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" href="../img/icono.png">
    <!-- Incluir Chart.js para gráficas -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        
        .chart-container {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .view-toggle {
            margin: 15px 0;
            display: flex;
            gap: 10px;
        }
        
        .view-toggle button {
            padding: 8px 15px;
            background: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .view-toggle button.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .metric-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .metric-value {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .metric-label {
            color: #666;
            font-size: 14px;
        }
        
        .hidden {
            display: none;
        }
        
        /* NUEVO ESTILO: Contenedor principal para tabla y métricas */
        .main-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        
        /* Ajustar la tabla para que ocupe la columna izquierda */
        .table-container {
            grid-column: 1;
        }
        
        /* Estilo para el sidebar de métricas */
        .metrics-sidebar {
            grid-column: 2;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: fit-content;
        }
        
        .metrics-sidebar h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        
        /* Ajuste para pantallas más pequeñas */
        @media (max-width: 1024px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .metrics-sidebar {
                grid-column: 1;
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">
            <img src="../img/logo.png" alt="Calzando a México" />
        </div>
        <nav>
            <a href="../index.html" data-pagina="inicio.html">Inicio</a>
            <a href="../secciones/personal.html" data-pagina="personal.html">Personal</a>
           <a href="productos.php"  data-pagina="ventas.html">Productos</a>
            <a href="#" class="subrayado" data-pagina="inventario.html"><strong>Inventario y ventas</strong></a>
        </nav>
    </header>

    <main id="contenido">
        <h1>Inventario y Ventas - Año 2025</h1>
        <br>

        <div class="controls">
            <div class="filter-group">
                <label for="tienda-filter">Filtrar por tienda:</label>
                <select id="tienda-filter">
                    <option value="todas">Todas las tiendas</option>
                    <?php
                    // Conexión a la base de datos
                    $servername = "localhost";
                    $username = "root";
                    $password = "53304917Mm$";
                    $dbname = "calzando_mexico";
                    
                    // Crear conexión
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    
                    // Verificar conexión
                    if ($conn->connect_error) {
                        die("Error de conexión: " . $conn->connect_error);
                    }
                    
                    // Obtener tiendas únicas
                    $sql_tiendas = "SELECT DISTINCT id_tienda, nombre_tienda FROM tiendas ORDER BY id_tienda";
                    $result_tiendas = $conn->query($sql_tiendas);
                    
                    if ($result_tiendas->num_rows > 0) {
                        while($row = $result_tiendas->fetch_assoc()) {
                            echo "<option value=\"" . $row['id_tienda'] . "\">" . $row['nombre_tienda'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="unidad-filter">Filtrar por unidad:</label>
                <select id="unidad-filter">
                    <option value="todas">Todas las unidades</option>
                    <?php
                    // Obtener unidades de negocio únicas
                    $sql_unidades = "SELECT DISTINCT unidad_negocio FROM inventario_ventas ORDER BY unidad_negocio";
                    $result_unidades = $conn->query($sql_unidades);
                    
                    if ($result_unidades->num_rows > 0) {
                        while($row = $result_unidades->fetch_assoc()) {
                            echo "<option value=\"" . $row['unidad_negocio'] . "\">" . $row['unidad_negocio'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- Toggle para cambiar entre vista de tabla y gráficas 
        <div class="view-toggle">
            <button id="tableViewBtn" class="active">Vista Tabla</button>
           
        </div>-->

        <!-- NUEVO CONTENEDOR PRINCIPAL CON TABLA A LA IZQUIERDA Y MÉTRICAS A LA DERECHA -->
        <div class="main-content">
            <!-- Contenedor de tabla (izquierda) -->
            <div class="table-container" id="tableView">
                <table>
                    <thead>
                        <tr>
                            <th>Tienda</th>
                            <th>Unidad de Negocio</th>
                            <th>Mes</th>
                            <th>Inventario (pzs)</th>
                            <th>Ventas (pzs)</th>
                            <th>Rotación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Consulta para obtener los datos de inventario y ventas
                        $sql = "SELECT 
                                t.nombre_tienda,
                                iv.unidad_negocio,
                                iv.mes,
                                iv.inventario,
                                iv.ventas,
                                CASE 
                                    WHEN iv.inventario > 0 THEN ROUND(iv.ventas / iv.inventario, 2)
                                    ELSE 0
                                END as rotacion
                            FROM inventario_ventas iv
                            JOIN tiendas t ON iv.id_tienda = t.id_tienda
                            WHERE iv.anio = 2025
                            ORDER BY t.id_tienda, iv.unidad_negocio, 
                                    FIELD(iv.mes, 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre')";
                        
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['nombre_tienda'] . "</td>";
                                echo "<td>" . $row['unidad_negocio'] . "</td>";
                                echo "<td>" . $row['mes'] . "</td>";
                                echo "<td>" . number_format($row['inventario'], 0, ',', ',') . "</td>";
                                echo "<td>" . number_format($row['ventas'], 0, ',', ',') . "</td>";
                                echo "<td>" . $row['rotacion'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No se encontraron datos</td></tr>";
                        }
                        
                        // Cerrar conexión
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Métricas resumen (derecha) -->
            <div class="metrics-sidebar">
                <h3>Métricas de Desempeño</h3>
                <div class="metrics" id="metricsContainer">
                    <div class="metric-card">
                        <div class="metric-label">Ventas Totales</div>
                        <div class="metric-value" id="totalVentas">0</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Inventario Promedio</div>
                        <div class="metric-value" id="promedioInventario">0</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Rotación</div>
                        <div class="metric-value" id="rotacion">0</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Mejor Mes</div>
                        <div class="metric-value" id="mejorMes">-</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor de gráficas -->
        <div class="dashboard hidden" id="chartView">
            <div class="chart-container">
                <canvas id="ventasChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="inventarioChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="rotacionChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="comparativaChart"></canvas>
            </div>
        </div>
    </main>

    <script>
        // Datos globales para usar en las gráficas
        let datosFiltrados = [];
        let ventasChart, inventarioChart, rotacionChart, comparativaChart;

        // Funcionalidad DE filtrado
        document.addEventListener('DOMContentLoaded', function () {
            const tiendaFilter = document.getElementById('tienda-filter');
            const unidadFilter = document.getElementById('unidad-filter');
            const tableRows = document.querySelectorAll('tbody tr');
            const tableViewBtn = document.getElementById('tableViewBtn');
            const chartViewBtn = document.getElementById('chartViewBtn');
            const tableView = document.getElementById('tableView');
            const chartView = document.getElementById('chartView');

            // Cargar datos iniciales
            cargarDatosFiltrados();
            actualizarMetricas();
            inicializarGraficas();

            function filterTable() {
                const tiendaValue = tiendaFilter.value;
                const unidadValue = unidadFilter.value;

                tableRows.forEach(row => {
                    const tiendaCell = row.cells[0].textContent;
                    const unidadCell = row.cells[1].textContent;

                    const showTienda = tiendaValue === 'todas' || tiendaCell.includes(tiendaValue);
                    const showUnidad = unidadValue === 'todas' || unidadCell.includes(unidadValue);

                    row.style.display = (showTienda && showUnidad) ? '' : 'none';
                });

                // Actualizar datos filtrados y gráficas
                cargarDatosFiltrados();
                actualizarMetricas();
                actualizarGraficas();
            }

            function cargarDatosFiltrados() {
                const tiendaValue = tiendaFilter.value;
                const unidadValue = unidadFilter.value;
                
                datosFiltrados = [];
                
                tableRows.forEach(row => {
                    if (row.style.display !== 'none') {
                        datosFiltrados.push({
                            tienda: row.cells[0].textContent,
                            unidad: row.cells[1].textContent,
                            mes: row.cells[2].textContent,
                            inventario: parseInt(row.cells[3].textContent.replace(/,/g, '')),
                            ventas: parseInt(row.cells[4].textContent.replace(/,/g, '')),
                            rotacion: parseFloat(row.cells[5].textContent)
                        });
                    }
                });
            }

            function actualizarMetricas() {
                if (datosFiltrados.length === 0) return;
                
                // Calcular métricas
                const totalVentas = datosFiltrados.reduce((sum, item) => sum + item.ventas, 0);
                const promedioInventario = datosFiltrados.reduce((sum, item) => sum + item.inventario, 0) / datosFiltrados.length;
                const rotacionPromedio = datosFiltrados.reduce((sum, item) => sum + item.rotacion, 0) / datosFiltrados.length;
                
                // Encontrar el mes con más ventas
                const ventasPorMes = {};
                datosFiltrados.forEach(item => {
                    if (!ventasPorMes[item.mes]) {
                        ventasPorMes[item.mes] = 0;
                    }
                    ventasPorMes[item.mes] += item.ventas;
                });
                
                let mejorMes = '';
                let maxVentas = 0;
                for (const mes in ventasPorMes) {
                    if (ventasPorMes[mes] > maxVentas) {
                        maxVentas = ventasPorMes[mes];
                        mejorMes = mes;
                    }
                }
                
                // Actualizar la UI
                document.getElementById('totalVentas').textContent = totalVentas.toLocaleString();
                document.getElementById('promedioInventario').textContent = Math.round(promedioInventario).toLocaleString();
                document.getElementById('rotacion').textContent = rotacionPromedio.toFixed(2);
                document.getElementById('mejorMes').textContent = mejorMes;
            }

            function inicializarGraficas() {
                // Gráfica de ventas por mes
                const ventasCtx = document.getElementById('ventasChart').getContext('2d');
                ventasChart = new Chart(ventasCtx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Ventas',
                            data: [],
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Ventas por Mes'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Gráfica de inventario por mes
                const inventarioCtx = document.getElementById('inventarioChart').getContext('2d');
                inventarioChart = new Chart(inventarioCtx, {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Inventario',
                            data: [],
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Inventario por Mes'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Gráfica de rotación
                const rotacionCtx = document.getElementById('rotacionChart').getContext('2d');
                rotacionChart = new Chart(rotacionCtx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Rotación',
                            data: [],
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Rotación de Inventario'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Gráfica comparativa
                const comparativaCtx = document.getElementById('comparativaChart').getContext('2d');
                comparativaChart = new Chart(comparativaCtx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [
                            {
                                label: 'Ventas',
                                data: [],
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Inventario',
                                data: [],
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Comparativa Ventas vs Inventario'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            function actualizarGraficas() {
                if (datosFiltrados.length === 0) return;
                
                // Agrupar datos por mes
                const datosPorMes = {};
                datosFiltrados.forEach(item => {
                    if (!datosPorMes[item.mes]) {
                        datosPorMes[item.mes] = {
                            ventas: 0,
                            inventario: 0,
                            rotacion: 0,
                            count: 0
                        };
                    }
                    datosPorMes[item.mes].ventas += item.ventas;
                    datosPorMes[item.mes].inventario += item.inventario;
                    datosPorMes[item.mes].rotacion += item.rotacion;
                    datosPorMes[item.mes].count++;
                });
                
                // Calcular promedios
                const meses = Object.keys(datosPorMes);
                const ventas = meses.map(mes => datosPorMes[mes].ventas);
                const inventario = meses.map(mes => datosPorMes[mes].inventario / datosPorMes[mes].count);
                const rotacion = meses.map(mes => datosPorMes[mes].rotacion / datosPorMes[mes].count);
                
                // Ordenar por mes (de enero a diciembre)
                const ordenMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                                   'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                meses.sort((a, b) => ordenMeses.indexOf(a) - ordenMeses.indexOf(b));
                
                // Actualizar gráficas
                ventasChart.data.labels = meses;
                ventasChart.data.datasets[0].data = ventas;
                ventasChart.update();
                
                inventarioChart.data.labels = meses;
                inventarioChart.data.datasets[0].data = inventario;
                inventarioChart.update();
                
                rotacionChart.data.labels = meses;
                rotacionChart.data.datasets[0].data = rotacion;
                rotacionChart.update();
                
                comparativaChart.data.labels = meses;
                comparativaChart.data.datasets[0].data = ventas;
                comparativaChart.data.datasets[1].data = inventario;
                comparativaChart.update();
            }

            // Event listeners
            tiendaFilter.addEventListener('change', filterTable);
            unidadFilter.addEventListener('change', filterTable);
            
            tableViewBtn.addEventListener('click', function() {
                tableView.classList.remove('hidden');
                chartView.classList.add('hidden');
                tableViewBtn.classList.add('active');
                chartViewBtn.classList.remove('active');
            });
            
            chartViewBtn.addEventListener('click', function() {
                tableView.classList.add('hidden');
                chartView.classList.remove('hidden');
                tableViewBtn.classList.remove('active');
                chartViewBtn.classList.add('active');
                actualizarGraficas();
            });
        });
    </script>

    <footer>
        <p>© 2025 Calzando a México. Todos los derechos reservados.</p>
    </footer>

    <script src="index.js"></script>
    
</body>
</html>