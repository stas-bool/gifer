<?php


namespace tests;


use Gifer\Gifer;
use PHPUnit\Framework\TestCase;

class GiferTest extends TestCase
{
    public function testCreateGif(): void
    {
        $fontFile = __DIR__ . '/data/NotoSans-Regular.ttf';
        $file = Gifer::get(
            $fontFile, 'test', '#FFFFFF', '#000000', 10
        )->create();
        file_put_contents('/tmp/test.gif', $file);
        self::assertFileExists('/tmp/test.gif');

    }
}