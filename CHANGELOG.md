Changelog
=========

## 2.0.1 - Unreleased

### Changed
- Updated to require craftcms/cms `^3.0.0-RC1`.

## 2.0.0 - 2017-12-05

### Added 
- Craft 3 compatibility.

### Fixed
- Fixed encoding bug with Facebook’s redirect URI.

### Removed
- Removed `FacebookVariable`.

## 2.0.0-beta.3 - 2017-09-01

### Added
- Added support informations to composer.json.

### Improved
- Facebook now requires Craft 3.0.0 beta 20 or above.
- Updated widget types for Craft 3.

### Fixed
- Fixed encoding bug with Facebook’s redirect URI.

## 2.0.0-beta.2 - 2017-04-05

### Added

- Added reference generation instructions.
- Added docs.

### Improved

- Updated config settings for Craft 3.0.0-beta.8+.
- Renamed `$hasSettings` to `$hasCpSettings`.

### Removed

- Removed `FacebookVariable`.

## 2.0.0-beta.1 - 2017-03-09

### Added
- Craft 3 compatibility.
- Added `craftcms/cms` dependency.
- Added `league/oauth2-client` dependency.
- Added `league/oauth2-facebook` dependency.

### Removed
- Removed `dukt/craft-oauth` dependency.


## 1.1.1 - 2016-11-08

### Improved
-  Updated requirements to OAuth 2.0.2.
-  The plugin is now using Facebook API v2.8.
-  Improved installation process.
-  API improvements.
-  Improved exception handling for `Facebook_ReportsController::actionGetInsightsReport()`.

### Fixed
-  Fixed CSS for the Insights widget.


## 1.1.0 - 2016-08-09

### Added
-  Added an `apiVersion` config to customize the Graph API version used to perform requests to the Facebook API.

### Improved
-  Facebook plugin now requires OAuth 2.0+.
-  The plugin is now using Graph API 2.7.

### Fixed
-  Fixed a bug where Facebook_PluginService::checkRequirements() which was checking if Google was configured instead of Facebook.


## 1.0.12087141 - 2016-02-14

### Added
-  Facebook Insights widget for the dashboard..