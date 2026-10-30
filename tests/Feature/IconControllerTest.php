<?php

declare(strict_types=1);

use Illuminate\Support\Facades\View;
use Yard\Webmanifest\Http\Controllers\IconController;
use Yard\Webmanifest\MaskableIcon;

beforeEach(function () {
	global $wp_query;
	$wp_query = Mockery::mock();
	$wp_query->shouldReceive('set_404')->once();

	WP_Mock::userFunction('status_header')->once()->with(404);

	config(['webmanifest.iconSizes' => [192]]);
	View::addLocation(__DIR__ . '/../fixtures/views');
});

it('returns 404 status with theme 404 view for invalid icon size', function () {
	$controller = new IconController(new MaskableIcon(\Intervention\Image\ImageManager::gd()));

	$response = $controller->index('icon_999');

	expect($response->getStatusCode())->toBe(404)
		->and(trim($response->getContent()))->toBe('Not found');
});

it('returns 404 status with theme 404 view when no site icon is set', function () {
	WP_Mock::userFunction('get_option')->with('site_icon')->andReturn(0);
	WP_Mock::userFunction('get_attached_file')->with(0)->andReturn(false);

	$controller = new IconController(new MaskableIcon(\Intervention\Image\ImageManager::gd()));

	$response = $controller->index('icon_192');

	expect($response->getStatusCode())->toBe(404);
});
