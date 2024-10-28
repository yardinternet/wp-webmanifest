<?php

declare(strict_types=1);

use Yard\WebmanifestGenerator\Facades\WebmanifestGenerator;

it('can retrieve a random inspirational quote', function () {
    $quote = WebmanifestGenerator::getQuote();

    expect($quote)->tobe('For every Sage there is an Acorn.');
});
