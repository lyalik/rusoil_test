<?php
require 'vendor/autoload.php'; // Путь к файлу автозагрузки PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Проверка, что форма была отправлена методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы
    $title = $_POST['title'];
    $category = $_POST['category'];
    $requestType = $_POST['requestType'];
    $warehouse = $_POST['warehouse'];
    $items = $_POST['items'];
    $comment = $_POST['comment'];

    // Формирование текста письма
    $message = "Заголовок заявки: $title\n";
    $message .= "Категория: $category\n";
    $message .= "Вид заявки: $requestType\n";
    $message .= "Склад поставки: $warehouse\n";
    $message .= "Состав заявки:\n";
    foreach ($items as $item) {
        $message .= "Бренд: {$item['brand']}, Наименование: {$item['name']}, Количество: {$item['quantity']}, Фасовка: {$item['packaging']}, Клиент: {$item['client']}\n";
    }
    $message .= "Комментарий: $comment\n";

    // Настройки SMTP для отправки письма
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Укажите SMTP-сервер
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com'; // Укажите вашу электронную почту
        $mail->Password = 'your-password'; // Укажите пароль от вашей электронной почты
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Отправка письма
        $mail->setFrom('your-email@example.com', 'Your Name'); // Укажите вашу электронную почту и имя отправителя
        $mail->addAddress('recipient@example.com', 'Recipient Name'); // Укажите электронную почту и имя получателя
        $mail->Subject = 'Новая заявка'; // Тема письма
        $mail->Body = $message; // Текст письма

        $mail->send();
        echo 'Письмо успешно отправлено!';
    } catch (Exception $e) {
        echo 'Ошибка при отправке письма: ' . $mail->ErrorInfo;
    }
} else {
    echo 'Некорректный метод запроса!';
}
?>