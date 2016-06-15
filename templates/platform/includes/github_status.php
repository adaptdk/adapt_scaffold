<?php

$result = file_get_contents('https://status.github.com/api/last-message.json');
$response = json_decode($result, true);

if (!empty($response['status']) && $response['status'] == 'major') {
  echo sprintf('Github status message: %s%s', $response['body'], PHP_EOL);
  exit(1);
}
else {
  exit(0);
}

