<?php

class MessageException extends Exception
{
    private $lang;
    private $translations = [];

    public function __construct($messageKey, $lang = null)
    {
        $this->lang = $lang ?: (isset($_SESSION['lang']) ? $_SESSION['lang'] : $this->getDefaultLang());

        $language = new Language($this->lang);
        $this->translations = $language->getTranslations();
        $message = $this->translations[$messageKey] ?? $messageKey;
        parent::__construct($message);
    }

    private function getDefaultLang()
    {
        $config = json_decode(file_get_contents(__DIR__ . '../../../data/config-global.json'), true);
        return isset($config['lang-default']) ? $config['lang-default'] : 'pt';
    }

    public function getLang()
    {
        return $this->lang;
    }
}

?>
