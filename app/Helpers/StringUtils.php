<?php
namespace App\Helpers;

class StringUtils
{
    /**
     * try to cast value to integer
     */
    public static function getIntegerValue($val)
    {
        if ($val === null) {
            return null;
        }
        if (!is_numeric($val)) {
            return $val;
        }
        if (gettype($val) === 'string') {
            $val = (int) $val;
        }
        return $val;
    }

    /**
     * get a string between the given strings
     * @param {string} $val source string
     * @param {string} $startSearch starting string to search
     * @param {string} $endSearch end string to search
     * @param {number} $offset position from which to start the search
     * @param {boolean} $returnInfo if false, returns the string, if true returns an object with the string and found indexes
     * @return {string} null if searched strings not found, the string between or an object with info (if $returnInfo is true)
     *          returned array contains these keys: string -> the found string,
     *              startIndex -> index of $startSearch,
     *              foundStartIndex -> start index of the text between : $startIndex + strlen($startSearch)
     *              foundEndIndex -> end index of the text between or start index of $endSearch
     *              endIndex -> end index of the $endSearch : $foundEndIndex + strlen($endSearch)
     */
    public static function getStringBetween($val, $startSearch, $endSearch, $offset = 0, $returnInfo = false)
    {
        if (!$val) {
            return null;
        }
        $stringBetween = null;
        $startIndex = strpos($val, $startSearch, $offset);
        if ($startIndex !== false) {
            $nextOffset = $startIndex + strlen($startSearch);
            $endIndex = strpos($val, $endSearch, $nextOffset);
            if ($endIndex !== false) {
                $stringBetween = substr($val, $nextOffset, $endIndex - $nextOffset);
            }
        }
        if (!$stringBetween || !$returnInfo) {
            return null;
        } else {
            return (object) ['string' => $stringBetween,
                'startIndex' => $startIndex,
                'foundStartIndex' => $nextOffset,
                'foundEndIndex' => $endIndex,
                'endIndex' => $endIndex + strlen($endSearch),
            ];
        }
    }

    /**
     * strip tags from given text
     * @param {string} $text
     * @param {string} $tags tags to strip / allow
     * @param {boolean} $invert if specified, then allow all the other tags than the specified ones
     */
    public static function stripTags($text, $tags = '', $invert = false)
    {

        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique($tags[1]);

        if (is_array($tags) && count($tags) > 0) {
            if ($invert === false) {
                return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            } else {
                return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
            }
        } elseif ($invert === false) {
            return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }
        return $text;
    }

    /**
     * strip php tags from given text
     * @param {string} $text
     */
    public static function stripPhpTags($text)
    {
        $strippedText = preg_replace(array('@<\?php@i', '@<\?\s*=@i', '@\?>@i'), '', $text);
        return $strippedText;
    }
}
