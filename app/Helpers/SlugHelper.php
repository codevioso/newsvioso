<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SlugHelper
{
    /**
     * Generate a multilingual slug from text
     * Supports Arabic, Bangla, Hindi, and other UTF-8 characters
     *
     * @param string $text
     * @param string $separator
     * @return string
     */
    public static function generate(string $text, string $separator = '-'): string
    {
        if (empty($text)) {
            return '';
        }

        // Normalize the text
        $text = self::normalizeText($text);
        
        // Convert to lowercase
        $text = mb_strtolower($text, 'UTF-8');
        
        // Replace spaces and unwanted characters with separator
        $text = self::replaceSpacesAndSymbols($text, $separator);
        
        // Clean up multiple separators
        $text = self::cleanMultipleSeparators($text, $separator);
        
        // Trim separators from start and end
        $text = trim($text, $separator);
        
        return $text;
    }

    /**
     * Normalize text for better slug generation
     *
     * @param string $text
     * @return string
     */
    private static function normalizeText(string $text): string
    {
        // Normalize Unicode characters
        $text = \Normalizer::normalize($text, \Normalizer::FORM_NFC);
        
        // Remove HTML tags
        $text = strip_tags($text);
        
        // Decode HTML entities
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return $text;
    }

    /**
     * Replace spaces and unwanted symbols with separator
     *
     * @param string $text
     * @param string $separator
     * @return string
     */
    private static function replaceSpacesAndSymbols(string $text, string $separator): string
    {
        // Define characters to replace with separator
        $spaceChars = [
            ' ',           // Regular space
            "\t",          // Tab
            "\n",          // Newline
            "\r",          // Carriage return
            "\0",          // Null character
            "\x0B",        // Vertical tab
            "\u00A0",      // Non-breaking space
            "\u2000",      // En quad
            "\u2001",      // Em quad
            "\u2002",      // En space
            "\u2003",      // Em space
            "\u2004",      // Three-per-em space
            "\u2005",      // Four-per-em space
            "\u2006",      // Six-per-em space
            "\u2007",      // Figure space
            "\u2008",      // Punctuation space
            "\u2009",      // Thin space
            "\u200A",      // Hair space
            "\u200B",      // Zero width space
            "\u200C",      // Zero width non-joiner
            "\u200D",      // Zero width joiner
            "\u2028",      // Line separator
            "\u2029",      // Paragraph separator
            "\u202F",      // Narrow no-break space
            "\u205F",      // Medium mathematical space
            "\u3000",      // Ideographic space
        ];

        // Replace space characters with separator
        foreach ($spaceChars as $spaceChar) {
            $text = str_replace($spaceChar, $separator, $text);
        }

        // Define symbols to remove or replace
        $symbolsToRemove = [
            '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=',
            '[', ']', '{', '}', '|', '\\', ':', ';', '"', "'", '<', '>',
            ',', '.', '?', '/', '~', '`', '¡', '¿', '«', '»', '‹', '›',
            '‚', '„', '…', '‰', '‱', '′', '″', '‴', '‵', '‶', '‷', '‸',
            '‹', '›', '‼', '‽', '‾', '‿', '⁀', '⁁', '⁂', '⁃', '⁅', '⁆',
            '⁇', '⁈', '⁉', '⁊', '⁋', '⁌', '⁍', '⁎', '⁏', '⁐', '⁑', '⁒',
            '⁓', '⁔', '⁕', '⁖', '⁗', '⁘', '⁙', '⁚', '⁛', '⁜', '⁝', '⁞'
        ];

        // Remove unwanted symbols
        foreach ($symbolsToRemove as $symbol) {
            $text = str_replace($symbol, '', $text);
        }

        return $text;
    }

    /**
     * Clean up multiple consecutive separators
     *
     * @param string $text
     * @param string $separator
     * @return string
     */
    private static function cleanMultipleSeparators(string $text, string $separator): string
    {
        $pattern = '/' . preg_quote($separator, '/') . '+/';
        return preg_replace($pattern, $separator, $text);
    }

    /**
     * Generate a unique slug for a model
     *
     * @param string $text
     * @param string $modelClass
     * @param string $column
     * @param int|null $excludeId
     * @param string $separator
     * @return string
     */
    public static function generateUnique(string $text, string $modelClass, string $column = 'slug', ?int $excludeId = null, string $separator = '-'): string
    {
        $baseSlug = self::generate($text, $separator);
        
        if (empty($baseSlug)) {
            $baseSlug = 'untitled';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (self::slugExists($modelClass, $column, $slug, $excludeId)) {
            $slug = $baseSlug . $separator . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if a slug exists in the database
     *
     * @param string $modelClass
     * @param string $column
     * @param string $slug
     * @param int|null $excludeId
     * @return bool
     */
    private static function slugExists(string $modelClass, string $column, string $slug, ?int $excludeId = null): bool
    {
        $query = $modelClass::where($column, $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Generate slug for Arabic text
     *
     * @param string $text
     * @return string
     */
    public static function generateArabic(string $text): string
    {
        return self::generate($text);
    }

    /**
     * Generate slug for Bangla text
     *
     * @param string $text
     * @return string
     */
    public static function generateBangla(string $text): string
    {
        return self::generate($text);
    }

    /**
     * Generate slug for Hindi text
     *
     * @param string $text
     * @return string
     */
    public static function generateHindi(string $text): string
    {
        return self::generate($text);
    }

    /**
     * Check if text contains non-Latin characters
     *
     * @param string $text
     * @return bool
     */
    public static function hasNonLatinCharacters(string $text): bool
    {
        return preg_match('/[^\x00-\x7F]/', $text);
    }

    /**
     * Get language detection for text
     *
     * @param string $text
     * @return string
     */
    public static function detectLanguage(string $text): string
    {
        // Simple language detection based on character ranges
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $text)) {
            return 'arabic';
        }
        
        if (preg_match('/[\x{0980}-\x{09FF}]/u', $text)) {
            return 'bangla';
        }
        
        if (preg_match('/[\x{0900}-\x{097F}]/u', $text)) {
            return 'hindi';
        }
        
        if (preg_match('/[\x{4E00}-\x{9FFF}]/u', $text)) {
            return 'chinese';
        }
        
        if (preg_match('/[\x{3040}-\x{309F}]/u', $text)) {
            return 'japanese';
        }
        
        if (preg_match('/[\x{AC00}-\x{D7AF}]/u', $text)) {
            return 'korean';
        }
        
        if (preg_match('/[\x{0400}-\x{04FF}]/u', $text)) {
            return 'cyrillic';
        }
        
        return 'latin';
    }
}
