<?php
// Only process POST reqeusts.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and remove whitespace.
    $nombre = strip_tags(trim($_POST["q-nombre"]));
    $nombre = str_replace(array("\r","\n"),array(" "," "),$nombre);
    $correo = filter_var(trim($_POST["q-email"]), FILTER_SANITIZE_EMAIL);
    $telefono = trim($_POST["q-telefono"]);
    $empresa = trim($_POST["q-empresa"]);
    $estado = trim($_POST["q-estado"]);
    $litros = trim($_POST["q-litros"]);
    $municipio = trim($_POST["q-municipio"]);
    $mensaje = trim($_POST["q-mensaje"]);

    // Check that data was sent to the mailer.
    if ( empty($nombre) OR empty($telefono) OR empty($mensaje) OR empty($empresa) OR empty($estado) OR empty($litros) OR empty($municipio) OR !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "¡Ups! Hubo un problema con tu envío. Por favor completa el formulario e inténtalo de nuevo.";
        exit;
    }

    // Update this to your desired email address.
    $destinatario = "elplayer_la@hotmail.com";
    $asunto = "Cotización de $nombre";

    // Email content.
    $contenido_email = "Nombre: $nombre\n";
    $contenido_email .= "Correo: $correo\n\n";
    $contenido_email .= "Asunto: $asunto\n\n";
    $contenido_email .= "Teléfono: $telefono\n\n";
    $contenido_email .= "Empresa: $empresa\n\n";
    $contenido_email .= "Estado: $estado\n\n";
    $contenido_email .= "Litros: $litros\n\n";
    $contenido_email .= "Municipio: $municipio\n\n";
    $contenido_email .= "Mensaje: $mensaje\n";

    // Email headers.
    $encabezados_email = "From: $nombre <$correo>\r\nReply-to: <$correo>";

    // Send the email.
    if (mail($destinatario, $asunto, $contenido_email, $encabezados_email)) {
        // Set a 200 (okay) response code.
        http_response_code(200);
        echo "¡Gracias! Tu mensaje ha sido enviado.";
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "¡Ups! Algo salió mal y no pudimos enviar tu mensaje.";
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Hubo un problema con tu envío, por favor inténtalo de nuevo.";
}