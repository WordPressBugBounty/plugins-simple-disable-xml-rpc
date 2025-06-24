=== Simple Disable XML-RPC ===
Author URI: https://www.wpsatkhira.com
Plugin URI: https://wordpress.org/plugins/simple-disable-xml-rpc/
Donate link: 
Contributors: wpdelower, monarchwp23
Tags: disable xml, xmlrpc, xml, disable xml rpc, wordpress security
Requires at least: 6.1
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.3.5
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://www.wpsatkhira.com/donate

This plugin utilizes the native WordPress filter "xmlrpc_enabled" to deactivate the XML-RPC API on a WordPress site running version 3.5 or higher.

== Description ==

Simple Disable XML-RPC is a powerful yet user-friendly WordPress plugin designed to give website administrators complete control over the XML-RPC functionality of their WordPress site. XML-RPC is a feature that enables remote communication with your website, allowing external applications and services to interact with your WordPress installation. While XML-RPC can be useful, it can also pose security risks if not properly managed. This plugin simplifies the process of managing XML-RPC, enhancing your site's security and performance with ease.

== Key Features: ==

**Effortless XML-RPC Control:** With "Simple Disable XML-RPC," you can easily enable or disable XML-RPC functionality on your WordPress site. The plugin provides a straightforward toggle switch, allowing you to make quick adjustments as needed.

**Enhanced Website Security:** By disabling XML-RPC when it's not needed, you can significantly reduce the potential attack surface for malicious actors. This helps safeguard your website against various XML-RPC-related security threats.

**Improved Performance:** When XML-RPC is disabled, it can lead to improved website performance and resource optimization. This is particularly beneficial for sites where remote connectivity features are unnecessary.

**User-Friendly Interface:** The plugin's intuitive user interface ensures that even those with limited technical expertise can easily manage XML-RPC settings without the need for complex configurations or coding.

**Zero Coding Required:** You don't need to be a coding expert to control XML-RPC. The plugin abstracts the technical details, making it accessible to all WordPress users.

**Real-time Changes:** Any changes to XML-RPC settings take effect in real-time, ensuring that your site's security can be adjusted promptly to meet your requirements.

**Compatibility:** "Simple Disable XML-RPC" is designed to work seamlessly with various WordPress themes and plugins, ensuring that it won't disrupt your website's core functionality.

**Regular Updates:** The plugin is actively maintained and updated to ensure compatibility with the latest versions of WordPress and to address any potential issues.

**Customer Support:** In case you encounter any difficulties or have questions, the plugin's support team is available to assist you, providing prompt and helpful responses.

== Contributing & Bug Report ==
Bug reports and pull requests are welcome on [Github](https://github.com/WordPress-Satkhira-Community/simple-disable-xml-rpc).

== Frequently Asked Questions ==

= What is XML-RPC, and why should I consider disabling it? =

XML-RPC is a feature that allows remote communication with your WordPress site, but it can also pose security risks if not managed properly. Disabling it when not needed can enhance your site's security.

= Is there an admin interface for this plugin? =

Yes. Please go to Settings > Simple Disable XML-RPC

= How do I know if the plugin is working? =

There are a few easy methods for checking if XML-RPC is off:

1. Try using an XML-RPC WordPress client, like the official WordPress mobile apps. The WordPress mobile app should tell you that "XML-RPC services are disabled on this site" if the plugin is activated.
2. Use the curl command to send an XML-RPC request to your site. If the response contains "XML-RPC services are disabled on this site" then the plugin is working properly and WordPress will not send data back to XML-RPC requests.
3. Try the XML-RPC Validator, written by Danilo Ercoli of the Automattic Mobile Team - the tool is available at [https://xmlrpc-check.hostpress.me/](https://xmlrpc-check.hostpress.me/). Information and source code for the tool are available on GitHub at [https://github.com/daniloercoli/WordPress-XML-RPC-Validator](https://github.com/daniloercoli/WordPress-XML-RPC-Validator). Keep in mind that you want the validator to fail and tell you that XML-RPC services are disabled.

See the screenshots for examples of what these tools will return when the plugin is enabled.

= Does disabling XML-RPC improve website performance? =

Yes, when XML-RPC is disabled, it can lead to improved website performance and resource optimization, especially for sites where remote connectivity features are unnecessary.

= Is the Simple Disable XML-RPC plugin compatible with other WordPress themes and plugins? =

Yes, the Simple Disable XML-RPC plugin is designed to work seamlessly with various WordPress themes and plugins. It is regularly updated to ensure compatibility with the latest versions of WordPress and to address any potential issues.

= Is there customer support available for Simple Disable XML-RPC? =

Yes, in case you encounter any difficulties or have questions, the plugin's support team is available to assist you and provide prompt and helpful responses.

= How frequently is the "Simple Disable XML-RPC" plugin updated? =

The plugin is actively maintained and updated regularly to ensure compatibility with the latest versions of WordPress and to address any potential issues.

== Installation ==

1. Go to `Plugins` in the Admin menu
2. Click on the button `Add new`
3. Search for `Simple Disable XML-RPC` and click 'Install Now' or click on the `upload` link to upload `simple-disable-xml-rpc.zip`
4. Click on `Activate plugin`
5. Click Settings or go to Settings > Simple Disable XML-RPC
6. Check the box.

== Screenshots ==

1. An example of the error when XML RPC is disabled sucessfully
2. An example of Simple Disable XML-RPC Settings page.

== Changelog ==

= 1.3.5 (April 20, 2025) =
* Bug fix
* Performance update
* WordPress 6.8 Compatibility

= 1.3.4 (November 17, 2024) =
* Bug fix
* Performance update
* WordPress 6.7 Compatibility

= 1.3.3 (AJuly 17, 2024) =
* Bug fix
* Performance update
* WordPress 6.6 Compatibility

= 1.3.2 (April 02, 2024) =
* Bug fix
* Performance update
* WordPress 6.5 Compatibility

= 1.3.1 (March 23, 2024) =
* Bug fix and important Security update
* Plugin Live Preview Added

= 1.3 (March 12, 2024) =
* Performance and Security update

= 1.2.5 (March 12, 2024) =
* Plugin compatibility fixed

= 1.2.4 (March 12, 2024) =
* Plugin Bug fix & improvements

= 1.2.3 (March 11, 2024) =
* Bug fix & improvements

= 1.2.2 (February 21, 2024) =
* Bug fix & improvements

= 1.2.1 (January 31, 2024) =
* Settings description Modified

= 1.2 (January 31, 2024) =
* compatible with WordPress 6.4.3
* Bug fix
* New FAQs Added

= 1.1 =
* Redirect to settings page once the plugin is activated & Bug fix

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.3.5 (April 20, 2025) =
* Bug fix
* Performance update
* WordPress 6.8 Compatibility

= 1.3.4 (November 17, 2024) =
* Bug fix
* Performance update
* WordPress 6.7 Compatibility

= 1.3.3 (July 17, 2024) =
* Bug fix
* Performance update
* WordPress 6.6 Compatibility

= 1.3.2 (April 02, 2024) =
* Bug fix
* Performance update
* WordPress 6.5 Compatibility

= 1.3.1 (March 23, 2024) =
* Bug fix and important Security update
* Plugin Live Preview Added

= 1.3 (March 12, 2024) =
* Performance and Security update

= 1.2.5 (March 12, 2024) =
* Plugin compatibility fixed

= 1.2.4 (March 12, 2024) =
* Plugin Bug fix & improvements

= 1.2.3 (March 11, 2024) =
* Bug fix & improvements

= 1.2.2 (February 21, 2024) =
* Bug fix & improvements

= 1.2.1 (January 31, 2024) =
* Settings description Modified

= 1.2 (January 31, 2024) =
* compatible with WordPress 6.4.3
* Bug fix
* New FAQs Added

= 1.1 =
* Redirect to settings page once the plugin is activated & Bug fix

= 1.0 =
* Initial Release
