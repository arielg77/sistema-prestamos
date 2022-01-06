const formsAjax = document.querySelectorAll(".FormAjax");

/**
 * Envía el formulario vía Ajax.
 * @param {Event} e Evento.
 */
function sendFormAjax(e) {
    
    //Se previene el evento por defecto
    e.preventDefault();
    
    let data = new FormData(this);
    let method = this.getAttribute("method");
    let action = this.getAttribute("action");
    let type = this.getAttribute("data-form");

    //Encabezados de la petición
    let headers = new Headers();

    //Configuración para la api de fetch
    let config = {
        method: method,
        headers: headers,
        mode: 'cors',
        cache: 'no-cache',
        body: data
    }

    //Almacena el texto para las alertas
    let alertText;

    //Se comprueba el tipo de operación para asignar el texto a las alertas
    if (type === "save") {
        alertText = "Los datos quedarán guardados en el sistema";
    } else if (type === "delete") {
        alertText = "Los datos serán eliminados completamente del sistema";
    } else if (type === "update") {
        alertText = "Los datos serám actualizados en el sistema";
    } else if (type === "search") {
        alertText = "Se eliminará el término de búsqueda y tendrás que escribir uno nuevo";
    } else if (type === "loans") {
        alertText = "Desea remover los datos seleccionados para prestamos o reservaciones";
    } else {
        alertText = "Qieres realizar la operación solicitada?";
    }

    //Se lanza la alerta
    Swal.fire({
        title: 'Estás seguro?',
        text: alertText,
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            fetch(action, config)
            .then(response => response.json())
            .then(response => {
                return alertsAjax(response);
            });
        }
    });

}

//Se anlaza el evento submit a los formularios
formsAjax.forEach(forms =>  {
    forms.addEventListener("submit", sendFormAjax);
});

/**
 * Construye los distintos tipos de alerta a partir del json proporcionado.
 * @param {json} alert Json que contiene los datos para construir la alerta.
 */
function alertsAjax(alert) {
    if (alert.Alert === "simple") {
        Swal.fire({
            title: alert.Title,
            text: alert.Text,
            type: alert.Type,
            confirmButtonText: 'Aceptar'
        });
    } else if (alert.Alerta === "reload") {
        Swal.fire({
            title: alert.Title,
            text: alert.Text,
            type: alert.Type,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.value) {
                location.reload();
            }
        });
    } else if (alert.Alert === "clean") {
        Swal.fire({
            title: alert.Title,
            text: alert.Text,
            type: alert.Type,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.value) {
                document.querySelector(".FormAjax").reset();            }
        });
    } else if (alert.Alert === "redirect") {
        window.location.href = alert.URL;
    }
}