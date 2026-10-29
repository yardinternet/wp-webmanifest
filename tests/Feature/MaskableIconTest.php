<?php

declare(strict_types=1);

use Yard\Webmanifest\Data\IconData;
use Yard\Webmanifest\MaskableIcon;

it('can get base64 icon from transiet', function () {
	$maskableIcon = new MaskableIcon(\Intervention\Image\ImageManager::gd());

	$iconData = new IconData();
	$iconData->name = 'testIcon';
	$iconData->size = 512;
	$iconData->extension = 'png';

	WP_Mock::userFunction('get_transient')
		->once()
		->with($iconData->getFileName())
		->andReturn('ICON BASE64');

	expect($maskableIcon->getBase64Icon($iconData))->tobe('ICON BASE64');
});

it('only supports raster source formats', function (string $mimeType, bool $supported) {
	expect(MaskableIcon::supportsMimeType($mimeType))->toBe($supported);
})->with([
	['image/png', true],
	['image/jpeg', true],
	['image/webp', true],
	['image/svg+xml', false],
	['image/x-icon', false],
	['image/vnd.microsoft.icon', false],
]);
