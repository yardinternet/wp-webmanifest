<?php

declare(strict_types=1);

namespace Yard\Webmanifest\Http\Controllers;

use Illuminate\Routing\Controller;
use Webmozart\Assert\Assert;
use Yard\Webmanifest\Data\IconData;
use Yard\Webmanifest\Data\WebmanifestIconData;

class IconController extends Controller
{
	/**
	 * @param string $fileName must be in format '[name]_[size].[extention]'
	 *
	 * @return void
	 */
	public function index(string $iconName)
	{
		$iconData = $this->parseIconName($iconName);

		if (null === $iconData) {
			return null;
		}
	}

	private function parseIconName(string $iconName): ?IconData
	{
		return IconData::fromFileName($iconName);
	}

	private function getIconnByFileName()
	{
	}

	private function setFaviconManifestIcons(): void
	{
		$favicon = $this->getFavicon();

		if ('' === $favicon) {
			return;
		}

		$this->webmanifestData->icons = collect(); // reset icon list

		foreach ($this->getConfigList('iconSizes') as $size) {
			Assert::integer($size);

			$icon = $this->maskableIcon->getBase64Icon($size);

			if ('' === $icon) {
				$icon = $this->maskableIcon->createBase64Icon($size, $favicon);
			}

			$this->webmanifestData->icons->push(WebmanifestIconData::from([
				'src' => $icon,
				'sizes' => "{$size}x{$size}",
				'type' => 'image/png',
			]));
		}
	}

	private function getFavicon(): string
	{
		$icon = intval(get_option('site_icon'));

		$faviconPath = get_attached_file($icon); // get full path to image

		if (false === $faviconPath || false === file_exists($faviconPath)) {
			return '';
		}

		return file_get_contents($faviconPath) ?: '';
	}
}
