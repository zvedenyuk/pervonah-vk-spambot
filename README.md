# Pervonah VK spambot
A spambot for vk.com that publishes your image in a first comment to every new post of a specified group or public page.

## Before you start
You need to create an app at VK's developer section. Open file `getstarted.php` in your browser to get the instructions. Also, if you want to bypass captcha you will need an Antigate API key.

## Config
Edit `config.php` and put there your VK access token, access secret and Antigate API key.

## Usage
Include `pervonah/pervonah.php`
```php
require_once("pervonah/pervonah.php");
```
and call the main function like this:
```php
pervonah(GROUP_ID,VK_PHOTO_ID);
```
It will scan a group page for new entries and post your image in comments to each new entry.

Normally, you want to use it in a cycle:
```php
// Repeat 60 times
for($i=0;$i<60;$i++){
	pervonah(98331381,"photo-85297897_377457569");
	usleep(1000000); // Wait for 1 second
}
```
Change the delay and the number of repeats to your preferences. Feel free to run your file by cron.
