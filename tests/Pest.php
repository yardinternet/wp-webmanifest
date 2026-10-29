<?php

declare(strict_types=1);

uses(Yard\Webmanifest\Tests\TestCase::class)
	// Reset WP_Mock per test, otherwise function mocks leak into other tests depending on the (random) order.
	->beforeEach(fn () => WP_Mock::setUp())
	->afterEach(fn () => WP_Mock::tearDown())
	->in('Feature');
