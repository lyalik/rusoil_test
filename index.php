<?php
// Проверка, что форма была отправлена методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы
    $title = $_POST['title'];
    $category = $_POST['category'];
    $requestType = $_POST['requestType'];
    $warehouse = $_POST['warehouse'];
    $items = $_POST['items'];
    $comment = $_POST['comment'];

    // Проверка обязательных полей
    $errors = [];
    if (empty($title)) {
        $errors[] = 'Заголовок заявки обязателен для заполнения';
    }
    if (empty($category)) {
        $errors[] = 'Категория обязательна для заполнения';
    }
    if (empty($requestType)) {
        $errors[] = 'Вид заявки обязателен для заполнения';
    }

    // Если есть ошибки, выводим их
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . '<br>';
        }
    } else {
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

        // Отправка письма с прикрепленным файлом
        $file = $_FILES['file'];
        $fileCount = count($file['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = $file['name'][$i];
            $fileTmpName = $file['tmp_name'][$i];
            $fileSize = $file['size'][$i];
            $fileError = $file['error'][$i];
            if ($fileError === UPLOAD_ERR_OK) {
                $fileDestination = 'uploads/' . $fileName;
                move_uploaded_file($fileTmpName, $fileDestination);
                $message .= "Прикрепленный файл: $fileName\n";
            }
        }

        // Отправка письма
        // ...

        // Перенаправление на страницу после отправки формы
        header('Location: success.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Форма заявки</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Форма заявки</h1>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Заголовок заявки</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="category">Категория</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="">Выберите категорию</option>
                    <option value="Масла">Масла</option>
                    <option value="Шины">Шины</option>
                </select>
            </div>
            <div class="form-group">
                <label for="requestType">Вид заявки</label>
                <select class="form-control" id="requestType" name="requestType" required>
                    <option value="">Выберите вид заявки</option>
                    <option value="Запрос">Запрос</option>
                    <option value="Пополнения">Пополнения</option>
                    <option value="Спецзаказ">Спецзаказ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="warehouse">Склад поставки</label>
                <select class="form-control" id="warehouse" name="warehouse" required>
                    <option value="">Выберите склад поставки</option>
                    <option value="1 склад">1 склад</option>
                    <option value="2 склад">2 склад</option>
                </select>
            </div>
            <div class="form-group">
                <label for="items">Состав заявки</label>
                <div id="itemsContainer">
                    <div class="form-row">
                        <div class="col">
                            <select class="form-control" name="items[0][brand]" required>
                                <option value="">Выберите бренд</option>
                                <option value="Первый бренд">Первый бренд</option>
                                <option value="Второй бренд">Второй бренд</option>
                            </select>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="items[0][name]" placeholder="Наименование" required>
                        </div>
                        <div class="col">
                            <input type="number" class="form-control" name="items[0][quantity]" placeholder="Количество" required>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="items[0][packaging]" placeholder="Фасовка" required>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="items[0][client]" placeholder="Клиент" required>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-danger" onclick="removeItem(this)">-</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary mt-2" onclick="addItem()">+</button>
            </div>
            <div class="form-group">
                <label for="comment">Комментарий</label>
                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="file">Файлы</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="file" name="file[]" multiple>
                    <label class="custom-file-label" for="file">Выберите файлы</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>

    <script>
        var itemCount = 0;

        function addItem() {
            itemCount++;
            var itemHtml = `
                <div class="form-row">
                    <div class="col">
                        <select class="form-control" name="items[${itemCount}][brand]" required>
                            <option value="">Выберите бренд</option>
                            <option value="Первый бренд">Первый бренд</option>
                            <option value="Второй бренд">Второй бренд</option>
                        </select>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="items[${itemCount}][name]" placeholder="Наименование" required>
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" name="items[${itemCount}][quantity]" placeholder="Количество" required>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="items[${itemCount}][packaging]" placeholder="Фасовка" required>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="items[${itemCount}][client]" placeholder="Клиент" required>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-danger" onclick="removeItem(this)">-</button>
                    </div>
                </div>
            `;
            $('#itemsContainer').append(itemHtml);
        }

        function removeItem(button) {
            $(button).closest('.form-row').remove();
        }
    </script>
</body>
</html>