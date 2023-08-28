## About SmartDirectory

SmartDirectory is a directory listing wordpress plugin

1. [Installation](#installation)
2. [Shortcode](#shortcode)
3. [PHPUnit Test](#phpunit)
4. [PHPCS](#phpcs)

## Installation

1. Clone github repository

	```sh
	git clone https://github.com/mdalaminbey/smartdirectory.git
	```
2. Go plugin directory

	```sh
	cd smartdirectory
	```
2. Install Composer

	```sh
	composer update
	```
## ShortCode

1. Create directory

	```sh
	[smart-directory-form]
	```
2. All Directories

	```sh
	[smart-directory-listings]
	```
3. Specific user Directories

	```sh
	[smart-directory-user-listings]
	```
## PHPUnit
To run the PHPUnit test, you have to install `WP-CLI` and `SVN-CLI`

### Follow these two links to install `WP-CLI` and `SVN-CLI`

1. [WP-CLI](https://make.wordpress.org/cli/handbook/guides/installing/)
2. [TortoiseSVN](https://tortoisesvn.net/)

### After install `WP-CLI` and `SVN-CLI` follow this step

1. install wp-tests.sh

	```sh
	bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
	```
2. Now times to run PHPUnit Test

	```sh
	phpunit
	```
3. `Note:` If you facing a fatal error link these. Follow this link

	```
	Warning: require_once(/tmp/wordpress//wp-includes/class-phpmailer.php): failed to open stream: No such file or directory in /tmp/wordpress-tests-lib/includes/mock-mailer.php on line 2

	Fatal error: require_once(): Failed opening required '/tmp/wordpress//wp-includes/class-phpmailer.php' (include_path='.:/opt/lampp/lib/php') in /tmp/wordpress-tests-lib/includes/mock-mailer.php on line 2
	```

	https://github.com/wp-cli/wp-cli/issues/2177
## PHPCS
SmartDirectory already contains phpcs rules

1. Setup for WordPress follow this [LINK](https://github.com/WordPress/WordPress-Coding-Standards)
2. Run PHPCS

	```sh
	phpcs
	```
