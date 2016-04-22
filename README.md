Pimcore Dependency Injection Plugin
===================================
 
## Installation

Create simply install the plugin. This should create a folder under the Pimcore website directory
called config (if it doesn't already exist), and create a `container.php` file. From there, just use
[PHP-DI](http://php-di.org/doc/) as normal!

## Configuration

Container configuration is stored in the Pimcore website directory. The plugin will check if the 
base container configuration file (`website/config/container.php`) exists, and if it does not, it 
will create it for you upon plugin installation (it will not automatically create it at any other
point, if it doesn't exist, the plugin will assume it is uninstalled).

You can also specify environment-specific configuration by placing another container configuration
file in the configuration folder, like: `website/config/container.<ENV>.php`. The environment used
is pulled from Pimcore's system settings. An example is `website/config/container.local.php`.

A parameters file can also be created, the plugin will not create it if it doesn't exist though.
This file will go in the same folder, and is called `website/config/parameters.php`.

See the [PHP-DI documentation](http://php-di.org/doc/) for more information about configuration.

## License

MIT
