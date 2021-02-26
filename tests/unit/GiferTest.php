<?php


namespace unit;


use Gifer\Gifer;
use PHPUnit\Framework\TestCase;

class GiferTest extends TestCase
{
    public function testCreateGif(): void
    {
        $fontFile = __DIR__ . '/../data/NotoSans-Regular.ttf';
        $file = Gifer::createGif(
            $fontFile, 'test', '#FFFFFF', '#000000', 10
        );

        self::assertIsString($file);
        self::assertGreaterThan(0, strlen($file));
    }
}