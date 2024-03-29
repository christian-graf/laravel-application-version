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
        if (str_contains($version, '-')) {
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
     *
     * @param int         $major
     * @param int         $minor
     * @param int         $patch
     * @param string|null $build
     */
    public function __construct(int $major, int $minor, int $patch, ?string $build = null)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
        $this->build = $build;
    }

    /**
     * Return the major part of the version number.
     *
     * @return int
     */
    public function major(): int
    {
        return $this->major;
    }

    /**
     * Return the minor part of the version number.
     *
     * @return int
     */
    public function minor(): int
    {
        return $this->minor;
    }

    /**
     * Return the patch part of the version number.
     *
     * @return int
     */
    public function patch(): int
    {
        return $this->patch;
    }

    /**
     * Return the build part of the version number.
     *
     * @return string|null
     */
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
