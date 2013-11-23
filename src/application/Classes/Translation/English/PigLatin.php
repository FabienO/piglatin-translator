<?php
/**
 * Created by PhpStorm.
 * User: fab
 * Date: 19/11/13
 * Time: 16:09
 *
 * English to PigLatin translation
 */

namespace src\application\Classes\Translation\English;

class PigLatin implements \src\application\Classes\Translation\Language
{
    /**
     * The string to be converted
     *
     * @param $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * The required function for translation
     *
     * @return string
     */
    public function convert()
    {
        return $this->parseText();
    }

    /**
     * Split sentences to words
     *
     * @param null $sentence
     * @return array
     */
    protected function splitString($sentence = null)
    {
        return array_filter(explode(' ', $sentence));
    }

    /**
     * Split text to lines
     *
     * @return array
     */
    protected function splitToLines() {
        return preg_split("[\r\n]", $this->text);
    }

    /**
     * Split word by grouped vowels and consonants
     *
     * @param null $word
     * @return array|bool
     */
    protected function splitConstVowel($word = null)
    {
        $vowels = array('a', 'e', 'i', 'o', 'u');
        preg_match_all('/([aeiou]+)|([bcdfghjklmnpqrstvwxyz.]+)/i', $word, $matches);

        if(isset($matches[0][0][0]) && in_array(strtolower($matches[0][0][0]), $vowels))
        {
            return array($word);
        }

        if(!empty($matches[0]))
        {
            return $matches[0];
        }

        return array($word);
    }

    /**
     * Parse the text through all the required functions
     *
     * @return string
     */
    protected function parseText()
    {
        $string = '';
        $lines = $this->splitToLines();

        foreach($lines as $l)
        {
            if($l != '')
            {
                $splitString = $this->splitString($l);

                foreach($splitString as $word)
                {
                    $splitWord = $this->splitConstVowel($word);
                    if(is_array($splitWord))
                    {
                        $string .=  $this->parseWord($splitWord) . ' ';
                    }
                }
            }

            // new line
            $string .= "<br />";
        }

        return $string;
    }

    /**
     * Parse the word through the required functions
     *
     * @param array $splitWord
     * @return string
     */
    protected function parseWord(array $splitWord = array())
    {
        if(count($splitWord) > 1)
        {
            return $this->handleMultipleCharacterSplits($splitWord);
        }
        else
        {
            return $this->handleSingleCharacterSplits($splitWord);
        }
    }

    /**
     * PigLatin's the words once split by vowels/consonants which have a
     * count greater than 1
     *
     * @param array $array
     * @return string
     */
    protected function handleMultipleCharacterSplits(array $array = array())
    {
        $word = '';
        $end = 'ay';

        foreach($array as $k => $characters)
        {
            if(in_array($k, array(0,1)))
            {
                if($k == 0) {
                    continue;
                }

                // Damn Q's are fiddly.
                if(in_array($array[0][0], array('q')))
                {
                    if(in_array($array[1][0], array('u')))
                    {
                        $characters = str_replace($array[1][0], '', $array[1]);
                        $end = $array[1][0] . 'ay';
                    }
                }
            }

            if(strpos($characters, '.') !== false)
            {
                $characters = str_replace('.', '', $characters);
                $end .= '.';
            }

            $word .= $characters;
        }

        // Append the first character set to the end of the word, then the ending phonetic
        return $word .'-'. strtolower($array['0']) . $end;
    }

    /**
     * PigLatin's the words once split by vowels/consonants which have a
     * count equal to 1
     *
     * @param array $array
     * @return string
     */
    protected function handleSingleCharacterSplits(array $array = array())
    {
        $word = '';
        $end = 'ay';
        $singleEnds = array('yay', 'way', 'hay');

        $end = $singleEnds[array_rand($singleEnds)];

        if(strpos($array['0'], '.') !== false)
        {
            $array[0] = str_replace('.', '', $array['0']);
            return $array['0'] .'\''. $end;
        }
        else
        {
            return $array['0'] .'\''. $end;
        }
    }
} 