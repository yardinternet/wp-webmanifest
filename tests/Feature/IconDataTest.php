<?php

declare(strict_types=1);

use Yard\Webmanifest\Data\IconData;

it('can create filename', function () {
	$iconData = new IconData();
	$iconData->name = 'testIcon';
	$iconData->size = 512;
	$iconData->extension = 'png';

	expect($iconData->getFileName())->tobe('testIcon_512.png');
});

it('can create url name without extension', function () {
	$iconData = new IconData();
	$iconData->name = 'testIcon';
	$iconData->size = 512;
	$iconData->extension = 'png';

	expect($iconData->getUrlName())->toBe('testIcon_512');
});

it('can parse file name with extension', function () {
	$iconData = IconData::fromFileName('icon_192.png');

	expect($iconData->name)->toBe('icon')
		->and($iconData->size)->toBe(192)
		->and($iconData->extension)->toBe('png')
		->and($iconData->getFileName())->toBe('icon_192.png');
});

it('can parse url name without extension', function () {
	$iconData = IconData::fromFileName('icon_192');

	expect($iconData->name)->toBe('icon')
		->and($iconData->size)->toBe(192)
		->and($iconData->extension)->toBe('png')
		->and($iconData->getFileName())->toBe('icon_192.png');
});

it('returns empty icon data for invalid file name', function () {
	expect(IconData::fromFileName('icon_192.png.png')->size)->toBe(0)
		->and(IconData::fromFileName('icon-192')->size)->toBe(0);
});

it('returns empty icon data for non-numeric or out-of-range size', function (string $fileName) {
	expect(IconData::fromFileName($fileName)->size)->toBe(0);
})->with([
	'icon_invalid',
	'icon_',
	'icon_abc.png',
	'icon_-192',
	'icon_0',
	'icon_99999999999999999999',
]);
