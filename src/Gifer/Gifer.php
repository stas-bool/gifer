<?php


namespace Gifer;


use Imagick;
use ImagickDraw;
use ImagickPixel;
use RuntimeException;

class Gifer
{
    public static $gifWidth = 500;
    public static $gifRowHeight = 29;
    public static $fontSize = 20;
    public static $textCoordX = 5;
    public static $textCoordY = 20;

    private static $fontFile;
    private static $text;
    private static $bgColor;
    private static $fontColor;
    private static $speed;

    public static function createGif(
        string $fontFile,
        string $text,
        string $bgColor,
        string $fontColor,
        int $speed
    ): string
    {
        self::$fontFile = $fontFile;
        if (iconv_strlen($text) > 300) {
            throw new RuntimeException("Слишком длинный текст");
        }
        self::$text = $text;
        self::$bgColor = $bgColor;
        self::$fontColor = $fontColor;
        self::$speed = $speed;
        return self::create();
    }
    public static function calcWidth($text)
    {
        $image = new Imagick();
        $draw = new ImagickDraw();
        $draw->setFontSize(self::$fontSize);
        $draw->setFont(self::$fontFile);
        $fontMetrics = $image->queryFontMetrics($draw, $text);
        return $fontMetrics['textWidth'];
    }

    private static function create(): string
    {
        $newLines = substr_count(self::$text, "\n");

        $animation = new Imagick();
        $animation->setFormat("gif");

        $formatedTextArray = self::splitText(self::$text);
        $formatedText = implode("", $formatedTextArray);
        $textLength = mb_strlen($formatedText);

        for ($lastSymbol = 1; $lastSymbol <= $textLength; $lastSymbol++) {
            $image = new Imagick();
            $draw = new ImagickDraw();
            $draw->setFillColor(new ImagickPixel(self::$fontColor));
            $draw->setFontSize(self::$fontSize);
            $draw->setFont(self::$fontFile);
            $textToImage = mb_substr($formatedText, 0, $lastSymbol);
            $image::setResourceLimit(6, 1);

            $image->newImage(
                self::$gifWidth,
                self::$gifRowHeight * (count($formatedTextArray) + $newLines),
                new ImagickPixel(self::$bgColor)
            );

            $image->annotateImage($draw, self::$textCoordX, self::$textCoordY, 0, $textToImage);
            $image->setImageFormat('png');
            $animation->addImage($image);
            $animation->nextImage();
            $animation->setImageDelay(100 / self::$speed);
            $image->clear();
        }
        $animation->setImageDelay(300);

        return $animation->getImagesBlob();
    }

    private static function splitText(string $text): array
    {
        $words = explode(' ', $text);
        $row = '';
        $totalText = [];

        // Пока не кончатся слова в тексте
        while (count($words) !== 0) {
            // Убираем одно слово из начала текста
            $word = array_shift($words);

            $strWidth = self::calcWidth("{$row}{$word} ");
            if ($strWidth > self::$gifWidth) {
                // Если ширина строки + слово > 690
                // То слово возвращаем обратно
                array_unshift($words, $word);
                // и добавляем конец строки в массив
                $totalText[] = $row.PHP_EOL;
                $row = '';
            } else {
                $row .= $word . " ";
            }
        }
        $totalText[] = $row;
        return $totalText;
    }
}