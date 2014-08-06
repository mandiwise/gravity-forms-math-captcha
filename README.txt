=== Gravity Forms Math Captcha ===
Contributors: mandiwise
Tags: gravity forms, spam, math, captcha
Requires at least: 3.7
Tested up to: 3.9.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a simple, non-image-based math captcha field to Gravity Forms.

== Description ==

Gravity Forms Math Captcha is a light-weight plugin that allows you to add a simple, non-image-based math captcha field to your Gravity Forms.

When you add a Math Captcha field to your form, a simple addition or subtraction question will be randomly generated whenever the form is loaded. You can choose to display the math question as numerals and math symbols, all words, or a mix of both.

**Please Note**

Simple math captcha validation isn't impervious to spam bots. However, in cases where image-based captcha cannot be used (e.g. due to accessibility concerns, etc.) this plugin can help add a bit of extra protection to your forms.

== Installation ==

1. Extract the `gravity-forms-math-captcha-master.zip` and remove `-master` from the extracted directory name
2. Upload the `gravity-forms-math-captcha` folder and its contents to the `/wp-content/plugins/` directory
3. Ensure that you have an up-to-date version of Gravity Forms already installed and activated
4. Activate the Gravity Forms Math Captcha plugin through the 'Plugins' menu in WordPress

Upon successful activation, you'll find the Math Captcha field under the "Advanced Fields" tab in the Gravity Forms editor.

== Screenshots ==

1. Field options for the math captcha field in the Gravity Forms editor
2. Math captcha added to a form and displayed on the front-end

== Changelog ==

= 1.0.1 =
* Fix bug where "0" was not accepted as a correct answer.

= 1.0 =
* Initial Release
