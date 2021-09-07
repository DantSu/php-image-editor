---
title: \DantSu\PHPImageEditor\Image
footer: false
---

# Image

DantSu\PHPImageEditor\Image is PHP library to easily edit image with GD extension. Resize, crop, merge, draw, and many more options !



* Full name: `\DantSu\PHPImageEditor\Image`

**See Also:**

* https://github.com/DantSu/php-image-editor - 



## Constants

| Constant | Type | Value |
|:---      |:---  |:---   |
|`\DantSu\PHPImageEditor\Image::ALIGN_LEFT`||&#039;left&#039;|
|`\DantSu\PHPImageEditor\Image::ALIGN_CENTER`||&#039;center&#039;|
|`\DantSu\PHPImageEditor\Image::ALIGN_RIGHT`||&#039;right&#039;|
|`\DantSu\PHPImageEditor\Image::ALIGN_TOP`||&#039;top&#039;|
|`\DantSu\PHPImageEditor\Image::ALIGN_MIDDLE`||&#039;middle&#039;|
|`\DantSu\PHPImageEditor\Image::ALIGN_BOTTOM`||&#039;bottom&#039;|

## Methods

-  [getWidth](#getwidth)
-  [getHeight](#getheight)
-  [getType](#gettype)
-  [getImage](#getimage)
-  [isImageDefined](#isimagedefined)
- *static*  [create](#create)
-  [createNew](#createnew)
- *static*  [fromPath](#frompath)
-  [path](#path)
- *static*  [fromForm](#fromform)
-  [form](#form)
- *static*  [fromData](#fromdata)
-  [data](#data)
- *static*  [fromBase64](#frombase64)
-  [base64](#base64)
- *static*  [fromCurl](#fromcurl)
-  [curl](#curl)
-  [destroy](#destroy)
-  [rotate](#rotate)
-  [resizeProportion](#resizeproportion)
-  [downscaleProportion](#downscaleproportion)
-  [resize](#resize)
-  [downscaleAndCrop](#downscaleandcrop)
-  [crop](#crop)
-  [pasteOn](#pasteon)
-  [alphaMask](#alphamask)
-  [grayscale](#grayscale)
-  [writeText](#writetext)
-  [writeTextAndGetBoundingBox](#writetextandgetboundingbox)
-  [drawRectangle](#drawrectangle)
-  [drawLine](#drawline)
-  [drawLineWithAngle](#drawlinewithangle)
-  [drawArrowWithAngle](#drawarrowwithangle)
-  [drawArrow](#drawarrow)
-  [drawCircle](#drawcircle)
-  [savePNG](#savepng)
-  [saveJPG](#savejpg)
-  [saveGIF](#savegif)
-  [displayPNG](#displaypng)
-  [displayJPG](#displayjpg)
-  [displayGIF](#displaygif)
-  [getDataPNG](#getdatapng)
-  [getDataJPG](#getdatajpg)
-  [getDataGIF](#getdatagif)
-  [getBase64PNG](#getbase64png)
-  [getBase64JPG](#getbase64jpg)
-  [getBase64GIF](#getbase64gif)
-  [getBase64SourcePNG](#getbase64sourcepng)
-  [getBase64SourceJPG](#getbase64sourcejpg)
-  [getBase64SourceGIF](#getbase64sourcegif)

### getWidth

Return the image width

```php
public Image::getWidth(): int
```









**Return Value:**





---
### getHeight

Return the image height

```php
public Image::getHeight(): int
```









**Return Value:**





---
### getType

Return the image type
Image type : 1 GIF; 2 JPG; 3 PNG

```php
public Image::getType(): int
```









**Return Value:**





---
### getImage

Return image resource

```php
public Image::getImage(): resource|\GdImage
```









**Return Value:**





---
### isImageDefined

Return true if image is initialized

```php
public Image::isImageDefined(): bool
```









**Return Value:**





---
### create

(Static method) Create a new image with transparent background

```php
public static Image::create(int $width, int $height): \DantSu\PHPImageEditor\Image
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `width` | **int** |  |
| `height` | **int** |  |


**Return Value:**





---
### createNew

Create a new image with transparent background

```php
public Image::createNew(int $width, int $height): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `width` | **int** |  |
| `height` | **int** |  |


**Return Value:**





---
### fromPath

(Static method) Open image from local path or URL.

```php
public static Image::fromPath(string $path): \DantSu\PHPImageEditor\Image
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `path` | **string** |  |


**Return Value:**





---
### path

Open image from local path or URL.

```php
public Image::path(string $path): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `path` | **string** |  |


**Return Value:**





---
### fromForm

(Static method) Open an uploaded image from html form (using $file["tmp_name"]).

```php
public static Image::fromForm(array $file): \DantSu\PHPImageEditor\Image
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `file` | **array** |  |


**Return Value:**





---
### form

Open an uploaded image from html form (using $file["tmp_name"]).

```php
public Image::form(array $file): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `file` | **array** |  |


**Return Value:**





---
### fromData

(Static method) Create an Image instance from image raw data.

```php
public static Image::fromData(string $data): \DantSu\PHPImageEditor\Image
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `data` | **string** |  |


**Return Value:**





---
### data

Create an Image instance from image raw data.

```php
public Image::data(string $data): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `data` | **string** |  |


**Return Value:**





---
### fromBase64

(Static method) Create an Image instance from base64 image data.

```php
public static Image::fromBase64(string $base64): \DantSu\PHPImageEditor\Image
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `base64` | **string** |  |


**Return Value:**





---
### base64

Create an Image instance from base64 image data.

```php
public Image::base64(string $base64): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `base64` | **string** |  |


**Return Value:**





---
### fromCurl

(Static method) Open image from URL with cURL.

```php
public static Image::fromCurl(string $url): \DantSu\PHPImageEditor\Image
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `url` | **string** |  |


**Return Value:**





---
### curl

Open image from URL with cURL.

```php
public Image::curl(string $url): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `url` | **string** |  |


**Return Value:**





---
### destroy

Destroy image

```php
public Image::destroy(): $this
```









**Return Value:**





---
### rotate

Rotate the image

```php
public Image::rotate(int $angle): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `angle` | **int** |  |


**Return Value:**





---
### resizeProportion

Resize the image keeping the proportions.

```php
public Image::resizeProportion(int $width, int $height): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `width` | **int** |  |
| `height` | **int** |  |


**Return Value:**





---
### downscaleProportion

Downscale the image keeping the proportions.

```php
public Image::downscaleProportion(int $maxWidth, int $maxHeight): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `maxWidth` | **int** |  |
| `maxHeight` | **int** |  |


**Return Value:**





---
### resize

Resize the image.

```php
public Image::resize(int $width, int $height): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `width` | **int** |  |
| `height` | **int** |  |


**Return Value:**





---
### downscaleAndCrop

Downscale the image keeping the proportions then crop to fit to $width and $height params.

```php
public Image::downscaleAndCrop(int $width, int $height, int|string $anchorX = Image::ALIGN_CENTER, int|string $anchorY = Image::ALIGN_MIDDLE): $this
```

Use $anchorX and $anchorY to select the cropping zone (You can use `Image::ALIGN_...`).






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `width` | **int** |  |
| `height` | **int** |  |
| `anchorX` | **int|string** |  |
| `anchorY` | **int|string** |  |


**Return Value:**





---
### crop

Crop to fit to $width and $height params.

```php
public Image::crop(int $width, int $height, int|string $anchorX = Image::ALIGN_CENTER, int|string $anchorY = Image::ALIGN_MIDDLE): $this
```

Use $anchorX and $anchorY to select the cropping zone (You can use `Image::ALIGN_...`).






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `width` | **int** |  |
| `height` | **int** |  |
| `anchorX` | **int|string** |  |
| `anchorY` | **int|string** |  |


**Return Value:**





---
### pasteOn

Paste the image at $posX and $posY position (You can use `Image::ALIGN_...`).

```php
public Image::pasteOn(\DantSu\PHPImageEditor\Image $image, int|string $posX = Image::ALIGN_CENTER, int|string $posY = Image::ALIGN_CENTER): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `image` | **\DantSu\PHPImageEditor\Image** |  |
| `posX` | **int|string** |  |
| `posY` | **int|string** |  |


**Return Value:**





---
### alphaMask

Use a grayscale image (`$mask`) to apply transparency to the image.

```php
public Image::alphaMask(\DantSu\PHPImageEditor\Image $mask): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `mask` | **\DantSu\PHPImageEditor\Image** |  |


**Return Value:**





---
### grayscale

Apply a grayscale filter on the image.

```php
public Image::grayscale(): $this
```









**Return Value:**





---
### writeText

Write text on the image.

```php
public Image::writeText(string $string, string $fontPath, int $fontSize, string $color = &#039;#ffffff&#039;, int|string $posX, int|string $posY, string $anchorX = Image::ALIGN_CENTER, string $anchorY = Image::ALIGN_MIDDLE, int $rotation): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `string` | **string** |  |
| `fontPath` | **string** |  |
| `fontSize` | **int** |  |
| `color` | **string** |  |
| `posX` | **int|string** |  |
| `posY` | **int|string** |  |
| `anchorX` | **string** |  |
| `anchorY` | **string** |  |
| `rotation` | **int** |  |


**Return Value:**





---
### writeTextAndGetBoundingBox

Write text on the image and get the bounding box of the text in the image.

```php
public Image::writeTextAndGetBoundingBox(string $string, string $fontPath, int $fontSize, string $color = &#039;#ffffff&#039;, int|string $posX, int|string $posY, string $anchorX = Image::ALIGN_CENTER, string $anchorY = Image::ALIGN_MIDDLE, int $rotation): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `string` | **string** |  |
| `fontPath` | **string** |  |
| `fontSize` | **int** |  |
| `color` | **string** |  |
| `posX` | **int|string** |  |
| `posY` | **int|string** |  |
| `anchorX` | **string** |  |
| `anchorY` | **string** |  |
| `rotation` | **int** |  |


**Return Value:**





---
### drawRectangle

Draw a rectangle.

```php
public Image::drawRectangle(int $left, int $top, int $right, int $bottom, string $color): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `left` | **int** |  |
| `top` | **int** |  |
| `right` | **int** |  |
| `bottom` | **int** |  |
| `color` | **string** |  |


**Return Value:**





---
### drawLine

Draw a Line from `$originX, $originY` to `$dstX, $dstY`.

```php
public Image::drawLine(int $originX, int $originY, int $dstX, int $dstY, int $weight, string $color = &#039;#000000&#039;): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `originX` | **int** |  |
| `originY` | **int** |  |
| `dstX` | **int** |  |
| `dstY` | **int** |  |
| `weight` | **int** |  |
| `color` | **string** |  |


**Return Value:**





---
### drawLineWithAngle

Draw a line using angle and length.

```php
public Image::drawLineWithAngle(int $originX, int $originY, int $angle, int $length, int $weight, string $color = &#039;#000000&#039;): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `originX` | **int** |  |
| `originY` | **int** |  |
| `angle` | **int** |  |
| `length` | **int** |  |
| `weight` | **int** |  |
| `color` | **string** |  |


**Return Value:**





---
### drawArrowWithAngle

Draw an arrow with angle and length.

```php
public Image::drawArrowWithAngle(int $originX, int $originY, int $angle, int $length, int $weight, string $color = &#039;#000000&#039;): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `originX` | **int** |  |
| `originY` | **int** |  |
| `angle` | **int** |  |
| `length` | **int** |  |
| `weight` | **int** |  |
| `color` | **string** |  |


**Return Value:**





---
### drawArrow

Draw and arrow from `$originX, $originY` to `$dstX, $dstY`.

```php
public Image::drawArrow(int $originX, int $originY, int $dstX, int $dstY, int $weight, string $color = &#039;#000000&#039;): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `originX` | **int** |  |
| `originY` | **int** |  |
| `dstX` | **int** |  |
| `dstY` | **int** |  |
| `weight` | **int** |  |
| `color` | **string** |  |


**Return Value:**





---
### drawCircle

Draw a circle.

```php
public Image::drawCircle(int $posX, int $posY, int $diameter, string $color = &#039;#FFFFFF&#039;, string $anchorX = Image::ALIGN_CENTER, string $anchorY = Image::ALIGN_MIDDLE): $this
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `posX` | **int** |  |
| `posY` | **int** |  |
| `diameter` | **int** |  |
| `color` | **string** |  |
| `anchorX` | **string** |  |
| `anchorY` | **string** |  |


**Return Value:**





---
### savePNG

Save the image to PNG file.

```php
public Image::savePNG(string $path): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `path` | **string** |  |


**Return Value:**





---
### saveJPG

Save the image to JPG file.

```php
public Image::saveJPG(string $path, int $quality = -1): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `path` | **string** |  |
| `quality` | **int** |  |


**Return Value:**





---
### saveGIF

Save the image to GIF file.

```php
public Image::saveGIF(string $path): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `path` | **string** |  |


**Return Value:**





---
### displayPNG

Display in PNG format.

```php
public Image::displayPNG(): mixed
```









**Return Value:**





---
### displayJPG

Display in JPG format.

```php
public Image::displayJPG(int $quality = -1): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `quality` | **int** |  |


**Return Value:**





---
### displayGIF

Display in GIF format.

```php
public Image::displayGIF(): mixed
```









**Return Value:**





---
### getDataPNG

Get image PNG raw data

```php
public Image::getDataPNG(): string
```









**Return Value:**





---
### getDataJPG

Get image JPG raw data

```php
public Image::getDataJPG(int $quality = -1): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `quality` | **int** |  |


**Return Value:**





---
### getDataGIF

Get image GIF raw data

```php
public Image::getDataGIF(): string
```









**Return Value:**





---
### getBase64PNG

Get image PNG base64 data

```php
public Image::getBase64PNG(): string
```









**Return Value:**





---
### getBase64JPG

Get image JPG base64 data

```php
public Image::getBase64JPG(int $quality = -1): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `quality` | **int** |  |


**Return Value:**





---
### getBase64GIF

Get image GIF base64 data

```php
public Image::getBase64GIF(): string
```









**Return Value:**





---
### getBase64SourcePNG

Get image PNG base64 data for <img src=""> tag.

```php
public Image::getBase64SourcePNG(): string
```









**Return Value:**





---
### getBase64SourceJPG

Get image JPG base64 data for <img src=""> tag.

```php
public Image::getBase64SourceJPG(int $quality = -1): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `quality` | **int** |  |


**Return Value:**





---
### getBase64SourceGIF

Get image GIF base64 data for <img src=""> tag.

```php
public Image::getBase64SourceGIF(): string
```









**Return Value:**





---


---
> Automatically generated from source code comments on 2021-09-07 using [phpDocumentor](http://www.phpdoc.org/) and [dmarkic/phpdoc3-template-md](https://github.com/dmarkic/phpdoc3-template-md)
