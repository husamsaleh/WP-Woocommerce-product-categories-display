# WPWC Categories Plugin

The WPWC Categories Plugin is a WordPress plugin designed to display WooCommerce product categories with customizable settings and shortcode support. This plugin allows you to easily integrate and style product categories on your WordPress site.

## Features

- **Customizable Settings**: Configure settings such as image size, image radius, space between images, font size, text color, and background color directly from the WordPress admin panel.
- **Shortcode Support**: Easily display product categories anywhere on your site using the `[wpwc_categories]` shortcode.
- **Responsive Design**: Includes responsive styles to ensure product categories look great on all devices.

## Installation

1. Download the plugin from the GitHub repository.
2. Upload the `my-project-plugin.php` file to the `/wp-content/plugins/` directory of your WordPress site.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

### Shortcode

You can display the product categories by using the shortcode `[wpwc_categories]` in your posts, pages, or widget areas. Here are some attributes you can use with the shortcode:

- `limit`: The number of categories to display. Default is 10.

Example:
[wpwc_categories limit="5"]


### Settings

Navigate to the 'WPWC Categories' settings page in the WordPress admin menu to customize the following options:

- Image Size (px)
- Image Radius (%)
- Space Between Images (px)
- Font Size (px)
- Text Color
- Background Color
- Order By (Name, ID, Count)
- Order (Ascending, Descending)

## Screenshots

![Categories Display](images/categories-display.PNG)

## Contributing

Contributions are welcome. Please fork the repository and submit a pull request with your changes.

## License

This plugin is open-sourced software licensed under the [GPLv2](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html) and is free to use.
