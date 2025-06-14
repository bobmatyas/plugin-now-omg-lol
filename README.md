# OMG.lol Now Page WordPress Plugin

A WordPress plugin that allows you to display OMG.lol now pages on your WordPress site using either a block or shortcode.

## Features

- Display OMG.lol now pages using a Gutenberg block or shortcode
- Caching support to prevent excessive API calls
- Admin settings page to configure default username
- Markdown support for content formatting
- Responsive design

## Installation

1. Download the plugin files
2. Upload the plugin folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to Settings > OMG.lol Now to configure your default username

## Usage

### Block Editor

1. Add a new block
2. Search for "OMG.lol Now Page"
3. Configure the username (optional - will use default if left empty)

### Shortcode

Use the following shortcode in your posts or pages:

```
[omg_lol_now]
```

Or specify a different username:

```
[omg_lol_now username="foobar"]
```

## Development

### Prerequisites

- Node.js
- npm

### Setup

1. Clone the repository
2. Run `npm install` to install dependencies
3. Run `npm run build` to build the block
4. Run `npm run start` for development

## License

This plugin is licensed under the GPL v2 or later.

## Credits

Created by [Your Name] 