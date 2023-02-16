# local_softwarewarning

This plugin allows showing configurable banners to users with specific configurable browsers and versions.

![Screenshot of Moodle page with Banner saying "Your browser is not supported"](https://user-images.githubusercontent.com/45795270/167505161-9725045c-4401-4308-a342-0b9a63e99ec1.png)

## Installation
This Plugin should go into `local/softwarewarning`.

## Configuration
The displaying of banners is initially completely disabled,
because first a browscap.ini must be download and configured in PHP. To do this, please:
- Set browscappath in the general settings (`Local Plugins > Browsersupport Warning > General settings`) to a path accessible to moodle, for example `<moodledata>/browscap.ini`
- Run local_softwarewarning\task\download_browscap task (this downloads browscap.ini to the specified path)
- Set `browscap=<moodledata>/browscap.ini` in the PHP ini files. ([Docs](https://www.php.net/manual/de/misc.configuration.php#ini.browscap))
- Make sure PHP has reloaded the configuration.
- Visit `Site Administration > Plugins > Local Plugins > Browsersupport Warning > Testing page`

If your Browser is displayed correctly, you can
- configure the title, possible link, color and closableness of banners in the general settings.
- configure the browser constraints (for what browser (and version) which banner should be displayed) in `Site Administration > Plugins > Local Plugins > Browsersupport Warning > Set Browserconstraints`
- test your configured banners on the testing page
- test your configured browser constraints on the testing page by using a user agent switcher

If everything works to your liking, you can enable the banners in the general settings. 
