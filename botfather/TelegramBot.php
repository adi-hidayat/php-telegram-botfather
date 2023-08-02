<?php

/**
 *
 */
class TelegramBot
{
    /**
     * @var
     */
    private $botToken;
    /**
     * @var string
     */
    private $telegramURL;
    /**
     * @var string
     */
    private $telegramGetUpdateUrl;
    /**
     * @var
     */
    private $chatId;
    /**
     * @var
     */
    private  $message;

    /**
     * @param $botToken
     */
    public function __construct($botToken)
    {
        $this->botToken = $botToken;
        $this->telegramURL = 'https://api.telegram.org/bot' . $this->botToken . '/sendMessage';
        $this->telegramGetUpdateUrl = "https://api.telegram.org/bot{$this->botToken}/getUpdates";
    }

    /**
     * @param int $chatId
     * @return void
     */
    public function setChatId(int $chatId)
    {
        $this->chatId = $chatId;

        if ($chatId == 0) {
            $this->chatId = $this->getChatId();
        }
    }

    /**
     * get chat id
     * @return array|int
     */
    public function getChatId()
    {
        if (empty($this->chatId)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->telegramGetUpdateUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            if ($response === false) {
                echo 'cURL error: ' . curl_error($ch);
            }

            curl_close($ch);

            $result = json_decode($response, true);

            if (isset($result['result'])) {
                $ids = [];
                $currentIds = null;
                foreach($result['result'] as $result) {

                    if ($currentIds != $result['message']['chat']['id']) {
                        $ids[] = $result['message']['chat']['id'];
                    }

                    $currentIds = $result['message']['chat']['id'];

                }

                return $ids;
            }

        }

        return $this->chatId;
    }

    /**
     * @param string $message
     * @return void
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return bool|string
     */
    public function sendMessage()
    {
        if (empty($this->chatId)) {
            $this->setChatId(0);
        }

        $data = array(
            'chat_id' => $this->chatId,
            'text' => $this->message
        );

        $ch = curl_init($this->telegramURL);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        curl_close($ch);

        return $result;
    }
}


