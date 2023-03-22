<?php

namespace App\Services\Uploader\src\Classes;

/**
 * Class ThumbConfig
 */
class ThumbConfig
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int|null
     */
    private $width;

    /**
     * @var int|null
     */
    private $height;

    /**
     * @var
     */
    private $mode;

    /**
     * ThumbConfig constructor.
     * @param string $alias
     * @param string $name
     * @param int|null $width
     * @param int|null $height
     * @param string|null $mode
     */
    public function __construct(string $alias, string $name, ?int $width, ?int $height, string $mode = null)
    {
        $this->alias = $alias;
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int|null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }
}
