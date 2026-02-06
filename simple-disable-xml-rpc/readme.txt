=== Simple Disable XML-RPC | Reduce Brute Force & DDOS Attacks ===
Contributors: wpdelower, monarchwp23
Tags: disable xml, xmlrpc, xml, disable xml rpc, wordpress security
Requires at least: 6.1
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.4.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Simply disable XML-RPC on your WordPress site with a simple toggle switch. Protect your site from XML-RPC attacks and improve security.

== Description ==

**Simple Disable XML-RPC** is a lightweight, powerful WordPress plugin that gives you complete control over your site's XML-RPC functionality. Protect your WordPress site from brute force attacks, DDoS attempts, and other XML-RPC security vulnerabilities with just one click.

### 🔒 Why Disable XML-RPC?

XML-RPC is a remote communication protocol that allows external applications to interact with your WordPress site. While useful for some services, it's frequently exploited by attackers for:

* **Brute Force Attacks** - Automated password guessing attempts
* **DDoS Attacks** - Overwhelming your server with requests
* **Resource Exhaustion** - Slowing down your website
* **Pingback Vulnerabilities** - Exploiting pingback features

### ✨ Key Features

* **🎯 One-Click Control** - Modern toggle switch interface (NEW in v1.4.0)
* **🔐 Enhanced Security** - Block XML-RPC attacks instantly
* **⚡ Improved Performance** - Reduce server load and resource usage
* **🎨 Beautiful Admin Interface** - Clean, modern card-based design (NEW in v1.4.0)
* **🌐 Translation Ready** - Fully internationalized and translation-ready
* **📱 Mobile Responsive** - Settings page works perfectly on all devices
* **🧹 Clean Uninstall** - Removes all data when uninstalled
* **⚙️ Developer Friendly** - Well-coded, follows WordPress standards
* **🔄 Regular Updates** - Actively maintained and tested with latest WordPress versions
* **💯 Lightweight** - No bloat, minimal impact on your site

### 🆕 What's New in Version 1.4.0

* ✅ Modern toggle switch replaces old checkbox
* ✅ Beautiful card-based admin interface
* ✅ Enhanced security with proper sanitization
* ✅ Better code organization (OOP approach)
* ✅ Improved accessibility and UX
* ✅ Removes X-Pingback header when disabled
* ✅ Fixed activation redirect for bulk installations
* ✅ Better mobile responsive design

### 🎯 Perfect For

* Security-focused website owners
* Sites that don't use mobile apps or remote publishing
* Sites experiencing XML-RPC attacks
* Performance-conscious administrators
* Anyone wanting better control over WordPress features

### 🔧 How It Works

This plugin uses the native WordPress `xmlrpc_enabled` filter to safely disable XML-RPC without modifying core files. Simply activate the plugin, toggle the switch on the settings page, and you're protected!

### ⚠️ Important Note

Disabling XML-RPC may affect:
* WordPress mobile apps
* Jetpack (some features)
* Remote publishing tools
* Pingbacks and trackbacks
* Third-party services that rely on XML-RPC

Only disable XML-RPC if you don't use these features.

### 🤝 Contributing & Bug Reports

Bug reports and pull requests are welcome on [GitHub](https://github.com/WordPress-Satkhira-Community/simple-disable-xml-rpc). Help us make this plugin better!

### 💝 Support the Development

If you find this plugin helpful, please consider:
* ⭐ [Rating it 5 stars](https://wordpress.org/support/plugin/simple-disable-xml-rpc/reviews/)
* 🐛 [Reporting bugs](https://github.com/WordPress-Satkhira-Community/simple-disable-xml-rpc/issues)
* 💬 [Suggesting features](https://github.com/WordPress-Satkhira-Community/simple-disable-xml-rpc/issues)
* ☕ [Buying us a coffee](https://www.wpsatkhira.com/donate)

== Installation ==

### Automatic Installation (Recommended)

1. Log in to your WordPress admin panel
2. Navigate to **Plugins > Add New**
3. Search for **"Simple Disable XML-RPC"**
4. Click **"Install Now"** button
5. Click **"Activate"** button
6. You'll be redirected to **Settings > Disable XML-RPC**
7. Toggle the switch to enable/disable XML-RPC

### Manual Installation

1. Download the plugin zip file
2. Log in to your WordPress admin panel
3. Navigate to **Plugins > Add New > Upload Plugin**
4. Choose the downloaded zip file and click **"Install Now"**
5. Click **"Activate Plugin"**
6. Go to **Settings > Disable XML-RPC**
7. Toggle the switch to your preference

### FTP Installation

1. Download and extract the plugin zip file
2. Upload the `simple-disable-xml-rpc` folder to `/wp-content/plugins/` directory
3. Activate the plugin through the **Plugins** menu in WordPress
4. Configure settings at **Settings > Disable XML-RPC**

== Frequently Asked Questions ==

= What is XML-RPC and why should I disable it? =

XML-RPC is a remote procedure call protocol that allows external applications to communicate with your WordPress site. While it enables features like mobile apps and remote publishing, it's also a common target for:

* Brute force attacks
* DDoS attacks
* Server resource exhaustion
* Security vulnerabilities

If you don't use WordPress mobile apps, Jetpack, or remote publishing tools, it's recommended to disable XML-RPC for better security.

= Will this plugin break my site? =

No, this plugin safely disables XML-RPC using WordPress's native filter. However, it may affect:

* WordPress mobile apps
* Jetpack functionality
* Pingbacks and trackbacks
* Third-party services using XML-RPC API

Test after activation to ensure your required features still work.

= How do I know if XML-RPC is successfully disabled? =

There are several ways to verify:

**Method 1: WordPress Mobile App**
Try connecting with the official WordPress mobile app. You should see: "XML-RPC services are disabled on this site"

**Method 2: Online Validator**
Use the [XML-RPC Validator](https://xmlrpc.blog/) tool. When properly disabled, it will show an error message.


You should receive a response indicating XML-RPC is disabled.

= Does this plugin improve website performance? =

Yes! When XML-RPC is disabled, your server doesn't need to process XML-RPC requests, which can:

* Reduce server load
* Prevent resource exhaustion
* Speed up response times
* Save bandwidth

= Is this plugin compatible with other security plugins? =

Yes! Simple Disable XML-RPC works seamlessly with other security plugins like:

* Wordfence Security
* Sucuri Security
* iThemes Security
* All In One WP Security
* And more!

= What's the difference between disabling via .htaccess vs this plugin? =

**Plugin Method (Recommended):**
* Uses WordPress native filters
* Easier to manage
* No server configuration needed
* Can be toggled on/off easily
* Won't cause server errors

**.htaccess Method:**
* Requires manual file editing
* Can break if edited incorrectly
* Harder to reverse
* May cause conflicts

= Can I re-enable XML-RPC if needed? =

Absolutely! Just go to **Settings > Disable XML-RPC** and toggle the switch off. Changes take effect immediately.

= Does this work on WordPress multisite? =

Yes, the plugin works on both single WordPress installations and multisite networks. On multisite, it must be configured per-site.

= Will this plugin be updated regularly? =

Yes! We actively maintain this plugin and test it with every new WordPress release. Updates are pushed regularly to ensure compatibility and security.

= Where can I get support? =

* [WordPress.org Support Forum](https://wordpress.org/support/plugin/simple-disable-xml-rpc/)
* [GitHub Issues](https://github.com/WordPress-Satkhira-Community/simple-disable-xml-rpc/issues)
* [Plugin Documentation](https://www.wpsatkhira.com)

= How can I contribute to this plugin? =

We welcome contributions! You can:

* Submit bug reports on [GitHub](https://github.com/WordPress-Satkhira-Community/simple-disable-xml-rpc/issues)
* Create pull requests with improvements
* Translate the plugin into your language
* Leave a review and rating
* Suggest new features

== Screenshots ==

1. **Modern Settings Page** - Beautiful card-based interface with toggle switch
2. **Toggle Switch in Action** - Easy one-click enable/disable control
3. **XML-RPC Disabled Message** - What users see when XML-RPC is successfully disabled
4. **Information Section** - Helpful guidance about XML-RPC and security

== Changelog ==

= 1.4.0 (2025-11-09) =
**Major Update - UI Overhaul & Security Enhancements**

* 🎨 NEW: Modern toggle switch interface replacing checkboxes
* 🎨 NEW: Beautiful card-based admin design
* 🔒 IMPROVED: Enhanced security with proper sanitization callbacks
* 🔒 IMPROVED: Added X-Pingback header removal
* ⚡ IMPROVED: Better code organization with OOP structure
* ⚡ IMPROVED: Separated files for better maintainability
* 🐛 FIXED: Activation redirect issue with bulk plugin activation
* 🐛 FIXED: Consistent function prefixing
* ♿ IMPROVED: Better accessibility and mobile responsive design
* 🧹 NEW: Proper uninstall cleanup script
* 📚 IMPROVED: Better documentation and inline comments
* 🌐 IMPROVED: Enhanced translation support

= 1.3.5 (2025-04-20) =
* 🐛 Bug fixes
* ⚡ Performance improvements
* ✅ WordPress 6.8 compatibility tested

= 1.3.4 (2024-11-17) =
* 🐛 Bug fixes
* ⚡ Performance improvements
* ✅ WordPress 6.7 compatibility tested

= 1.3.3 (2024-07-17) =
* 🐛 Bug fixes
* ⚡ Performance improvements
* ✅ WordPress 6.6 compatibility tested

= 1.3.2 (2024-04-02) =
* 🐛 Bug fixes
* ⚡ Performance improvements
* ✅ WordPress 6.5 compatibility tested

= 1.3.1 (2024-03-23) =
* 🔒 Important security update
* 🐛 Bug fixes
* 🎨 Plugin live preview added

= 1.3.0 (2024-03-12) =
* ⚡ Performance improvements
* 🔒 Security enhancements

= 1.2.5 (2024-03-12) =
* 🔧 Plugin compatibility fixes

= 1.2.4 (2024-03-12) =
* 🐛 Bug fixes and improvements

= 1.2.3 (2024-03-11) =
* 🐛 Bug fixes and improvements

= 1.2.2 (2024-02-21) =
* 🐛 Bug fixes and improvements

= 1.2.1 (2024-01-31) =
* 📝 Settings description updated

= 1.2.0 (2024-01-31) =
* ✅ WordPress 6.4.3 compatibility
* 🐛 Bug fixes
* 📚 New FAQs added

= 1.1.0 =
* 🎯 Auto-redirect to settings after activation
* 🐛 Bug fixes

= 1.0.0 =
* 🎉 Initial release

== Upgrade Notice ==

= 1.4.0 =
Major update! New modern toggle switch interface, enhanced security, better code organization, and improved user experience. Highly recommended upgrade!

= 1.3.5 =
Bug fixes, performance improvements, and WordPress 6.8 compatibility. Recommended update.

= 1.3.1 =
Important security update. Please update immediately.

== Privacy Policy ==

Simple Disable XML-RPC does not:

* Collect any user data
* Store any personal information
* Make external API calls
* Use cookies or tracking
* Send data to third parties

The plugin only stores one setting in your WordPress database: whether XML-RPC is enabled or disabled.

== Support ==

Need help? We're here for you!

* 📖 [Documentation](https://www.wpsatkhira.com)
* 💬 [Support Forum](https://wordpress.org/support/plugin/simple-disable-xml-rpc/)
* 🐛 [Report Bugs](https://github.com/WordPress-Satkhira-Community/simple-disable-xml-rpc/issues)
* ⭐ [Rate Plugin](https://wordpress.org/support/plugin/simple-disable-xml-rpc/reviews/)

== Credits ==

Developed with ❤️ by [WordPress Satkhira Community](https://www.wpsatkhira.com)

**Contributors:**
* [wpdelower](https://profiles.wordpress.org/wpdelower/)
* [monarchwp23](https://profiles.wordpress.org/monarchwp23/)

Special thanks to all our users and contributors who help make this plugin better!