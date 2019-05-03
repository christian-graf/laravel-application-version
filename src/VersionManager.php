<?php

declare(strict_types=1);

namespace Fox\Application\Version;

use Composer\IO\NullIO;
use Composer\Factory as Composer;
use Symfony\Component\Process\Process;
use Illuminate\Contracts\Cache\Repository as CacheContract;

class VersionManager
{
    /**
     * @var CacheContract
     */
    protected $cache;

    /**
     * @var int
     */
    protected $cacheTTL = 0;

    /**
     * @var string
     */
    protected $composerFile;

    /**
     * @var Version|null
     */
    protected $currentVersion = null;

    /**
     * @var Process|null
     */
    protected $process = null;

    /**
     * VersionManager constructor.
     *
     * @param CacheContract $cache
     * @param int           $cacheTTL
     * @param string|null   $composerFile
     * @param Process|null  $process
     */
    public function __construct(CacheContract $cache, int $cacheTTL, ?string $composerFile = null, ?Process $process = null)
    {
        $this->cache = $cache;
        $this->cacheTTL = $cacheTTL;
        $this->composerFile = $composerFile;
        $this->process = $process;
    }

    /**
     * Get the current version of your project.
     *
     * @return Version
     */
    public function getCurrentVersion(): Version
    {
        if ($this->currentVersion === null) {
            $version = $this->cache->remember('application-version', $this->cacheTTL, function () {
                $package = array_merge(
                    [
                        'version' => '0.0.0',
                        'build' => null,
                    ],
                    $this->composerFile ? $this->receiveVersionInformation($this->composerFile) : []
                );

                return $package['version'] . ($package['build'] ? '-' . $package['build'] : '');
            });

            $this->currentVersion = Version::create($version);
        }

        return $this->currentVersion;
    }

    /**
     * @return Process
     */
    protected function getProcess(): Process
    {
        if ($this->process === null) {
            $this->process = new Process([
                'git',
                'log',
                '--pretty=%h',
                '-n1',
                'HEAD',
                ],
                dirname(realpath($this->composerFile))
            );
        }

        return $this->process;
    }

    /**
     * Receive some useful information from the given composer json file and merge it with
     * the current git commit identifier of your project.
     *
     * Information you will get:
     *   - id ...identifier of the composer package [composer]
     *   - name ...name of the project/package [composer]
     *   - description ...package description [composer]
     *   - version  ...package version [composer]
     *   - type ...package type [composer]
     *   - licence ...defined licence [composer]
     *   - keywords ...describing keyword of the package [composer]
     *   - build ...id of the last commit message if available [git]
     *
     * @param string $composerFile the full qualified file name of the designated composer.json file
     *
     * @return array
     */
    public function receiveVersionInformation(string $composerFile): array
    {
        $package = Composer::create(new NullIO(), $composerFile, true)->getPackage();
        $cmd = $this->getProcess();
        $cmd->run();

        return [
            'id' => $package->getId(),
            'name' => $package->getName(),
            'description' => $package->getDescription(),
            'version' => $package->getPrettyVersion(),
            'build' => $cmd->isSuccessful() ? trim($cmd->getOutput()) : null,
            'type' => $package->getType(),
            'licence' => $package->getLicense(),
            'keywords' => $package->getKeywords(),
        ];
    }
}
