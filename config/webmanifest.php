<?php

declare(strict_types=1);

return [
	'url' => '/manifest.webmanifest',
	// Example of possible icon sizes: [192, 384, 512, 1024]
	'iconSizes' => [512],

	// Theme color settings
	'background_color' => '',
	'theme_color' => '',

	/**
	 * Set icons manually,
	 * allowed attributes are: src, sizes, type, purpose.
	 *
	 * Example:
	 *
	 * 'icons' => [
	 *   [
	 *      'src' => 'path/to/icon.png',
	 *      'sizes' => '192x192',
	 *      'type' => 'image/png',
	 *   ],
	 * ],
	 */
	'icons' => [],
];
