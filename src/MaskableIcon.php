<?php

declare(strict_types=1);

namespace Yard\Webmanifest;

use Intervention\Image\ImageManager;
use Yard\Webmanifest\Data\IconData;

class MaskableIcon
{
	const IMAGE_TYPE = [
		'mime' => 'image/png',
		'extension' => 'png',
	];

	/**
	 * Source formats the GD driver can decode. Vector (SVG) and ICO site icons are not supported.
	 */
	const SUPPORTED_SOURCE_MIMES = [
		'image/png',
		'image/jpeg',
		'image/gif',
		'image/webp',
		'image/avif',
		'image/bmp',
	];

	public function __construct(private ImageManager $imageManager)
	{
	}

	/**
	 * @since 1.3.0
	 */
	public static function supportsMimeType(string $mimeType): bool
	{
		return in_array($mimeType, self::SUPPORTED_SOURCE_MIMES, true);
	}

	public function getBase64Icon(IconData $iconData): string
	{
		$icon = get_transient($iconData->getFileName());

		return is_string($icon) ? $icon : '';
	}

	public function createBase64Icon(IconData $iconData, string $originalIcon): string
	{
		$icon = $this->makeIconMaskable($iconData->size, $originalIcon);

		set_transient($iconData->getFileName(), $icon, WEEK_IN_SECONDS); // cache icon

		return $icon;
	}

	private function makeIconMaskable(int $size, string $originalIcon): string
	{
		// Add padding.
		$newIconSize = (int) round($size - ($size * 0.4));

		// load and shrink icon
		$icon = $this->imageManager->read($originalIcon)->resize($newIconSize, $newIconSize);

		// merge icon with bg layer
		$bg = $this->imageManager->create($size, $size)->place($icon, 'center');

		return base64_encode((string) $bg->toPng());
	}
}
