<?php
// Solo procesar peticiones POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener datos y limpiar espacios
    $nombre = strip_tags(trim($_POST["q-nombre"]));
    $nombre = str_replace(array("\r","\n"),array(" "," "),$nombre);
    $correo = filter_var(trim($_POST["q-email"]), FILTER_SANITIZE_EMAIL);
    
    // Recibimos el resto de datos
    $telefono = trim($_POST["q-telefono"]);
    $empresa = trim($_POST["q-empresa"]);
    $estado = trim($_POST["q-estado"]);
    $litros = trim($_POST["q-litros"]);
    $municipio = trim($_POST["q-municipio"]);
    $mensaje = trim($_POST["q-mensaje"]);

    // Validación de Litros (Rango 25,000 - 64,000)
    // Se verifica que sea numérico y esté dentro del rango permitido
    if (!is_numeric($litros) || $litros < 25000 || $litros > 64000) {
        http_response_code(400);
        echo "La cantidad de litros debe estar entre 25,000 y 64,000.";
        exit;
    }

    // Validación de Teléfono (10 dígitos exactos)
    if (!preg_match("/^[0-9]{10}$/", $telefono)) {
        http_response_code(400);
        echo "El teléfono debe ser un número válido de 10 dígitos.";
        exit;
    }

    // Validación básica
    if ( empty($nombre) OR empty($telefono) OR empty($mensaje) OR !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Por favor completa el formulario e inténtalo de nuevo.";
        exit;
    }

    // ----------------------------------------------------
    // 1. CONFIGURACIÓN
    // ----------------------------------------------------
    $destinatario = "dsolis@alcoholeslacima.com.mx";
    
    // CORRECCIÓN 1: EL ASUNTO
    // Los acentos en el asunto rompen el correo si no se codifican así:
    $asunto_texto = "Cotización de $nombre";
    $asunto = "=?UTF-8?B?" . base64_encode($asunto_texto) . "?=";

    // ----------------------------------------------------
    // 2. CUERPO DEL CORREO
    // ----------------------------------------------------
    $contenido_email = "Has recibido una nueva cotización.\n\n";
    $contenido_email .= "Nombre: $nombre\n";
    $contenido_email .= "Correo: $correo\n";
    $contenido_email .= "Teléfono: $telefono\n";
    $contenido_email .= "Empresa: $empresa\n";
    $contenido_email .= "Estado: $estado\n";
    $contenido_email .= "Municipio: $municipio\n";
    $contenido_email .= "Litros: $litros\n\n";
    $contenido_email .= "Mensaje:\n$mensaje\n";

    // ----------------------------------------------------
    // 3. ENCABEZADOS (HEADERS) CON UTF-8
    // ----------------------------------------------------
    
    // Correo real de tu hosting (cámbialo si es necesario)
    $email_emisor_servidor = "$correo"; 

    // CORRECCIÓN 2: charset=UTF-8
    // Esto arregla los acentos en el cuerpo del mensaje (Teléfono, Cotización, etc.)
    $encabezados = "MIME-Version: 1.0" . "\r\n";
    $encabezados .= "Content-type: text/plain; charset=UTF-8" . "\r\n";
    
    $encabezados .= "From: Formulario Web <$email_emisor_servidor>" . "\r\n";
    $encabezados .= "Reply-To: $correo" . "\r\n";
    $encabezados .= "X-Mailer: PHP/" . phpversion();

    // ----------------------------------------------------
    // 4. ENVÍO
    // ----------------------------------------------------
    if (mail($destinatario, $asunto, $contenido_email, $encabezados)) {
        http_response_code(200);
        echo "¡Gracias! Tu mensaje ha sido enviado.";
    } else {
        http_response_code(500);
        echo "Algo salió mal y no pudimos enviar tu mensaje.";
    }

} else {
    http_response_code(403);
    echo "Hubo un problema con tu envío.";
}
?>