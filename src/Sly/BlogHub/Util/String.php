<?php

namespace Sly\BlogHub\Util;

/**
 * String.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class String
{
    /**
     * Slugify.
     * 
     * @param string $text Text
     * 
     * @return string
     */
    public static function slugify($text)
    {
        $text = preg_replace('~[^\pLd]+~u', '-', $text);
        $text = trim($text, '-');

        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        $text = strtolower($text);
        $text = preg_replace('~[^-w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
