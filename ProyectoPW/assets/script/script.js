const carrito = document.getElementById('carrito');
const elementos1 = document.getElementById('lista-1'); // Contenedor de platos
const elementos2 = document.getElementById('lista-2'); // Contenedor de bebidas
const lista = document.querySelector('#lista-carrito tbody');
const vaciarCarritoBtn = document.getElementById('vaciar-carrito');

cargarEventListeners();


// Evento para el botón "Comprar"
document.getElementById("Comprar").addEventListener("click", function(e) {
    console.log("Preparando el carrito antes de redirigir a comprar.php");

    // Recopilar todos los productos del carrito actual
    const carritoActual = [];
    const filas = lista.querySelectorAll('tr');
    
    filas.forEach(fila => {
        const id = fila.querySelector('.borrar').getAttribute('data-id');
        const nombre = fila.children[1].textContent;
        const precio = fila.children[2].textContent.trim(); // No convertir el precio, lo mantenemos como está
        const cantidad = 1; // Asumimos que la cantidad por defecto es 1 

        console.log({ id, nombre, precio, cantidad}); // Verificar los valores

        carritoActual.push({ id, nombre, precio, cantidad});
    });

    // Enviar los productos del carrito actual al servidor antes de redirigir
    fetch('php/guardar_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(carritoActual) // Mandamos todos los productos del carrito
    })
    .then(response => response.json())
    .then(data => {
        console.log('Carrito guardado en sesión:', data);
        // Ahora redirigimos al usuario a comprar.php
        window.location.href = "comprar.php"; // Redirigir después de guardar el carrito
    })
    .catch(error => {
        console.error('Error al guardar el carrito:', error);
    });
});


// Función para cargar eventos de añadir al carrito y eliminar elementos
function cargarEventListeners() {
    elementos1.addEventListener('click', comprarElemento); // Platos
    if (elementos2) { // Verificar si el contenedor de bebidas existe
        elementos2.addEventListener('click', comprarElemento); // Bebidas
    }
    carrito.addEventListener('click', eliminarElemento);
    vaciarCarritoBtn.addEventListener('click', vaciarCarrito);
}

// Función para agregar productos al carrito
function comprarElemento(e) {
    e.preventDefault();
    if (e.target.classList.contains('agregar-carrito')) {
        const elemento = e.target.closest('.box');
        leerDatosElemento(elemento, e);
    }
}

// Función para leer los datos del producto
function leerDatosElemento(elemento, e) {
    const infoElemento = {
        imagen: elemento.querySelector('img').src,
        titulo: elemento.querySelector('h3').textContent,
        precio: elemento.querySelector('.precio').textContent, // No convertimos el precio
        id: e.target.getAttribute('data-id')
    };
    insertarCarrito(infoElemento);
}

// Función para insertar el producto en el carrito visual
function insertarCarrito(elemento) {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <div style="width: 100px; height: 150px; overflow: hidden; border-radius: 5px;">
                <img src="${elemento.imagen}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>  
        </td>
        <td>${elemento.titulo}</td>
        <td>${elemento.precio}</td>
        <td><a href="#" class="borrar" data-id="${elemento.id}">X</a></td>
    `;
    lista.appendChild(row);

    // Guardar en la sesión (AJAX)
    const item = {
        id: elemento.id,
        nombre: elemento.titulo,
        precio: elemento.precio, // Mantener el precio tal como llega
        cantidad: 1
    };

    fetch('php/guardar_carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(item)
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
    })
    .catch(error => console.error('Error:', error));
}

// Función para eliminar un producto del carrito
function eliminarElemento(e) {
    e.preventDefault();
    if (e.target.classList.contains('borrar')) {
        const elemento = e.target.closest('tr');
        const elementoId = e.target.getAttribute('data-id');
        elemento.remove();

        // Enviar solicitud para eliminar el producto de la sesión
        fetch('php/eliminar_producto.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: elementoId })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Producto eliminado:', data);
        })
        .catch(error => {
            console.error('Error al eliminar el producto:', error);
        });

        console.log(`Elemento con ID ${elementoId} eliminado del carrito.`);
    }
}

// Función para vaciar el carrito
function vaciarCarrito() {
    // Vaciar visualmente el carrito en el frontend
    while (lista.firstChild) {
        lista.removeChild(lista.firstChild);
    }
    
    // Enviar solicitud AJAX para vaciar el carrito en la sesión en el servidor
    fetch('php/vaciar_carrito.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Carrito vaciado correctamente en el servidor.");
        } else {
            console.error("Error al vaciar el carrito en el servidor.");
        }
    })
    .catch(error => console.error('Error:', error));

    return false;
}
