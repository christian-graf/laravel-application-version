<?php

declare(strict_types=1);

namespace Fox\Application\Version\Tests\Unit;

use DMX\PHPUnit\Framework\TestCase;
use Fox\Application\Version\Version;
use Symfony\Component\Process\Process;
use Fox\Application\Version\VersionManager;
use PHPUnit\Framework\MockObject\MockObject;
use Illuminate\Contracts\Cache\Repository as CacheContract;

class VersionManagerTest extends TestCase
{
    /**
     * @var MockObject|CacheContract
     */
    private $cacheMock;

    /**
     * @var MockObject|Process
     */
    private $processMock;

    /**
     * @var string
     */
    private $composerFile;

    /**
     * {@inheritdoc}
     *
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheMock = $this->getMockBuilder(CacheContract::class)->disableOriginalConstructor()->getMock();
        $this->processMock = $this->getMockBuilder(Process::class)->disableOriginalConstructor()->getMock();
        $this->composerFile = __DIR__ . '/../data/composer.json';
    }

    /**
     * Test.
     */
    public function testReceiveVersionInformation()
    {
        $manager = new VersionManager($this->cacheMock, 10, null, $this->processMock);

        $this->processMock
            ->expects($this->once())
            ->method('run')
            ->willReturn(0)
        ;
        $this->processMock
            ->expects($this->once())
            ->method('isSuccessful')
            ->willReturn(true)
        ;
        $this->processMock
            ->expects($this->once())
            ->method('getOutput')
            ->willReturn('testReceiveVersionInformation')
        ;

        $package = $manager->receiveVersionInformation($this->composerFile);

        $this->assertNotEmpty($package);
        $this->assertEquals(
            [
                'id' => -1,
                'name' => 'tests/unit',
                'description' => 'My test composer file.',
                'version' => '1.2.3',
                'build' => 'testReceiveVersionInformation',
                'type' => 'project',
                'licence' => [
                    'Apache-2.0',
                ],
                'keywords' => [
                    'application',
                    'laravel',
                    'Cyberfox',
                    'Test',
                ],
            ],
            $package
        );
    }

    /**
     * Test.
     */
    public function testGetVersionWithoutComposerFile()
    {
        $manager = new VersionManager($this->cacheMock, 10);

        $expectedVersion = rand(1, 10) . '.' . rand(0, 20) . '.' . rand(100, 9999) . '-TEST';
        $this->cacheMock
            ->expects($this->once())
            ->method('remember')
            ->with('application-version', 10, $this->closure('0.0.0'))
            ->willReturn($expectedVersion)
        ;

        $manager->getCurrentVersion();
        // running twice to check using the previous received version
        $version = $manager->getCurrentVersion();
        $this->assertInstanceOf(Version::class, $version);
        $this->assertEquals($expectedVersion, (string) $version);
    }

    /**
     * Test.
     */
    public function testGetVersionWithComposerFile()
    {
        $manager = new VersionManager($this->cacheMock, 10, $this->composerFile);
        $cmd = new Process([
                'git',
                'log',
                '--pretty=%h',
                '-n1',
                'HEAD',
            ],
            dirname(realpath($this->composerFile))
        );
        $cmd->run();

        $expectedVersion = '1.2.3' . ($cmd->isSuccessful() ? '-' . trim($cmd->getOutput()) : null);
        $this->cacheMock
            ->expects($this->once())
            ->method('remember')
            ->with('application-version', 10, $this->closure($expectedVersion))
            ->willReturn($expectedVersion)
        ;

        $version = $manager->getCurrentVersion();
        $this->assertInstanceOf(Version::class, $version);
        $this->assertEquals($expectedVersion, (string) $version);
    }
}
