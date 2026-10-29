<?php

declare(strict_types=1);

use Illuminate\Support\Facades\View;
use Yard\Webmanifest\Http\Controllers\IconController;
use Yard\Webmanifest\MaskableIcon;

beforeEach(function () {
	config(['webmanifest.iconSizes' => [192]]);
	View::addLocation(__DIR__ . '/../fixtures/views');
});

function expectNotFound(): void
{
	global $wp_query;
	$wp_query = Mockery::mock();
	$wp_query->shouldReceive('set_404')->once();

	WP_Mock::userFunction('status_header')->once()->with(404);
}

it('returns 404 status with theme 404 view for invalid icon size', function () {
	expectNotFound();

	$controller = new IconController(new MaskableIcon(\Intervention\Image\ImageManager::gd()));

	$response = $controller->index('icon_999');

	expect($response->getStatusCode())->toBe(404)
		->and(trim($response->getContent()))->toBe('Not found');
});

it('returns 404 status with theme 404 view when no site icon is set', function () {
	expectNotFound();

	WP_Mock::userFunction('get_option')->with('site_icon')->andReturn(0);
	WP_Mock::userFunction('get_attached_file')->with(0)->andReturn(false);

	$controller = new IconController(new MaskableIcon(\Intervention\Image\ImageManager::gd()));

	$response = $controller->index('icon_192');

	expect($response->getStatusCode())->toBe(404);
});

it('returns 404 status when the site icon can not be decoded', function () {
	expectNotFound();

	$svgPath = __DIR__ . '/../fixtures/icons/icon.svg';

	WP_Mock::userFunction('get_option')->with('site_icon')->andReturn(1);
	WP_Mock::userFunction('get_attached_file')->with(1)->andReturn($svgPath);
	WP_Mock::userFunction('get_transient')->andReturn(false);

	$controller = new IconController(new MaskableIcon(\Intervention\Image\ImageManager::gd()));

	$response = $controller->index('icon_192');

	expect($response->getStatusCode())->toBe(404);
});
