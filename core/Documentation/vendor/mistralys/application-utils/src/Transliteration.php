<?php
/**
 * File containing the {@link Transliteration} class.
 * 
 * @package Application Utils
 * @subpackage Transliteration
 * @see Transliteration
 */

declare(strict_types=1);

namespace AppUtils;

/**
 * Transliteration class that can be used to convert a regular
 * string to an ascii-only text, while preserving as many characters
 * as possible. Characters are replaced by their ascii equivalents
 * with the closest visual representation.
 *
 * @package Application Utils
 * @subpackage Transliteration
 * @author Sebastian Mordziol <s.mordziol@gmail.com>
 */
class Transliteration implements Interface_Optionable
{
    use Traits_Optionable;

    public const OPTION_SPACE_CHARACTER = 'space';
    public const OPTION_LOWER_CASE = 'lowercase';

    public function getDefaultOptions() : array
    {
        return array(
            self::OPTION_SPACE_CHARACTER => '_',
            self::OPTION_LOWER_CASE => false
        );
    }

    public function __construct()
    {

    }

    /**
     * Sets the character to use as replacement for spaces.
     * Default is an underscore.
     *
     * @param string $char
     * @return Transliteration
     */
    public function setSpaceReplacement(string $char) : Transliteration
    {
        $this->setOption(self::OPTION_SPACE_CHARACTER, $char);

        return $this;
    }

    /**
     * The converted string will be all lowercase.
     *
     * @param bool $lowercase
     * @return Transliteration
     */
    public function setLowercase(bool $lowercase=true) : Transliteration
    {
        $this->setOption(self::OPTION_LOWER_CASE, $lowercase);

        return $this;
    }

    /**
     * Converts the specified string using the current settings.
     *
     * @param string $string
     * @return string
     */
    public function convert(string $string) : string
    {
        $space = $this->getStringOption(self::OPTION_SPACE_CHARACTER);
        
        $result = str_replace(array_keys(self::$charTable), array_values(self::$charTable), $string);
        $result = str_replace('_', $space, $result);

        $regex = '/\A[a-zA-Z0-9_%s]+\Z/';
        $additionalChar = '';
        if ($space !== '_') {
            $additionalChar = $space;
        }

        $regex = sprintf($regex, $additionalChar);

        $keep = array();
        $max = strlen($result);
        for ($i = 0; $i < $max; $i++) {
            if (preg_match($regex, $result[$i])) {
                $keep[] = $result[$i];
            }
        }

        $result = implode('', $keep);

        while (strpos($result, $space . $space) !== false) {
            $result = str_replace($space . $space, $space, $result);
        }

        $result = trim($result, $space);

        if ($this->getBoolOption(self::OPTION_LOWER_CASE)) {
            $result = mb_strtolower($result);
        }

        return $result;
    }

    /**
     * The full character table with all ascii equivalents of
     * strings.
     *
     * @var array
     */
    protected static $charTable = array(
        '°' => '',
        'º' => '',
        '§' => '',
        '¿' => '',
        'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Ä' => 'AE',
        'Å' => 'A',
        'Æ' => 'AE',
        'Ç' => 'C',
        'È' => 'E',
        'É' => 'E',
        'Ê' => 'E',
        'Ë' => 'E',
        'Ì' => 'I',
        'Í' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ð' => 'D',
        'Ñ' => 'N',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ö' => 'OE',
        '×' => 'x',
        'Ø' => 'O',
        'Ù' => 'U',
        'Ú' => 'U',
        'Û' => 'U',
        'Ü' => 'UE',
        'Ý' => 'Y',
        'Þ' => '',
        'ß' => 'SS',
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'ä' => 'ae',
        'å' => 'a',
        'æ' => 'ae',
        'ç' => 'c',
        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ð' => '',
        'ñ' => 'n',
        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ö' => 'oe',
        '÷' => '',
        'ø' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ü' => 'ue',
        'ý' => 'y',
        'þ' => '',
        'ÿ' => 'y',
        'Ā' => 'A',
        'ā' => 'a',
        'Ă' => 'A',
        'ă' => 'a',
        'Ą' => 'A',
        'ą' => 'a',
        'Ć' => 'C',
        'ć' => 'c',
        'Ĉ' => 'C',
        'ĉ' => 'c',
        'Ċ' => 'C',
        'ċ' => 'c',
        'Č' => 'C',
        'č' => 'c',
        'Ď' => 'D',
        'ď' => 'd',
        'Đ' => 'D',
        'đ' => 'd',
        'Ē' => 'E',
        'ē' => 'e',
        'Ĕ' => 'E',
        'ĕ' => 'e',
        'Ė' => 'E',
        'ė' => 'e',
        'Ę' => 'E',
        'ę' => 'e',
        'Ě' => 'E',
        'ě' => 'e',
        'Ĝ' => 'G',
        'ĝ' => 'g',
        'Ğ' => 'G',
        'ğ' => 'g',
        'Ġ' => 'G',
        'ġ' => 'g',
        'Ģ' => 'G',
        'ģ' => 'g',
        'Ĥ' => 'H',
        'ĥ' => 'h',
        'Ħ' => '',
        'ħ' => 'h',
        'Ĩ' => 'I',
        'ĩ' => 'i',
        'Ī' => 'I',
        'ī' => 'i',
        'Ĭ' => 'I',
        'ĭ' => 'i',
        'Į' => 'I',
        'į' => 'i',
        'İ' => 'I',
        'ı' => 'i',
        'Ĳ' => 'IJ',
        'ĳ' => 'ij',
        'Ĵ' => 'j',
        'ĵ' => 'j',
        'Ķ' => 'K',
        'ķ' => 'k',
        'ĸ' => 'k',
        'Ĺ' => 'L',
        'ĺ' => 'l',
        'Ļ' => 'L',
        'ļ' => 'l',
        'Ľ' => 'L',
        'ľ' => 'l',
        'Ŀ' => 'L',
        'ŀ' => 'l',
        'Ł' => 'L',
        'ł' => 'l',
        'Ń' => 'N',
        'ń' => 'n',
        'Ņ' => 'N',
        'ņ' => 'n',
        'Ň' => 'N',
        'ň' => 'n',
        'ŉ' => 'n',
        'Ŋ' => 'N',
        'ŋ' => 'n',
        'Ō' => 'O',
        'ō' => 'o',
        'Ŏ' => 'O',
        'ŏ' => 'o',
        'Ő' => 'O',
        'ő' => 'o',
        'Œ' => 'OE',
        'œ' => 'oe',
        'Ŕ' => 'R',
        'ŕ' => 'r',
        'Ŗ' => 'R',
        'ŗ' => 'r',
        'Ř' => 'R',
        'ř' => 'r',
        'Ś' => 'S',
        'ś' => 's',
        'Ŝ' => 'S',
        'ŝ' => 's',
        'Ş' => 'S',
        'ş' => 's',
        'Š' => 'S',
        'š' => 's',
        'Ţ' => 'T',
        'ţ' => 't',
        'Ť' => 'T',
        'ť' => 't',
        'Ŧ' => 't',
        'ŧ' => 't',
        'Ũ' => 'U',
        'ũ' => 'u',
        'Ū' => 'U',
        'ū' => 'u',
        'Ŭ' => 'U',
        'ŭ' => 'u',
        'Ů' => 'U',
        'ů' => 'u',
        'Ű' => 'U',
        'ű' => 'u',
        'Ų' => 'U',
        'ų' => 'u',
        'Ŵ' => 'W',
        'ŵ' => 'w',
        'Ŷ' => 'Y',
        'ŷ' => 'y',
        'Ÿ' => 'Y',
        'Ź' => 'Z',
        'ź' => 'z',
        'Ż' => 'Z',
        'ż' => 'z',
        'Ž' => 'Z',
        'ž' => 'z',
        'ſ' => '',
        'ƀ' => '',
        'Ɓ' => 'B',
        'Ƃ' => 'b',
        'ƃ' => 'b',
        'Ƅ' => 'b',
        'ƅ' => 'b',
        'Ɔ' => 'C',
        'Ƈ' => 'C',
        'ƈ' => 'c',
        'Ɖ' => 'D',
        'Ɗ' => 'D',
        'Ƌ' => 'd',
        'ƌ' => 'd',
        'ƍ' => '',
        'Ǝ' => 'E',
        'Ə' => 'e',
        'Ɛ' => 'e',
        'Ƒ' => 'F',
        'ƒ' => 'f',
        'Ɠ' => 'G',
        'Ɣ' => 'X',
        'ƕ' => 'h',
        'Ɩ' => 'l',
        'Ɨ' => 'I',
        'Ƙ' => 'K',
        'ƙ' => 'k',
        'ƚ' => 'l',
        'ƛ' => 'A',
        'Ɯ' => 'W',
        'Ɲ' => 'N',
        'ƞ' => 'n',
        'Ɵ' => 'o',
        'Ơ' => 'O',
        'ơ' => 'o',
        'Ƣ' => '',
        'ƣ' => '',
        'Ƥ' => 'P',
        'ƥ' => '',
        'Ʀ' => 'R',
        'Ƨ' => 'S',
        'ƨ' => 's',
        'Ʃ' => 'E',
        'ƪ' => 'l',
        'ƫ' => 't',
        'Ƭ' => 'T',
        'ƭ' => 't',
        'Ʈ' => 'T',
        'Ư' => 'U',
        'ư' => 'u',
        'Ʊ' => 'U',
        'Ʋ' => 'U',
        'Ƴ' => 'Y',
        'ƴ' => 'y',
        'Ƶ' => 'Z',
        'ƶ' => 'z',
        'Ʒ' => '3',
        'Ƹ' => '3',
        'ƹ' => '3',
        'ƺ' => '3',
        'ƻ' => '2',
        'Ƽ' => '5',
        'ƽ' => '5',
        'ƾ' => '',
        'ƿ' => 'p',
        'ǀ' => '',
        'ǁ' => '',
        'ǂ' => '',
        'ǃ' => '!',
        'Ǆ' => '',
        'ǅ' => '',
        'ǆ' => '',
        'Ǉ' => 'LJ',
        'ǈ' => 'Lj',
        'ǉ' => 'lj',
        'Ǌ' => 'NJ',
        'ǋ' => 'Nj',
        'ǌ' => 'nj',
        'Ǎ' => 'A',
        'ǎ' => 'a',
        'Ǐ' => 'I',
        'ǐ' => 'i',
        'Ǒ' => 'O',
        'ǒ' => 'o',
        'Ǔ' => 'U',
        'ǔ' => 'u',
        'Ǖ' => 'U',
        'ǖ' => 'u',
        'Ǘ' => 'U',
        'ǘ' => 'u',
        'Ǚ' => 'U',
        'ǚ' => 'u',
        'Ǜ' => 'U',
        'ǜ' => 'u',
        'ǝ' => 'e',
        'Ǟ' => 'A',
        'ǟ' => 'a',
        'Ǡ' => 'A',
        'ǡ' => 'a',
        'Ǣ' => 'AE',
        'ǣ' => 'ae',
        'Ǥ' => 'G',
        'ǥ' => 'g',
        'Ǧ' => 'G',
        'ǧ' => 'g',
        'Ǩ' => 'K',
        'ǩ' => 'k',
        'Ǫ' => 'Q',
        'ǫ' => 'q',
        'Ǭ' => 'Q',
        'ǭ' => 'q',
        'Ǯ' => '3',
        'ǯ' => '3',
        'ǰ' => 'J',
        'Ǳ' => 'DZ',
        'ǲ' => 'Dz',
        'ǳ' => 'dz',
        'Ǵ' => 'G',
        'ǵ' => 'g',
        'Ƕ' => 'H',
        'Ƿ' => 'P',
        'Ǹ' => 'N',
        'ǹ' => 'n',
        'Ǻ' => 'A',
        'ǻ' => 'a',
        'Ǽ' => 'AE',
        'ǽ' => 'ae',
        'Ǿ' => 'O',
        'ǿ' => 'o',
        'Ȁ' => 'A',
        'ȁ' => 'a',
        'Ȃ' => 'A',
        'ȃ' => 'a',
        'Ȅ' => 'E',
        'ȅ' => 'e',
        'Ȇ' => 'E',
        'ȇ' => 'e',
        'Ȉ' => 'I',
        'ȉ' => 'i',
        'Ȋ' => 'I',
        'ȋ' => 'i',
        'Ȍ' => 'O',
        'ȍ' => 'o',
        'Ȏ' => 'O',
        'ȏ' => 'o',
        'Ȑ' => 'R',
        'ȑ' => 'r',
        'Ȓ' => 'R',
        'ȓ' => 'r',
        'Ȕ' => 'U',
        'ȕ' => 'u',
        'Ȗ' => 'U',
        'ȗ' => 'u',
        'Ș' => 'S',
        'ș' => 's',
        'Ț' => 'T',
        'ț' => 't',
        'Ȝ' => '3',
        'ȝ' => '3',
        'Ȟ' => 'H',
        'ȟ' => 'h',
        'Ƞ' => 'N',
        'ȡ' => 'd',
        'Ȣ' => '',
        'ȣ' => '',
        'Ȥ' => 'Z',
        'ȥ' => 'z',
        'Ȧ' => 'A',
        'ȧ' => 'a',
        'Ȩ' => 'E',
        'ȩ' => 'e',
        'Ȫ' => 'O',
        'ȫ' => 'o',
        'Ȭ' => 'O',
        'ȭ' => 'o',
        'Ȯ' => 'O',
        'ȯ' => 'o',
        'Ȱ' => 'O',
        'ȱ' => 'o',
        'Ȳ' => 'Y',
        'ȳ' => 'y',
        'ɐ' => 'a',
        'ɑ' => 'a',
        'ɒ' => 'a',
        'ɓ' => 'b',
        'ɔ' => 'c',
        'ɕ' => 'c',
        'ɖ' => 'd',
        'ɗ' => 'd',
        'ɘ' => 'e',
        'ə' => 'e',
        'ɚ' => 'e',
        'ɛ' => 'e',
        'ɜ' => '3',
        'ɝ' => '3',
        'ɞ' => 'g',
        'ɟ' => 'j',
        'ɠ' => 'g',
        'ɡ' => 'g',
        'ɢ' => 'g',
        'ɣ' => 'y',
        'ɤ' => 'y',
        'ɥ' => 'h',
        'ɦ' => 'h',
        'ɧ' => 'h',
        'ɨ' => 'i',
        'ɩ' => 'l',
        'ɪ' => 'i',
        'ɫ' => 'l',
        'ɬ' => 'l',
        'ɭ' => 'l',
        'ɮ' => 'l3',
        'ɯ' => 'w',
        'ɰ' => 'w',
        'ɱ' => 'm',
        'ɲ' => 'n',
        'ɳ' => 'n',
        'ɴ' => 'n',
        'ɵ' => 'e',
        'ɶ' => 'oe',
        'ɷ' => '',
        'ɸ' => 'o',
        'ɹ' => 'r',
        'ɺ' => 'R',
        'ɻ' => 'r',
        'ɼ' => 'r',
        'ɽ' => 'r',
        'ɾ' => 'r',
        'ɿ' => 'r',
        'ʀ' => 'R',
        'ʁ' => 'R',
        'ʂ' => 's',
        'ʃ' => 'l',
        'ʄ' => 'l',
        'ʅ' => 'l',
        'ʆ' => 'l',
        'ʇ' => 't',
        'ʈ' => 't',
        'ʉ' => 'u',
        'ʊ' => 'u',
        'ʋ' => 'u',
        'ʌ' => 'A',
        'ʍ' => 'M',
        'ʎ' => 'Y',
        'ʏ' => 'y',
        'ʐ' => 'z',
        'ʑ' => 'z',
        'ʒ' => '3',
        'ʓ' => '3',
        'ʔ' => '',
        'ʕ' => '',
        'ʖ' => '',
        'ʗ' => 'C',
        'ʘ' => 'O',
        'ʙ' => 'B',
        'ʚ' => 'G',
        'ʛ' => 'G',
        'ʜ' => 'H',
        'ʝ' => 'J',
        'ʞ' => 'K',
        'ʟ' => 'L',
        'ʠ' => '',
        'ʡ' => '',
        'ʢ' => '',
        'ʣ' => 'du',
        'ʤ' => 'd3',
        'ʥ' => 'd3',
        'ʦ' => 'ts',
        'ʧ' => 'tl',
        'ʨ' => 'tl',
        'ʩ' => 'fn',
        'ʪ' => 'ls',
        'ʫ' => 'lz',
        'ʬ' => 'ww',
        'ʭ' => 'nn',
        'ʮ' => 'h',
        'ʯ' => 'h',
        'ʰ' => 'h',
        'ʱ' => 'h',
        'ʲ' => 'j',
        'ʳ' => 'r',
        'ʴ' => 'r',
        'ʵ' => 'r',
        'ʶ' => 'R',
        'ʷ' => 'w',
        'ʸ' => 'y',
        'ʹ' => '',
        'ʺ' => '',
        'ʻ' => '',
        'ʼ' => '',
        'ʽ' => '',
        'ʾ' => '',
        'ʿ' => '',
        'ˀ' => '',
        'ˁ' => '',
        '˂' => '',
        '˃' => '',
        '˄' => '',
        '˅' => '',
        'ˆ' => '',
        'ˇ' => '',
        'ˈ' => '',
        'ˉ' => '',
        'ˊ' => '',
        'ˋ' => '',
        'ˌ' => '',
        'ˍ' => '',
        'ˎ' => '',
        'ˏ' => '',
        'ː' => '',
        'ˑ' => '',
        '˒' => '',
        '˓' => '',
        '˔' => '',
        '˕' => '',
        '˖' => '',
        '˗' => '',
        '˘' => '',
        '˙' => '',
        '˚' => '',
        '˛' => '',
        '˜' => '',
        '˝' => '',
        '˞' => '',
        '˟' => '',
        'ˠ' => '',
        'ˡ' => '',
        'ˢ' => '',
        'ˣ' => '',
        'ˤ' => '',
        '˥' => '',
        '˦' => '',
        '˧' => '',
        '˨' => '',
        '˩' => '',
        '̦' => '',
        '̀' => '',
        '́' => '',
        '͂' => '',
        'ʹ' => '',
        '͵' => '',
        ';' => ' ',
        '΀' => '',
        '΄' => '',
        '΅' => '',
        'Ά' => 'A',
        '·' => '',
        'Έ' => 'E',
        'Ή' => 'H',
        'Ί' => 'I',
        'Ό' => 'O',
        '΍' => '',
        'Ύ' => 'Y',
        'Ώ' => 'O',
        'ΐ' => 'i',
        'Α' => 'A',
        'Β' => 'B',
        'Γ' => '',
        'Δ' => '',
        'Ε' => 'E',
        'Ζ' => 'Z',
        'Η' => 'H',
        'Θ' => 'O',
        'Ι' => 'I',
        'Κ' => 'K',
        'Λ' => '',
        'Μ' => 'M',
        'Ν' => 'N',
        'Ξ' => 'E',
        'Ο' => 'O',
        'Π' => 'N',
        'Ρ' => 'P',
        '΢' => '',
        'Σ' => 'E',
        'Τ' => 'T',
        'Υ' => 'Y',
        'Φ' => 'O',
        'Χ' => 'X',
        'Ψ' => 'W',
        'Ω' => 'O',
        'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'ά' => 'a',
        'έ' => 'e',
        'ή' => 'n',
        'ί' => 'i',
        'ΰ' => 'u',
        'α' => 'a',
        'β' => 'ss',
        'γ' => 'y',
        'δ' => '',
        'ε' => 'e',
        'ζ' => '',
        'η' => 'n',
        'θ' => 'o',
        'ι' => '',
        'κ' => 'k',
        'λ' => '',
        'μ' => 'm',
        'ν' => 'v',
        'ξ' => 'E',
        'ο' => 'o',
        'π' => 'pi',
        'ρ' => 'p',
        'ς' => 'c',
        'σ' => 'o',
        'τ' => 't',
        'υ' => 'u',
        'φ' => 'o',
        'χ' => 'X',
        'ψ' => 'W',
        'ω' => 'w',
        'ϊ' => 'i',
        'ϋ' => 'u',
        'ό' => 'o',
        'ύ' => 'u',
        'ώ' => 'w',
        'Ϗ' => '',
        'ϐ' => 'B',
        'ϑ' => '',
        'ϒ' => 'Y',
        'ϓ' => 'Y',
        'ϔ' => 'Y',
        'ϕ' => '',
        'ϖ' => 'w',
        'ϗ' => '',
        'Ϙ' => 'Q',
        'ϙ' => 'q',
        'Ϛ' => 'C',
        'ϛ' => 'c',
        'Ϝ' => 'F',
        'ϝ' => 'f',
        'Ϟ' => 'S',
        'ϟ' => 's',
        'Ϡ' => 'E',
        'ϡ' => 'E',
        'Ϣ' => 'W',
        'ϣ' => 'w',
        'Ϥ' => 'h',
        'ϥ' => 'h',
        'Ϧ' => 'h',
        'ϧ' => 's',
        'Ϩ' => 'S',
        'ϩ' => 's',
        'Ϫ' => '',
        'ϫ' => '',
        'Ϭ' => '',
        'ϭ' => '',
        'Ϯ' => 'T',
        'ϯ' => 'T',
        'ϰ' => 'n',
        'ϱ' => 'e',
        'ϲ' => 'c',
        'ϳ' => 'j',
        'ϴ' => 'o',
        'ϵ' => 'E',
        '϶' => 'E',
        'Ѐ' => 'E',
        'Ё' => 'E',
        'Ђ' => 'h',
        'Ѓ' => '',
        'Є' => 'E',
        'Ѕ' => 'S',
        'І' => 'I',
        'Ї' => 'I',
        'Ј' => 'J',
        'Љ' => 'lb',
        'Њ' => 'Hb',
        'Ћ' => 'h',
        'Ќ' => 'K',
        'Ѝ' => 'N',
        'Ў' => 'Y',
        'Џ' => 'U',
        'А' => 'A',
        'Б' => 'B',
        'В' => 'B',
        'Г' => '',
        'Д' => '',
        'Е' => 'E',
        'Ж' => 'X',
        'З' => '3',
        'И' => 'N',
        'Й' => 'N',
        'К' => 'K',
        'Л' => 'N',
        'М' => 'M',
        'Н' => 'H',
        'О' => 'O',
        'П' => 'N',
        'Р' => 'P',
        'С' => 'C',
        'Т' => 'T',
        'У' => 'Y',
        'Ф' => 'o',
        'Х' => 'X',
        'Ц' => 'U',
        'Ч' => 'u',
        'Ш' => 'W',
        'Щ' => 'W',
        'Ъ' => 'b',
        'Ы' => 'bl',
        'Ь' => 'b',
        'Э' => '3',
        'Ю' => 'lO',
        'Я' => 'R',
        'а' => 'a',
        'б' => '6',
        'в' => 'B',
        'г' => 'r',
        'д' => 'A',
        'е' => 'e',
        'ж' => 'X',
        'з' => '3',
        'и' => 'n',
        'й' => 'n',
        'к' => 'k',
        'л' => 'n',
        'м' => 'M',
        'н' => 'H',
        'о' => 'o',
        'п' => 'n',
        'р' => 'p',
        'с' => 'c',
        'т' => 'T',
        'у' => 'y',
        'ф' => 'o',
        'х' => 'x',
        'ц' => 'u',
        'ч' => 'u',
        'ш' => 'w',
        'щ' => 'w',
        'ъ' => 'b',
        'ы' => 'bl',
        'ь' => 'b',
        'э' => '3',
        'ю' => 'lo',
        'я' => 'R',
        'ѐ' => 'e',
        'ё' => 'e',
        'ђ' => 'h',
        'ѓ' => 'r',
        'є' => 'E',
        'ѕ' => 's',
        'і' => 'i',
        'ї' => 'i',
        'ј' => 'j',
        'љ' => 'lb',
        'њ' => 'hb',
        'ћ' => 'h',
        'ќ' => 'k',
        'ѝ' => 'n',
        'ў' => 'y',
        'џ' => 'u',
        'Ѡ' => 'W',
        'ѡ' => 'w',
        'Ѣ' => 'b',
        'ѣ' => 'tb',
        'Ѥ' => 'lE',
        'ѥ' => 'lE',
        'Ѧ' => 'A',
        'ѧ' => 'a',
        'Ѩ' => 'lA',
        'ѩ' => 'la',
        'Ѫ' => '',
        'ѫ' => '',
        'Ѭ' => '',
        'ѭ' => '',
        'Ѯ' => '',
        'ѯ' => '',
        'Ѱ' => '',
        'ѱ' => '',
        'Ѳ' => 'O',
        'ѳ' => 'e',
        'Ѵ' => 'V',
        'ѵ' => 'v',
        'Ѷ' => 'V',
        'ѷ' => 'v',
        'Ѹ' => 'Oy',
        'ѹ' => 'oy',
        'Ѻ' => 'o',
        'ѻ' => 'o',
        'Ѽ' => 'o',
        'ѽ' => 'w',
        'Ѿ' => 'W',
        'ѿ' => 'W',
        'Ҁ' => 'C',
        'ҁ' => 'c',
        '҂' => '',
        '҃' => '',
        '҄' => '',
        '҅' => '',
        '҆' => '',
        '҇' => '',
        '҈' => '',
        '҉' => '',
        'Ҋ' => 'N',
        'ҋ' => 'n',
        'Ҍ' => 'b',
        'ҍ' => 'b',
        'Ҏ' => 'P',
        'ҏ' => 'P',
        'Ґ' => 'r',
        'ґ' => 'r',
        'Ғ' => 'f',
        'ғ' => 'f',
        'Ҕ' => 'h',
        'ҕ' => 'h',
        'Җ' => 'X',
        'җ' => 'x',
        'Ҙ' => '3',
        'ҙ' => '3',
        'Қ' => 'K',
        'қ' => 'k',
        'Ҝ' => 'K',
        'ҝ' => 'k',
        'Ҟ' => 'K',
        'ҟ' => 'k',
        'Ҡ' => 'K',
        'ҡ' => 'k',
        'Ң' => 'H',
        'ң' => 'h',
        'Ҥ' => 'H',
        'ҥ' => 'h',
        'Ҧ' => 'm',
        'ҧ' => 'm',
        'Ҩ' => 'a',
        'ҩ' => 'a',
        'Ҫ' => 'C',
        'ҫ' => 'c',
        'Ҭ' => 'T',
        'ҭ' => 't',
        'Ү' => 'Y',
        'ү' => 'Y',
        'Ұ' => 'Y',
        'ұ' => 'Y',
        'Ҳ' => 'X',
        'ҳ' => 'x',
        'Ҵ' => 'U',
        'ҵ' => 'u',
        'Ҷ' => 'u',
        'ҷ' => 'u',
        'Ҹ' => 'u',
        'ҹ' => 'u',
        'Һ' => 'h',
        'һ' => 'h',
        'Ҽ' => 'e',
        'ҽ' => 'e',
        'Ҿ' => 'e',
        'ҿ' => 'e',
        'Ӏ' => 'I',
        'Ӂ' => 'X',
        'ӂ' => 'x',
        'Ӄ' => 'K',
        'ӄ' => 'k',
        'Ӆ' => 'N',
        'ӆ' => 'n',
        'Ӈ' => 'H',
        'ӈ' => 'h',
        'Ӊ' => 'H',
        'ӊ' => 'h',
        'Ӌ' => 'u',
        'ӌ' => 'u',
        'Ӎ' => 'M',
        'ӎ' => 'M',
        'ӏ' => 'I',
        'Ӑ' => 'A',
        'ӑ' => 'a',
        'Ӓ' => 'A',
        'ӓ' => 'a',
        'Ӕ' => 'AE',
        'ӕ' => 'ae',
        'Ӗ' => 'E',
        'ӗ' => 'e',
        'Ә' => 'e',
        'ә' => 'e',
        'Ӛ' => 'e',
        'ӛ' => 'e',
        'Ӝ' => 'X',
        'ӝ' => 'x',
        'Ӟ' => '3',
        'ӟ' => '3',
        'Ӡ' => '3',
        'ӡ' => '3',
        'Ӣ' => 'N',
        'ӣ' => 'n',
        'Ӥ' => 'N',
        'ӥ' => 'n',
        'Ӧ' => 'O',
        'ӧ' => 'o',
        'Ө' => 'O',
        'ө' => 'o',
        'Ӫ' => 'O',
        'ӫ' => 'o',
        'Ӭ' => '3',
        'ӭ' => '3',
        'Ӯ' => 'y',
        'ӯ' => 'y',
        'Ӱ' => 'y',
        'ӱ' => 'y',
        'Ӳ' => 'y',
        'ӳ' => 'y',
        'Ӵ' => 'y',
        'ӵ' => 'y',
        'Ӷ' => '',
        'ӷ' => '',
        'Ӹ' => 'bl',
        'ӹ' => 'bl',
        'Ӻ' => 'f',
        'ӻ' => 'f',
        'Ӽ' => 'X',
        'ӽ' => 'x',
        'Ӿ' => 'X',
        'ӿ' => 'x',
        'Ԁ' => 'd',
        'ԁ' => 'd',
        'Ԃ' => 'd',
        'ԃ' => 'd',
        'Ԅ' => 'R',
        'ԅ' => 'R',
        'Ԇ' => 'R',
        'ԇ' => 'R',
        'Ԉ' => 'N',
        'ԉ' => 'N',
        'Ԋ' => 'H',
        'ԋ' => 'h',
        'Ԍ' => 'G',
        'ԍ' => 'g',
        'Ԏ' => 'T',
        'ԏ' => 't',
        'Ա' => 'U',
        'Բ' => 'F',
        'Գ' => '9',
        'Դ' => '7',
        'Ե' => 't',
        'Զ' => '2',
        'Է' => 't',
        'Ը' => 'L',
        'Թ' => 'A',
        'Ժ' => 'd',
        'Ի' => 'r',
        'Լ' => 'L',
        'Խ' => 'tu',
        'Ծ' => '6',
        'Կ' => 'u',
        'Հ' => 'R',
        'Ձ' => '2',
        'Ղ' => '7',
        'Ճ' => '',
        'Մ' => 'U',
        'Յ' => '3',
        'Ն' => 'l',
        'Շ' => '',
        'Ո' => '',
        'Չ' => '',
        'Պ' => 'M',
        'Ջ' => '',
        'Ռ' => '',
        'Ս' => 'U',
        'Վ' => '',
        'Տ' => 'S',
        'Ր' => '',
        'Ց' => '',
        'Ւ' => 'T',
        'Փ' => 'O',
        'Ք' => '',
        'Օ' => 'O',
        'Ֆ' => 'S',
        '՛' => '',
        '՜' => '',
        '՝' => '',
        '՞' => '',
        '՟' => '',
        'ՠ' => '',
        'ա' => 'W',
        'բ' => 'F',
        'գ' => 'q',
        'դ' => 'n',
        'ե' => 't',
        'զ' => 'q',
        'է' => 't',
        'ը' => 'n',
        'թ' => 'p',
        'ժ' => 'd',
        'ի' => 'h',
        'լ' => 'l',
        'խ' => 'lu',
        'ծ' => 'o',
        'կ' => 'u',
        'հ' => 'h',
        'ձ' => 'o',
        'ղ' => 'n',
        'ճ' => '',
        'մ' => 'u',
        'յ' => 'j',
        'ն' => 'u',
        'շ' => 'S',
        'ո' => 'n',
        'չ' => 'E',
        'պ' => 'W',
        'ջ' => '2',
        'ռ' => 'n',
        'ս' => 'u',
        'վ' => 'u',
        'տ' => 'un',
        'ր' => 'n',
        'ց' => 'g',
        'ւ' => 'L',
        'փ' => 'un',
        'ք' => 'p',
        'օ' => 'O',
        'ֆ' => 'S',
        '¢' => 'c',
        '„' => '',
        '^' => '',
        '<' => '',
        '>' => '',
        '!' => '',
        '²' => '2',
        '³' => '3',
        '"' => '',
        '$' => '_dollar_',
        '%' => '_percent_',
        '&' => '_and_',
        '/' => ' ',
        '(' => '',
        ')' => '',
        '=' => '_equals_',
        '?' => '',
        '`' => '',
        '´' => '',
        '©' => '_copyright_',
        '@' => '_at_',
        '€' => '_euro_',
        '*' => '',
        '~' => '',
        '+' => '_plus_',
        '#' => '_hash_',
        '\'' => '',
        '|' => '',
        ';' => '_',
        ',' => '_',
        '.' => '_',
        ':' => '_',
        '{' => '',
        '}' => '',
        '[' => '',
        ']' => '',
        ' ' => '_',
        '-' => '_',
    );
}
