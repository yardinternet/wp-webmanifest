<?php

declare(strict_types=1);

namespace Yard\Webmanifest\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Webmozart\Assert\Assert;
use Yard\Webmanifest\MaskableIcon;

class IconData extends Data
{
	public string $name = 'icon'; // may not contain underscores
	public int $size = 0;
	public string $extension = MaskableIcon::IMAGE_TYPE['extension']; // may not contain dots

	/**
	 * @return Collection<int, IconData> collection of iconData objects from configured sizes
	 */
	public static function fromConfiguredSizes(): Collection
	{
		$configSizes = config('webmanifest.iconSizes');

		Assert::isArray($configSizes);

		$sizes = collect($configSizes);

		return $sizes->map(function (int $size) {
			return IconData::from([
				'size' => $size,
			]);
		});
	}

	public function getFileName(): string
	{
		return "{$this->name}_{$this->size}.{$this->extension}";
	}

	/**
	 * Name used in the icon url. Without extension, so webserver rules for static files
	 * (e.g. nginx locations matching *.png) don't intercept the dynamically generated icon.
	 */
	public function getUrlName(): string
	{
		return "{$this->name}_{$this->size}";
	}

	public static function fromFileName(string $fileName): ?self
	{
		return IconData::from(IconData::explodeFileName($fileName));
	}

	/**
	 * @param string $fileName
	 *
	 * @return array<string, int|string>
	 */
	private static function explodeFileName(string $fileName): array
	{
		$iconNameAndsuffix = explode('_', $fileName);

		if (2 !== count($iconNameAndsuffix)) {
			return [];
		}

		Assert::string($iconNameAndsuffix[1]);

		$sizeAndExtension = explode('.', $iconNameAndsuffix[1]);

		// url name without extension (e.g. icon_192) or file name with extension (e.g. icon_192.png)
		if (1 !== count($sizeAndExtension) && 2 !== count($sizeAndExtension)) {
			return [];
		}

		// reject non-numeric or out-of-range sizes, which would fail hydration of the int property
		$size = filter_var($sizeAndExtension[0], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

		if (false === $size) {
			return [];
		}

		$data = [
			'name' => $iconNameAndsuffix[0],
			'size' => $size,
		];

		if (isset($sizeAndExtension[1])) {
			$data['extension'] = $sizeAndExtension[1];
		}

		return $data;
	}
}
