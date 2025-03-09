<?php
header('Content-Type: application/json'); // Configurar respuesta JSON

// Verifica si los datos han sido enviados correctamente
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
    exit();
}

// Validación de los campos
if (empty($_POST['name']) || empty($_POST['subject']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios y el correo debe ser válido."]);
    exit();
}

// Sanitización de los datos
file_put_contents("debug_email.txt", "TO: $to\nSUBJECT: $subject\nBODY: $body\nHEADERS: $headers");
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$m_subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
$message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

// Configuración del correo
$to = "beelu.dvt@gmail.com"; // Cambia esto a tu dirección de correo
$subject = "$m_subject - $name";

// Cuerpo del mensaje
$body = "Has recibido un nuevo mensaje desde el formulario de contacto.\n\n";
$body .= "Detalles del mensaje:\n";
$body .= "Nombre: $name\n";
$body .= "Correo: $email\n";
$body .= "Asunto: $m_subject\n";
$body .= "Mensaje:\n$message\n";

// Encabezados del correo
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Intento de envío del correo
if (mail($to, $subject, $body, $headers)) {
    http_response_code(200);
    echo json_encode(["status" => "success", "message" => "Tu mensaje ha sido enviado con éxito."]);
} else {
    http_response_code(500);
    error_log("Error al enviar el correo a $to");
    echo json_encode(["status" => "error", "message" => "No se pudo enviar el mensaje. Intenta nuevamente más tarde."]);
}
?>
