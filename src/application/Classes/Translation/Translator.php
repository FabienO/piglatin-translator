<?php
/**
 * The main translation class file which controls and manages all your language conversion needs.
 *
 * Created by PhpStorm.
 * User: fab
 * Date: 19/11/13
 * Time: 16:14
 */

namespace Classes\Translation;


class Translator
{
    private $inputLanguage;
    private $outputLanguage;

    public function __construct($text = null)
    {
        if($text !== null)
        {
            $this->text = $text;
        }
    }

    private function checkTextSet()
    {
        if(isset($this->text) && $this->text !== null)
        {
            return true;
        }
        else
        {
            throw new \Exception('No text set to convert. Please utilise the setText() method.');
        }
    }

    /**
     * For easy text changing
     *
     * @param null $text
     */
    public function setText($text = null)
    {
        $this->text = $text;
    }

    /**
     * Potentially expand in to language detection but for now simply state your language
     *
     * @param null $inputLanguage
     */
    public function setInputLanguage($inputLanguage = null)
    {
        $this->inputLanguage = $inputLanguage;
    }

    /**
     * Language we convert to
     *
     * @param null $outputLanguage
     */
    public function setOutputLanguage($outputLanguage = null)
    {
        $this->outputLanguage = $outputLanguage;
    }

    /**
     * The actual conversion method which uses the correct language conversion class
     *
     * @return mixed
     * @throws \Exception
     */
    public function convert()
    {
        $this->checkTextSet();

        // Check language available
        if($this->languageConversionAvailable($this->inputLanguage, $this->outputLanguage))
        {
            $languageFile = '\Classes\Translation\\'. $this->inputLanguage .'\\'. $this->outputLanguage;
            $conversion = new $languageFile($this->text);
            return $conversion->convert();
        }
        else
        {
            throw new \Exception('Language you are trying to convert to is not available');
        }
    }

    /**
     * to do: Convert to DB stored list
     *
     * @param null $inputLanguage
     * @param null $outputLanguage
     * @return bool
     * @throws \Exception
     */
    protected function languageConversionAvailable($inputLanguage = null, $outputLanguage = null)
    {
        $availableLanguages[$inputLanguage] = array('PigLatin');
        $inputLanguage = $inputLanguage !== null ? $inputLanguage : $this->inputLanguage;
        $outputLanguage = $outputLanguage !== null ? $outputLanguage : $this->outputLanguage;

        if($outputLanguage != '' && $inputLanguage != '')
        {
            if(in_array($outputLanguage, $availableLanguages[$inputLanguage]))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            throw new \Exception('Either input or output language has not been specified or it is unavailable');
        }
    }

    public function quickTranslate($inputLanguage = null, $outputLanguage = null, $text = null)
    {
        if($inputLanguage === null || $outputLanguage === null || $text === null) {
            throw new \Exception('You are missing a parameter. Usage: quickConvert(language_from, language_to, text)');
        }

        if($this->languageConversionAvailable($inputLanguage, $outputLanguage))
        {
            $languageFile = '\Classes\Translation\\'. $inputLanguage .'\\'. $outputLanguage;
            $quickConversion = new $languageFile($text);
            return $quickConversion->convert();
        }
    }
} 