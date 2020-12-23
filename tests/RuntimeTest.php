<?php

declare(strict_types=1);

namespace Akondas\Tests;

use Akondas\Runtime;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

class RuntimeTest extends TestCase
{
    use PHPMock;

    /**
     * @dataProvider initConfigurationProvider
     */
    public function testFreeMemoryWhenSet(string $value, int $expected): void
    {
        ini_set('memory_limit', $value);
        $runtime = new Runtime();

        self::assertEquals($expected, $runtime->totalMemory());
    }

    public function testFreeMemoryWhenIniNotSet(): void
    {
        ini_set('memory_limit', '-1');
        $runtime = new Runtime();

        self::assertTrue($runtime->totalMemory() > 0);

        ini_set('memory_limit', '');
        self::assertTrue($runtime->totalMemory() > 0);
    }

    public function testThrowExceptionWhenThereIsNoPossibilityToDetermine(): void
    {
        $this->getFunctionMock('Akondas', 'exec')->expects($this->once())->willReturn(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No possibility to determine memory limits');

        (new Runtime())->totalMemory();
    }

    public function testThrowExceptionWhenSomethingGoesWrong(): void
    {
        $this->getFunctionMock('Akondas', 'exec')->expects($this->once())->willThrowException(new \ErrorException());

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No possibility to determine memory limits');

        (new Runtime())->totalMemory();
    }

    /**
     * https://www.php.net/manual/en/faq.using.php#faq.using.shorthandbytes.
     *
     * The available options are K (for Kilobytes), M (for Megabytes) and G (for Gigabytes;
     * available since PHP 5.1.0), and are all case-insensitive. Anything else assumes bytes.
     */
    public function initConfigurationProvider(): array
    {
        return [
            ['1024', 1024],
            ['34K', 34816],
            ['34k', 34816],
            ['128M', 134217728],
            ['128m', 134217728],
            ['2G', 2147483648],
            ['2g', 2147483648],
            ['34816P', 34816],
            ['34816p', 34816],
        ];
    }
}
