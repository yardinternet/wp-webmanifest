<?php

declare(strict_types=1);

namespace Yard\Webmanifest\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

class WebManifestData extends Data
{
	public string $lang;
	public string $name;
	#[MapOutputName('short_name')]
	public string $shortName;
	public string $description;
	#[MapOutputName('start_url')]
	public string $startUrl;
	public string $display;
	#[MapOutputName('prefer_related_applications')]
	public bool $preferRelatedApplications;
	public string $orientation;
	/** @var Collection<int, WebmanifestIconData> */
	public Collection $icons;
	#[MapOutputName('background_color')]
	public string $backgroundColor;
	#[MapOutputName('theme_color')]
	public string $themeColor;
}
