<?php 

require "./db/db.php";

if(isset($_POST["url"]) && isset($_POST["name_product"]) && isset($_POST["name_description"]) && isset($_POST["categories_product"]) && isset($_POST["price"])) {
    $categories_product = $_POST["categories_product"];
    $url = $_POST["url"];
    $name_product = $_POST["name_product"];
    $name_description = $_POST["name_description"];
    $price = $_POST["price"];
    $query = $pdo->prepare("INSERT INTO `product`(`image`, `name`, `descript`, `price`, `key`) VALUES (?,?,?,?,?)");
    $query->execute([$url, $name_product, $name_description, $price, $categories_product]);
    echo "<script>alert('Добавленно')</script>";
}

if(isset($_POST["url"]) && isset($_POST["name_product"]) && isset($_POST["name_description"]) && isset($_POST["id"]) && isset($_POST["price"])) {
    $id = $_POST["id"];
    $url = $_POST["url"];
    $name_product = $_POST["name_product"];
    $name_description = $_POST["name_description"];
    $price = $_POST["price"];

    $query = $pdo->prepare("UPDATE `product` SET `image`=?,`name`=?,`descript`=?,`price`=? WHERE `id` = ?");
    $query->execute([$url, $name_product, $name_description, $price, $id]);

    echo "<script>alert('Успешно')</script>";
}


if(isset($_POST["delete"])) {
    $delete = $_POST["delete"];
    $query = $pdo->prepare("DELETE FROM `product` WHERE `id` = ?");
    $query->execute([$delete]);
    echo "<script>alert('Удаленно')</script>";
}



function allQuery() {
    global $pdo;

    $query = $pdo->prepare("SELECT `id`, `text`, `callback_data`, `categories_key`, `product_key` FROM `categories_product` WHERE 1");
    $query->execute();
    $response = $query->fetchAll();

    return $response;
}

function allProduct() {
    global $pdo;

    $query = $pdo->prepare("SELECT `id`, `image`, `name`, `descript`, `price`, `key` FROM `product` WHERE 1");
    $query->execute();
    $response = $query->fetchAll();

    return $response;
}

$categories_product = allQuery();
$product = allProduct();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>categories_product</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a class="btn btn-primary" href="index.php">Назад</a>

    <form action="" method="POST">
        <select name="categories_product">
            <?php
            for ($i=0; $i < count($categories_product); $i++) { 
                
            ?>
            <option value="<?= $categories_product[$i]['product_key'] ?>"><?= $categories_product[$i]['text'] ?></option>
            <?php } ?>
        </select>
        <input type="text" name="url" placeholder="url Картинка">
        <input type="text" name="name_product" placeholder="Название товара">
        <input type="text" name="name_description" placeholder="Описание товара">
        <input type="text" name="price" placeholder="Цена">
        <button class="btn btn-success">Добавить</button>
    </form>         
    <table class="table">
        <thead>
            <tr>
                <th>№</th>
                <th>Картинка</th>
                <th>имя товара</th>
                <th>Описание</th>
                <th>Цена</th>
                <th>Ключ</th>
                <th>Кнопки</th>
            </tr>
        </thead>
        <tbody>
           <?php
           
           for( $i = 0; $i < count($product); $i++ ) {
            
           ?>
           <tr>
            <td><?= $product[$i]["id"]; ?></td>
            <td><?= $product[$i]["image"]; ?></td>
            <td><?= $product[$i]["name"]; ?></td>
            <td><?= $product[$i]["descript"]; ?></td>
            <td><?= $product[$i]["price"]; ?></td>
            <td><?= $product[$i]["key"]; ?></td>
            <td style="display: flex; align-items: center;">
            <div id="openModal_<?= $product[$i]["id"]; ?>" class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Название</h3>
                            <a href="#close" title="Close" class="close">×</a>
                        </div>
                            <div class="modal-body">    
                            <form action="" method="POST">
                                <label for="">Картинка</label>
                                <input type="text" name="url" value="<?= $product[$i]["image"]; ?>" placeholder="url Картинка">
                                <label for="">Название товара</label>
                                <input type="text" name="name_product" value="<?= $product[$i]["name"]; ?>" placeholder="Название товара">
                                <label for="">Описание</label>
                                <input type="text" name="name_description" value="<?= $product[$i]["descript"]; ?>" placeholder="Описание товара">
                                <label for="">Цена</label>
                                <input type="text" name="price" placeholder="Цена" value="<?= $product[$i]["price"]; ?>">
                                <label for="">Ид товара</label>
                                <input type="text" name="id" placeholder="id" value="<?= $product[$i]["id"]; ?>">
                                <button class="btn btn-success">Изменить</button>
                            </form>     
                            </div>
                        </div>
                    </div>
                </div>
                <a href="#openModal_<?= $product[$i]["id"]; ?>">Изменить</a>
                <form action="" method="POST">
                    <input 
                        name="delete" 
                        style="position: absolute; top: -1000px;" 
                        type="text" 
                        value="<?= $product[$i]["id"]; ?>">
                    <button class="btn btn-danger">Удалить</button>
                </form>
            </td>
           </tr>
           <?php } ?>
        </tbody>
    </table>
    <script>
        let password = localStorage.getItem('password')

        if(password == 'qDFr5c_W8m') {} else {
            location.href = "/"
        }
    </script>
    <style>
        .modal {
    position: fixed; /* фиксированное положение */
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0,0,0,0.5); /* цвет фона */
    z-index: 1050;
    opacity: 0; /* по умолчанию модальное окно прозрачно */
    -webkit-transition: opacity 200ms ease-in; 
    -moz-transition: opacity 200ms ease-in;
    transition: opacity 200ms ease-in; /* анимация перехода */
    pointer-events: none; /* элемент невидим для событий мыши */
    margin: 0;
    padding: 0;
}
/* при отображении модального окно */
.modal:target {
    opacity: 1; /* делаем окно видимым */
	  pointer-events: auto; /* элемент видим для событий мыши */
    overflow-y: auto; /* добавляем прокрутку по y, когда элемент не помещается на страницу */
}
/* ширина модального окна и его отступы от экрана */
.modal-dialog {
    position: relative;
    width: auto;
    margin: 10px;
}
@media (min-width: 576px) {
  .modal-dialog {
      max-width: 500px;
      margin: 30px auto; /* для отображения модального окна по центру */
  }
}
/* свойства для блока, содержащего контент модального окна */ 
.modal-content {
    position: relative;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    background-color: #fff;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.2);
    border-radius: .3rem;
    outline: 0;
}
@media (min-width: 768px) {
  .modal-content {
      -webkit-box-shadow: 0 5px 15px rgba(0,0,0,.5);
      box-shadow: 0 5px 15px rgba(0,0,0,.5);
  }
}
/* свойства для заголовка модального окна */
.modal-header {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
    padding: 15px;
    border-bottom: 1px solid #eceeef;
}
.modal-title {
    margin-top: 0;
    margin-bottom: 0;
    line-height: 1.5;
    font-size: 1.25rem;
    font-weight: 500;
}
/* свойства для кнопки "Закрыть" */
.close {
    float: right;
    font-family: sans-serif;
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
    text-decoration: none;
}
/* свойства для кнопки "Закрыть" при нахождении её в фокусе или наведении */
.close:focus, .close:hover {
    color: #000;
    text-decoration: none;
    cursor: pointer;
    opacity: .75;
}
/* свойства для блока, содержащего основное содержимое окна */
.modal-body {
  position: relative;
    -webkit-box-flex: 1;
    -webkit-flex: 1 1 auto;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 15px;
    overflow: auto;
}
    </style>
</body>
</html>