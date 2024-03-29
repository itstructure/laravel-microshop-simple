<?php

namespace App\Services\Uploader\src\Helpers;

use Exception;
use Illuminate\Support\Arr;
use Imagine\Filter\Basic\Autorotate;
use Imagine\Image\{Box, BoxInterface, ImageInterface, ImagineInterface, ManipulatorInterface, Palette\RGB, Point};


class ImageHelper
{
    /**
     * GD2 driver definition for Imagine implementation using the GD library.
     */
    const DRIVER_GD2 = 'gd2';
    /**
     * imagick driver definition.
     */
    const DRIVER_IMAGICK = 'imagick';
    /**
     * gmagick driver definition.
     */
    const DRIVER_GMAGICK = 'gmagick';

    /**
     * @var array|string the driver to use. This can be either a single driver name or an array of driver names.
     * If the latter, the first available driver will be used.
     */
    public static $driver = [self::DRIVER_GMAGICK, self::DRIVER_IMAGICK, self::DRIVER_GD2];
    /**
     * @var ImagineInterface instance.
     */
    private static $_imagine;


    /**
     * @var string background color to use when creating thumbnails in `ImageInterface::THUMBNAIL_INSET` mode with
     * both width and height specified. Default is white.
     */
    public static $thumbnailBackgroundColor = 'FFF';
    /**
     * @var string background alpha (transparency) to use when creating thumbnails in `ImageInterface::THUMBNAIL_INSET`
     * mode with both width and height specified. Default is solid.
     */
    public static $thumbnailBackgroundAlpha = 100;

    /**
     * Returns the `Imagine` object that supports various image manipulations.
     * @return ImagineInterface the `Imagine` object
     */
    public static function getImagine()
    {
        if (self::$_imagine === null) {
            self::$_imagine = static::createImagine();
        }

        return self::$_imagine;
    }

    /**
     * @param ImagineInterface $imagine the `Imagine` object.
     */
    public static function setImagine($imagine)
    {
        self::$_imagine = $imagine;
    }

    /**
     * Creates an `Imagine` object based on the specified [[driver]].
     * @return ImagineInterface the new `Imagine` object
     * @throws Exception if [[driver]] is unknown or the system doesn't support any [[driver]].
     */
    protected static function createImagine()
    {
        foreach ((array)static::$driver as $driver) {
            switch ($driver) {
                case self::DRIVER_GMAGICK:
                    if (class_exists('Gmagick', false)) {
                        return new \Imagine\Gmagick\Imagine();
                    }
                    break;
                case self::DRIVER_IMAGICK:
                    if (class_exists('Imagick', false)) {
                        return new \Imagine\Imagick\Imagine();
                    }
                    break;
                case self::DRIVER_GD2:
                    if (function_exists('gd_info')) {
                        return new \Imagine\Gd\Imagine();
                    }
                    break;
                default:
                    throw new Exception("Unknown driver: $driver");
            }
        }
        throw new Exception('Your system does not support any of these drivers: ' . implode(',', (array)static::$driver));
    }

    /**
     * Takes either file path or ImageInterface. In case of file path, creates an instance of ImageInterface from it.
     *
     * @param string|resource|ImageInterface $image
     * @return ImageInterface
     * @throws Exception
     */
    protected static function ensureImageInterfaceInstance($image)
    {
        if ($image instanceof ImageInterface) {
            return $image;
        }

        if (is_resource($image)) {
            return static::getImagine()->read($image);
        }

        if (is_string($image)) {
            return static::getImagine()->open($image);
        }

        throw new Exception('File should be either ImageInterface, resource or a string containing file path.');
    }

    /**
     * Crops an image.
     *
     * For example:
     *
     * ```php
     * $obj->crop('path\to\image.jpg', 200, 200, [5, 5]);
     *
     * $point = new \Imagine\Image\Point(5, 5);
     * $obj->crop('path\to\image.jpg', 200, 200, $point);
     * ```
     *
     * @param string|resource|ImageInterface $image either ImageInterface, resource or a string containing file path
     * @param int $width the crop width
     * @param int $height the crop height
     * @param array $start the starting point. This must be an array with two elements representing `x` and `y` coordinates.
     * @return ImageInterface
     * @throws Exception if the `$start` parameter is invalid
     */
    public static function crop($image, $width, $height, array $start = [0, 0])
    {
        if (!isset($start[0], $start[1])) {
            throw new Exception('$start must be an array of two elements.');
        }

        return static::ensureImageInterfaceInstance($image)
            ->copy()
            ->crop(new Point($start[0], $start[1]), new Box($width, $height));
    }

    /**
     * Rotates an image automatically based on EXIF information.
     *
     * @param string|resource|ImageInterface $image either ImageInterface, resource or a string containing file path
     * @param string $color
     * @return \Imagine\Image\ImageInterface
     */
    public static function autorotate($image, $color = '000000')
    {
        return (new Autorotate($color))->apply(static::ensureImageInterfaceInstance($image));
    }

    /**
     * Creates a thumbnail image.
     *
     * If one of thumbnail dimensions is set to `null`, another one is calculated automatically based on aspect ratio of
     * original image. Note that calculated thumbnail dimension may vary depending on the source image in this case.
     *
     * If both dimensions are specified, resulting thumbnail would be exactly the width and height specified. How it's
     * achieved depends on the mode.
     *
     * If `ImageInterface::THUMBNAIL_OUTBOUND` mode is used, which is default, then the thumbnail is scaled so that
     * its smallest side equals the length of the corresponding side in the original image. Any excess outside of
     * the scaled thumbnail’s area will be cropped, and the returned thumbnail will have the exact width and height
     * specified.
     *
     * If thumbnail mode is `ImageInterface::THUMBNAIL_INSET`, the original image is scaled down so it is fully
     * contained within the thumbnail dimensions. The rest is filled with background that could be configured via
     * [[Image::$thumbnailBackgroundColor]] and [[Image::$thumbnailBackgroundAlpha]].
     *
     * @param string|resource|ImageInterface $image either ImageInterface, resource or a string containing file path
     * @param int $width the width in pixels to create the thumbnail
     * @param int $height the height in pixels to create the thumbnail
     * @param string $mode mode of resizing original image to use in case both width and height specified
     * @return ImageInterface
     */
    public static function thumbnail($image, $width, $height, $mode = ManipulatorInterface::THUMBNAIL_OUTBOUND)
    {
        $img = self::ensureImageInterfaceInstance($image);

        /** @var BoxInterface $sourceBox */
        $sourceBox = $img->getSize();
        $thumbnailBox = static::getThumbnailBox($sourceBox, $width, $height);

        if (self::isUpscaling($sourceBox, $thumbnailBox)) {
            return $img->copy();
        }

        $img = $img->thumbnail($thumbnailBox, $mode);

        if ($mode == ManipulatorInterface::THUMBNAIL_OUTBOUND) {
            return $img;
        }

        $size = $img->getSize();

        if ($size->getWidth() == $width && $size->getHeight() == $height) {
            return $img;
        }

        $palette = new RGB();
        $color = $palette->color(static::$thumbnailBackgroundColor, static::$thumbnailBackgroundAlpha);

        // create empty image to preserve aspect ratio of thumbnail
        $thumb = static::getImagine()->create($thumbnailBox, $color);

        // calculate points
        $startX = 0;
        $startY = 0;
        if ($size->getWidth() < $width) {
            $startX = ceil(($width - $size->getWidth()) / 2);
        }
        if ($size->getHeight() < $height) {
            $startY = ceil(($height - $size->getHeight()) / 2);
        }

        $thumb->paste($img, new Point($startX, $startY));

        return $thumb;
    }

    /**
     * Resizes an image.
     *
     * If one of the dimensions is set to `null`, another one is calculated automatically based on aspect ratio of
     * original image.
     *
     * If both of the dimensions are set then new dimensions are calculated so that image keeps aspect ratio.
     *
     * You can set $keepAspectRatio to false if you want to force fixed width and height.
     *
     * @param string|resource|ImageInterface $image either ImageInterface, resource or a string containing file path
     * @param int $width the width in pixels
     * @param int $height the height in pixels
     * @param bool $keepAspectRatio should the image keep aspect ratio
     * @param bool $allowUpscaling should the image be upscaled if needed
     * @return ImageInterface
     */
    public static function resize($image, $width, $height, $keepAspectRatio = true, $allowUpscaling = false)
    {
        $img = self::ensureImageInterfaceInstance($image)->copy();

        /** @var BoxInterface $sourceBox */
        $sourceBox = $img->getSize();
        $destinationBox = static::getBox($sourceBox, $width, $height, $keepAspectRatio);

        if ($allowUpscaling === false && self::isUpscaling($sourceBox, $destinationBox)) {
            return $img;
        }

        return $img->resize($destinationBox);
    }

    /**
     * Adds a watermark to an existing image.
     * @param string|resource|ImageInterface $image either ImageInterface, resource or a string containing file path
     * @param string|resource|ImageInterface $watermarkImage either ImageInterface, resource or a string containing watermark file path
     * @param array $start the starting point. This must be an array with two elements representing `x` and `y` coordinates.
     * @return ImageInterface
     * @throws Exception if `$start` is invalid
     */
    public static function watermark($image, $watermarkImage, array $start = [0, 0])
    {
        if (!isset($start[0], $start[1])) {
            throw new Exception('$start must be an array of two elements.');
        }

        $img = self::ensureImageInterfaceInstance($image);
        $watermark = self::ensureImageInterfaceInstance($watermarkImage);
        $img->paste($watermark, new Point($start[0], $start[1]));

        return $img;
    }

    /**
     * Draws a text string on an existing image.
     * @param string|resource|ImageInterface $image either ImageInterface, resource or a string containing file path
     * @param string $text the text to write to the image
     * @param string $fontFile the file path or path alias
     * @param array $start the starting position of the text. This must be an array with two elements representing `x` and `y` coordinates.
     * @param array $fontOptions the font options. The following options may be specified:
     *
     * - color: The font color. Defaults to "fff".
     * - size: The font size. Defaults to 12.
     * - angle: The angle to use to write the text. Defaults to 0.
     *
     * @return ImageInterface
     * @throws Exception if `$fontOptions` is invalid
     */
    public static function text($image, $text, $fontFile, array $start = [0, 0], array $fontOptions = [])
    {
        if (!isset($start[0], $start[1])) {
            throw new Exception('$start must be an array of two elements.');
        }

        $fontSize = Arr::get($fontOptions, 'size', 12);
        $fontColor = Arr::get($fontOptions, 'color', 'fff');
        $fontAngle = Arr::get($fontOptions, 'angle', 0);

        $palette = new RGB();
        $color = $palette->color($fontColor);

        $img = self::ensureImageInterfaceInstance($image);
        $font = static::getImagine()->font($fontFile, $fontSize, $color);

        $img->draw()->text($text, $font, new Point($start[0], $start[1]), $fontAngle);

        return $img;
    }

    /**
     * Adds a frame around of the image. Please note that the image size will increase by `$margin` x 2.
     * @param string|resource|ImageInterface $image either ImageInterface, resource or a string containing file path
     * @param int $margin the frame size to add around the image
     * @param string $color the frame color
     * @param int $alpha the alpha value of the frame.
     * @return ImageInterface
     */
    public static function frame($image, $margin = 20, $color = '666', $alpha = 100)
    {
        $img = self::ensureImageInterfaceInstance($image);

        $size = $img->getSize();

        $pasteTo = new Point($margin, $margin);

        $palette = new RGB();
        $color = $palette->color($color, $alpha);

        $box = new Box($size->getWidth() + ceil($margin * 2), $size->getHeight() + ceil($margin * 2));

        $finalImage = static::getImagine()->create($box, $color);

        $finalImage->paste($img, $pasteTo);

        return $finalImage;
    }

    /**
     * Returns box for a thumbnail to be created. If one of the dimensions is set to `null`, another one is calculated
     * automatically based on width to height ratio of original image box.
     *
     * @param BoxInterface $sourceBox original image box
     * @param int $width thumbnail width
     * @param int $height thumbnail height
     * @return BoxInterface thumbnail box
     */
    protected static function getThumbnailBox(BoxInterface $sourceBox, $width, $height)
    {
        if ($width !== null && $height !== null) {
            return new Box($width, $height);
        }

        return self::getBox($sourceBox, $width, $height, false);
    }

    /**
     * Returns box for an image to be created.
     * If one of the dimensions is set to `null`, another one is calculated automatically based on width to height ratio
     * of original image box.
     *
     * If both of the dimensions are set then new dimensions are calculated so that image keeps aspect ratio.
     *
     * You can set $keepAspectRatio to false if you want to force fixed width and height.
     * @param BoxInterface $sourceBox
     * @param $width
     * @param $height
     * @param bool $keepAspectRatio
     * @return Box
     * @throws Exception
     */
    protected static function getBox(BoxInterface $sourceBox, $width, $height, $keepAspectRatio = true)
    {
        if ($width === null && $height === null) {
            throw new Exception('Width and height cannot be null at same time.');
        }

        $ratio = $sourceBox->getWidth() / $sourceBox->getHeight();
        if ($keepAspectRatio === false) {
            if ($height === null) {
                $height = ceil($width / $ratio);
            } elseif ($width === null) {
                $width = ceil($height * $ratio);
            }
        } else {
            if ($height === null) {
                $height = ceil($width / $ratio);
            } elseif ($width === null) {
                $width = ceil($height * $ratio);
            } elseif ($width / $height > $ratio) {
                $width = $height * $ratio;
            } else {
                $height = $width / $ratio;
            }
        }

        return new Box($width, $height);
    }

    /**
     * Checks if upscaling is going to happen
     * @param BoxInterface $sourceBox
     * @param BoxInterface $destinationBox
     * @return bool
     */
    protected static function isUpscaling(BoxInterface $sourceBox, BoxInterface $destinationBox)
    {
        return
            (
                $sourceBox->getWidth() <= $destinationBox->getWidth()
                && $sourceBox->getHeight() <= $destinationBox->getHeight()
            ) || (
                !$destinationBox->getWidth() && !$destinationBox->getHeight()
            );
    }
}
