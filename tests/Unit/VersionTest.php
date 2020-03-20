<?php

declare(strict_types=1);

namespace Fox\Application\Version\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Fox\Application\Version\Version;

class VersionTest extends TestCase
{
    /**
     * @return array
     */
    public function getTestData(): array
    {
        $major = rand(1, 100);
        $minor = rand(0, 100);
        $patch = rand(0, 1000);
        $build = 'a' . rand(10, 99) . 'qwerty' . rand(100, 999) . 'V';
        $randomExpected = $major . '.' . $minor . '.' . $patch . '-' . $build;

        return [
            ['0.0.0', 0, 0, 0, null],
            ['0.13.0', 0, 13, 0, null],
            ['0.0.67', 0, 0, 67, null],
            ['2.4.10', 2, 4, 10, null],
            ['2.4.10', 2, 4, 10, null],
            ['0.0.0-testcase', 0, 0, 0, 'testcase'],
            ['4.0.32-alpha', 4, 0, 32, 'alpha'],
            [$randomExpected, $major, $minor, $patch, $build],
        ];
    }

    /**
     * @return array
     */
    public function getInvalidTestData(): array
    {
        return [
            ['3'],
            ['3.44'],
        ];
    }

    /**
     * Test.
     *
     * @param string      $versionString
     * @param int         $major
     * @param int         $minor
     * @param int         $patch
     * @param string|null $build
     *
     * @dataProvider getTestData
     */
    public function testGetter(string $versionString, int $major, int $minor, int $patch, ?string $build = null)
    {
        $version = new Version($major, $minor, $patch, $build);

        $this->assertEquals($major, $version->major());
        $this->assertEquals($minor, $version->minor());
        $this->assertEquals($patch, $version->patch());
        $this->assertEquals($build, $version->build());

        $this->assertEquals($versionString, (string) $version);
    }

    /**
     * Test.
     *
     * @param string      $versionString
     * @param int         $major
     * @param int         $minor
     * @param int         $patch
     * @param string|null $build
     *
     * @dataProvider getTestData
     */
    public function testFactoryMethodAndGetter(string $versionString, int $major, int $minor, int $patch, ?string $build = null)
    {
        $version = Version::create($versionString);

        $this->assertEquals($major, $version->major());
        $this->assertEquals($minor, $version->minor());
        $this->assertEquals($patch, $version->patch());
        $this->assertEquals($build, $version->build());
        $this->assertEquals($versionString, (string) $version);
    }

    /**
     * Test.
     */
    public function testFactoryMethodThrowsExceptionOnEmptyVersion()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The given version number is empty!');

        Version::create('');
    }

    /**
     * Test.
     *
     * @param string $version
     *
     * @dataProvider getInvalidTestData
     */
    public function testFactoryMethodThrowsExceptionOnInvalidVersion(string $version)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The given version number is invalid!');

        Version::create($version);
    }

    /**
     * Test.
     */
    public function testToString()
    {
        $version = new Version(2, 23, 400, 'abcd');

        $this->assertEquals('2.23.400-abcd', $version->toString());
        $this->assertEquals('2.23.400', $version->toString(true));
    }
}
