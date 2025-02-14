<?php

class Language
{
    private $translations = [];
    private $lang;

    public function __construct($lang = null)
    {
        $lang = $lang ?: (isset($_SESSION['lang']) ? $_SESSION['lang'] : $this->getDefaultLang());
        $this->lang = $lang;
        $this->loadLanguageFile();
    }

    private function getDefaultLang()
    {
        $config = json_decode(file_get_contents(__DIR__ . '/../data/config-global.json'), true);
        return isset($config['lang-default']) ? $config['lang-default'] : 'pt';
    }

    private function loadLanguageFile()
    {
        $filePath = __DIR__ . '/../data/lang/lang_' . $this->lang . '.json';
        if (file_exists($filePath)) {
            $this->translations = json_decode(file_get_contents($filePath), true);
        } else {
            $this->translations = json_decode(file_get_contents(__DIR__ . '/../data/lang/lang_pt.json'), true);
        }
    }

    public function get($key)
    {
        return isset($this->translations[$key]) ? $this->translations[$key] : $key;
    }

    public function setLanguage($lang)
    {
        $this->lang = $lang;
        $_SESSION['lang'] = $lang;
        $this->loadLanguageFile();
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function getTranslations()
    {
        return $this->translations;
    }
}

?>
