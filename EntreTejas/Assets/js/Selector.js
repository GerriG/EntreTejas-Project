function redirigir() {
    // Obtener el valor del radio button seleccionado
    var seleccion = document.querySelector('input[name="Comida"]:checked').value;

    // Redirigir según la selección
    if (seleccion === "comidas") {
        window.location.href = "/Resources/Pages/Platillos.html";
    } else if (seleccion === "bebidas") {
        window.location.href = "/Resources/Pages/Bebidas.html";
    } else if (seleccion === "postres") {
        window.location.href = "/Resources/Pages/Postres.html";
    }
}
