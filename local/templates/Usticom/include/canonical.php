<?php
// Получаем текущий URL без домена
$requestUri = $_SERVER['REQUEST_URI'] ?? '';

// Разбиваем на путь и query-строку
$parsedUrl = parse_url($requestUri);
$path = $parsedUrl['path'] ?? '';
$query = $parsedUrl['query'] ?? '';

// Парсим параметры запроса
parse_str($query, $queryParams);

// Оставляем только PAGEN_* параметры
$allowedParams = [];
foreach ($queryParams as $key => $value) {
  if (preg_match('/^PAGEN_\d+$/', $key)) {
    $allowedParams[$key] = $value;
  }
}

// Формируем канонический URL
$canonicalPath = rtrim($path, '/') . '/';
if (!empty($allowedParams)) {
  $canonicalQuery = http_build_query($allowedParams, '', '&', PHP_QUERY_RFC3986);
  $canonicalUrl = $canonicalPath . '?' . $canonicalQuery;
} else {
  $canonicalUrl = $canonicalPath;
}

// Добавляем протокол и домен
$host = $_SERVER['HTTP_HOST'] ?? '';
$fullCanonical = 'https://' . $host . $canonicalUrl;

// Выводим canonical
echo '<link rel="canonical" href="' . htmlspecialchars($fullCanonical, ENT_QUOTES, 'UTF-8') . '" />' . "\n";
