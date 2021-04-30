<?php

declare(strict_types=1);

namespace Fox\Application\Version;

class Version
{
    /**
     * @var string|null
     */
    protected ?string $version = null;

    /**
     * @var int|null
     */
    protected ?int $major = null;

    /**
     * @var int|null
     */
    protected ?int $minor = null;

    /**
     * @var int|null
     */
    protected ?int $patch = null;

    /**
     * @var string|null
     */
    protected ?string $build = null;

    /**
     * @return static
     */
    public static function create(string $version): Version
    {
        if (empty($version)) {
            throw new \InvalidArgumentException('The given version number is empty!', 10);
        }

        $build = null;
        if (strpos($version, '-') !== false) {
            list($version, $build) = array_pad(explode('-', $version), 2, null);
        }

        list($major, $minor, $patch) = array_pad(explode('.', $version), 3, null);

        if ($major === null || $minor === null || $patch === null) {
            throw new \InvalidArgumentException('The given version number is invalid!', 20);
        }

        return new static((int) $major, (int) $minor, (int) $patch, $build);
    }

    /**
     * Version constructor.
     */
    public function __construct(int $major, int $minor, int $patch, ?string $build = null)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
        $this->build = $build;
    }

    public function major(): int
    {
        return $this->major;
    }

    public function minor(): int
    {
        return $this->minor;
    }

    public function patch(): int
    {
        return $this->patch;
    }

    public function build(): ?string
    {
        return $this->build;
    }

    /**
     * @param bool $excludeBuild
     *
     * @return string
     */
    public function toString(bool $excludeBuild = false): string
    {
        return $this->major()
            . '.' . $this->minor()
            . '.' . $this->patch()
            . (!$excludeBuild && !empty($this->build()) ? '-' . $this->build() : '')
            ;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
