# Changelog

All notable changes to `medianet-dev/cloud-message` will be documented in this file

## 2.0.0 - Unreleased

### Added
- Support for Laravel 12 and Laravel 13.

### Changed
- Minimum PHP version bumped to 8.1 (Laravel 9+ baseline; older PHP versions were already incompatible due to upstream `firebase/php-jwt` security advisories blocking older `google/apiclient` releases).
- Minimum Laravel version is now 9.0; dropped Laravel 7 and 8 from the supported range.
- `phpunit.xml.dist` migrated to a schema compatible with PHPUnit 9, 10, and 11.

### Removed
- Support for PHP 7.3, 7.4, and 8.0.
- Support for Laravel 7 and Laravel 8.

## 1.0.0 - 2024-09-16

- initial release
