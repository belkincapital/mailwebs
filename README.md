# MAIL WEBS for WordPress
* Contributors: [Jason Jersey](https://github.com/icryptic)
* Tags: plugin, mail, smtp
* Requires at least: 3.8
* Tested up to: 4.0
* Stable tag: master
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

Secure Authenticated SMTP Server.

## Description

This plugin was designed to simply enable authenticated smtp email globally on WordPress and WordPress Multisite networks using the mail.we.bs email server. 

You may also use this plugin with any other smtp server, you'll just want to make sure you specify your localhost within the code we have you copy to your wp-config.php file upon setup (see: Installation).
 
`Additional Plugin (optional): https://github.com/belkincapital/github-updater (used for updates)`

## Installation

### Manual

1. Download the latest [tagged archive](https://github.com/belkincapital/mailwebs/releases) (choose the "zip" option).
2. Unzip the archive.
3. Fix the folder name to remove the extra stuff GitHub adds, like _-master_. Rename to **mailwebs**.
4. Copy the folder to your `/wp-content/plugins/` directory.
5. Go to the Plugins screen and click __Activate__.
6. Edit and add the following code to the wp-config.php file (below).

NOTE: Make sure you edit the **MAILWEBS_MAIL_FROM**, **MAILWEBS_MAIL_FROM_NAME**, **MAILWEBS_SMTP_USER** and **MAILWEBS_SMTP_PASS**.

Check out the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation). 

`define('MAILWEBS_ON', true); // Enable global smtp server.`

`define('MAILWEBS_MAIL_FROM', 'your@email-address.com'); // Your From email address.`

`define('MAILWEBS_MAIL_FROM_NAME', 'My Company Name'); // Your From name.`

`define('MAILWEBS_MAILER', 'smtp'); // Leave set to smtp.`

`define('MAILWEBS_SET_RETURN_PATH', 'false'); // Sets $phpmailer->Sender if true.`

`define('MAILWEBS_SMTP_HOST', 'mail.vps42975.mylogin.co'); // The SMTP mail host.`

`define('MAILWEBS_SMTP_PORT', 2525); // 587 and 25 may also be used.`

`define('MAILWEBS_SSL', ''); // Possible values '', 'ssl', 'tls'.`

`define('MAILWEBS_SMTP_AUTH', true); // True turns on SMTP authentication.`

`define('MAILWEBS_SMTP_USER', 'your@email-address.com'); // SMTP authentication username.`

`define('MAILWEBS_SMTP_PASS', '1234567890'); // SMTP authentication password.`

## Issues

Please log issues on the GitHub at https://github.com/belkincapital/mailwebs/issues

If you are using a WordPress Multisite installation, the plugin **should** be network activated.

## Pull Requests

Please fork and submit pull requests against the `develop` branch.