# Changelog

## [v1.3.0] - 2026-11-03

-   Fix: serve manifest icons from urls without file extension, so webserver rules for static files don't intercept them
-   Fix: keep the default favicon when the site icon is not a raster image (e.g. SVG or ICO)
-   Fix: return a 404 instead of an empty image when no site icon is available
-   Chore: require PHP 8.2
-   Chore: remove composer.lock
-   Chore: add CODEOWNERS
-   Docs: update readme, badges and license

## [v1.2.0] - 2025-01-07

-   Added: icon route and controller serving the generated maskable icons
-   Change: only allow icon sizes configured in the config
-   Change: show the website 404 page when an icon is not found

## [v1.1.1] - 2025-01-02

-   Change: use 512px as default icon size

## [v1.1.0] - 2024-11-26

-   Added: output the manifest link in `wp_head`
-   Chore: add linting, tests and workflows

## [v1.0.2] - 2024-11-25

-   Fix: wrong config value

## [v1.0.1] - 2024-11-22

-   Added: manual config option
-   Change: no fill colour results in a transparent background
-   Fix: remove debug output
-   Docs: extend readme

## [v1.0.0] - 2024-10-29

-   Initial release
