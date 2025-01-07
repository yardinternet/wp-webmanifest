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

	public static function fromFileName(string $fileName): ?self
	{
		return IconData::from(IconData::explodeFileName($fileName));
	}

	/**
	 * @param string $fileName
	 *
	 * @return array<string, string>
	 */
	private static function explodeFileName(string $fileName): array
	{
		$iconNameAndsuffix = explode('_', $fileName);

		if (2 !== count($iconNameAndsuffix)) {
			return [];
		}

		Assert::string($iconNameAndsuffix[1]);

		$sizeAndExtension = explode('.', $iconNameAndsuffix[1]);

		if (2 !== count($sizeAndExtension)) {
			return [];
		}

		return [
			'name' => $iconNameAndsuffix[0],
			'size' => $sizeAndExtension[0],
			'extension' => $sizeAndExtension[1],
		];
	}
}
