<?php

require_once "../botfather/Botfather.php";

// add your bot token here
$botToken = '';

$telegramBot = new TelegramBot($botToken);

$chatIDs = $telegramBot->getChatId();

$result = array();
if ($chatIDs && is_array($chatIDs)) {
    foreach($chatIDs as $chatId) {
        $telegramBot->setChatId($chatId);
        $telegramBot->setMessage('Hello World!');
        $result = $telegramBot->sendMessage();
    }
} else {
    // add your chat id here, get chat id from telegramGetUpdateUrl
    $chatId = '12345677';
    $telegramBot->setChatId($chatId);
    $telegramBot->setMessage('Hello World!');
    $result = $telegramBot->sendMessage();
}


var_dump(json_decode($result, true));