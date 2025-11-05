const contenido = document.getElementById("contenido");
    document.getElementById("inicioBtn").addEventListener("click", () => {
      contenido.innerHTML = `
        <h2>¡Bienvenido al sistema de calzando a México!</h2>
        <p> Somos una de las cadenas de zapaterías más grandes del país, con más de 15 tiendas a nivel nacional. Fundada hace 35 años.</p>
      `;
    });

    document.getElementById("inventarioBtn").addEventListener("click", () => {
      contenido.innerHTML = `
        <h2>Inventario</h2>
        <p>Aquí podrás ver y actualizar los productos disponibles en tu stock.</p>
      `;
    });

    document.getElementById("ventasBtn").addEventListener("click", () => {
      contenido.innerHTML = `
        <h2>Ventas</h2>
        <p>Registra las ventas realizadas y consulta tu historial de transacciones.</p>
      `;
    });

