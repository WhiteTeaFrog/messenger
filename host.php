<?php
// Создаем переменные для подключения к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "messenger";

// Подключаемся к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем, что подключение успешно
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Получаем действие из запроса
$action = $_GET["action"];

// Если действие - получить сообщения
if ($action == "get") {
  // Создаем массив для хранения сообщений
  $messages = array();

  // Выполняем запрос к базе данных, чтобы получить все сообщения
  $sql = "SELECT * FROM messages";
  $result = $conn->query($sql);

  // Если запрос успешен и есть результаты
  if ($result && $result->num_rows > 0) {
    // Проходим по всем результатам и добавляем их в массив сообщений
    while($row = $result->fetch_assoc()) {
      $messages[] = array(
        "id" => $row["id"],
        "text" => $row["text"],
        "type" => $row["type"]
      );
    }
  }

  // Выводим массив сообщений в формате JSON
  echo json_encode($messages);
}

// Если действие - отправить сообщение
if ($action == "send") {
  // Получаем текст и тип сообщения из запроса
  $text = $_GET["text"];
  $type = $_GET["type"];

  // Проверяем, что текст и тип не пустые
  if ($text && $type) {
    // Выполняем запрос к базе данных, чтобы добавить новое сообщение
    $sql = "INSERT INTO messages (text, type) VALUES ('$text', '$type')";
    $result = $conn->query($sql);

    // Если запрос успешен
    if ($result) {
      // Выводим статус OK
      echo "OK";
    } else {
      // Выводим статус ERROR и ошибку
      echo "ERROR: " . $conn->error;
    }
  }
}

// Закрываем подключение к базе данных
$conn->close();
?>
