<?php

declare(strict_types=1);

namespace Yard\Webmanifest\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yard\Webmanifest\Data\IconData;
use Yard\Webmanifest\MaskableIcon;
use Yard\Webmanifest\Traits\Helpers;

class IconController extends Controller
{
	use Helpers;

	public function __construct(private MaskableIcon $maskableIcon)
	{
	}

	public function index(string $iconName): Response
	{
		$iconData = $this->parseIconName($iconName);

		$configuredSizes = $this->getConfigList('iconSizes');

		// Return 404 if wrong file name or if size is not in config.
		if (null === $iconData || false === in_array($iconData->size, $configuredSizes)) {
			return $this->dieAndNotFound();
		}

		$icon = $this->getIcon($iconData);

		// Return 404 if no site icon is available, instead of an empty image.
		if ('' === $icon) {
			return $this->dieAndNotFound();
		}

		return response($icon)->header('Content-Type', MaskableIcon::IMAGE_TYPE['mime']);
	}

	private function parseIconName(string $iconName): ?IconData
	{
		return IconData::fromFileName($iconName);
	}

	private function getIcon(IconData $iconData): string
	{
		$favicon = $this->getFavicon();

		if ('' === $favicon) {
			return '';
		}

		$icon = $this->maskableIcon->getBase64Icon($iconData);

		if ('' === $icon) {
			$icon = $this->maskableIcon->createBase64Icon($iconData, $favicon);
		}

		return base64_decode($icon) ?: '';
	}

	private function getFavicon(): string
	{
		$icon = intval(get_option('site_icon'));

		$faviconPath = get_attached_file($icon); // Get full path to image.

		if (false === $faviconPath || false === file_exists($faviconPath)) {
			return '';
		}

		return file_get_contents($faviconPath) ?: '';
	}
}
