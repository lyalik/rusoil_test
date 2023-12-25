<?php
// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы
    $title = $_POST['title'];
    $category = $_POST['category'];
    $requestType = $_POST['requestType'];
    $warehouse = $_POST['warehouse'];
    $items = $_POST['items'];
    $comment = $_POST['comment'];

    // Перенаправление на страницу после отправки формы
    header('Location: success.php');
    exit();
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
        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Заголовок заявки</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="category">Категория</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="Масла">Масла</option>
                    <option value="Шины">Шины</option>
                </select>
            </div>
            <div class="form-group">
                <label for="requestType">Вид заявки</label>
                <select class="form-control" id="requestType" name="requestType" required>
                    <option value="Запрос">Запрос</option>
                    <option value="Пополнения">Пополнения</option>
                    <option value="Спецзаказ">Спецзаказ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="warehouse">Склад поставки</label>
                <select class="form-control" id="warehouse" name="warehouse" required>
                    <option value="1 склад">1 склад</option>
                    <option value="2 склад">2 склад</option>
                </select>
            </div>
            <div class="form-group">
                <label for="items">Состав заявки</label>
                <div id="itemsContainer">
                    <div class="form-row">
                        <div class="col">
                            <input type="text" class="form-control" name="items[0][brand]" placeholder="Бренд" required>
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
                    </div>
                </div>
                <button type="button" class="btn btn-primary mt-2" onclick="addItem()">Добавить</button>
            </div>
            <div class="form-group">
                <label for="file">Файлы</label>
                <input type="file" class="form-control-file" id="file" name="file[]" multiple>
            </div>
            <div class="form-group">
                <label for="comment">Комментарий</label>
                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
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
                        <input type="text" class="form-control" name="items[${itemCount}][brand]" placeholder="Бренд" required>
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
                </div>
            `;
            $('#itemsContainer').append(itemHtml);
        }
    </script>
</body>
</html>