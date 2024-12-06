// Función para redirigir a la página de pedido
function realizarPedido() {
    window.location.href = '/Resources/Pages/Pedido.html';
}

// Función para regresar a la página de inicio
function regresarInicio() {
    window.location.href = '/Resources/Pages/Categories.html';
}

function updateOrderTime() {
    // Mostrar el mensaje de alerta
    alert('Pedido realizado con éxito.');

    // Obtener el elemento donde se mostrará la hora del pedido
    const orderTimeElement = document.getElementById('order-time');
    
    // Crear un objeto de fecha actual
    const now = new Date();
    
    // Calcular hora de llegada
    now.setMinutes(now.getMinutes() + 45);

    // Obtener las horas y los minutos
    const hours = now.getHours();
    const minutes = now.getMinutes();
    
    // Formatear la hora y los minutos con ceros a la izquierda si es necesario
    const formattedTime = `${hours < 10 ? '0' : ''}${hours}:${minutes < 10 ? '0' : ''}${minutes}`;
    
    // Actualizar el contenido del elemento con el tiempo de entrega
    orderTimeElement.innerText = `Su pedido llegará a las ${formattedTime}`;

    
}


