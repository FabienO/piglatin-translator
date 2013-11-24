<?php
/**
 * The main translation class file which controls and manages all your language conversion needs.
 *
 * Created by PhpStorm.
 * User: fab
 * Date: 19/11/13
 * Time: 16:14
 */

namespace src\application\Classes\Translation;

class Translator
{
    private $inputLanguage;
    private $outputLanguage;

    /**
     * Set intent of language conversion
     *
     * @param string $inputLanguage
     * @param string $outputLanguage
     */
    public function __construct($inputLanguage = '', $outputLanguage = '')
    {
        if(isset($inputLanguage) && $inputLanguage != '')
        {
            $this->inputLanguage = $inputLanguage;
        }

        if(isset($outputLanguage) && $outputLanguage != '')
        {
            $this->outputLanguage = $outputLanguage;
        }
    }

    /**
     * Set the input language
     * Potentially expand in to language detection but for now simply state your language
     *
     * @param string $inputLanguage
     * @throws \Exception
     */
    public function setInputLanguage($inputLanguage = '')
    {
        if(isset($inputLanguage) && $inputLanguage != '')
        {
            $this->inputLanguage = $inputLanguage;
        }
        else
        {
            throw new \Exception('No input language provided');
        }
    }

    /**
     * Language we convert to
     *
     * @param string $outputLanguage
     * @throws \Exception
     */
    public function setOutputLanguage($outputLanguage = '')
    {
        if(isset($outputLanguage) && $outputLanguage != '')
        {
            $this->outputLanguage = $outputLanguage;
        }
        else
        {
            throw new \Exception('No input language provided');
        }
    }

    /**
     * The actual conversion method which uses the correct language conversion class
     *
     * @param string $text
     * @return mixed
     * @throws \Exception
     */
    public function convert($text = '')
    {
        $errors = array();
        if(!isset($text) || $text == '')
        {
            $errors[] = 'No text input to translate.';
        }

        if(!isset($this->inputLanguage) || $this->inputLanguage == '')
        {
            $errors[] = 'No input language specified.';
        }

        if(!isset($this->inputLanguage) || $this->inputLanguage == '')
        {
            $errors[] = 'No output language specified.';
        }

        if(!$this->languageConversionAvailable($this->inputLanguage, $this->outputLanguage))
        {
            $errors[] = 'Language conversion not available for selected language.';
        }

        // Check language available
        if(empty($errors))
        {
            $languageClassPath = $this->getClassName($this->inputLanguage, $this->outputLanguage);
            return (new $languageClassPath($text))->convert();
        }
        else
        {
            throw new \Exception(pre_r($errors));
        }
    }

    /**
     * to do: Convert to DB stored list
     *
     * @param string $inputLanguage
     * @param string $outputLanguage
     * @return bool
     * @throws \Exception
     */
    protected function languageConversionAvailable($inputLanguage = '', $outputLanguage = '')
    {
        $availableLanguages[$inputLanguage] = array('PigLatin');
        $inputLanguage = isset($inputLanguage) && $inputLanguage == '' ? $inputLanguage : $this->inputLanguage;
        $outputLanguage = isset($outputLanguage) && $outputLanguage == '' ? $outputLanguage : $this->outputLanguage;

        if($outputLanguage != '' && $inputLanguage != '')
        {
            if(in_array($outputLanguage, $availableLanguages[$inputLanguage]))
            {
                return true;
            }

            return false;
        }
        else
        {
            throw new \Exception('Either input or output language has not been specified or it is unavailable');
        }
    }

    /**
     * A quick translation feature.
     *
     * @param string $inputLanguage
     * @param string $outputLanguage
     * @param string $text
     * @return mixed
     * @throws \Exception
     */
    public function quickTranslate($inputLanguage = '', $outputLanguage = '', $text = '')
    {
        if(!isset($inputLanguage) || $inputLanguage == '' || !isset($outputLanguage) || $outputLanguage == '' || !isset($text) || $text == '') {
            throw new \Exception('You are missing a parameter. Usage: quickConvert(language_from, language_to, text)');
        }

        if($this->languageConversionAvailable($inputLanguage, $outputLanguage))
        {
            $languageClassPath = $this->getClassName($this->inputLanguage, $this->outputLanguage);
            return (new $languageClassPath($text))->convert();
        }
    }

    protected function getClassName($inputLanguage = '', $outputLanguage = '')
    {
        return '\\' . __NAMESPACE__ . '\\' . $inputLanguage . '\\' . $outputLanguage;
    }
} 