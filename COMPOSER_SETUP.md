# Composer Setup for OMG.lol Now Plugin

This plugin uses Composer to manage PHP dependencies with namespace isolation to prevent conflicts with other plugins.

## Setup Instructions

### 1. Install Composer Dependencies

Run the following command in the plugin root directory:

```bash
composer install
```

### 2. Generate Namespaced Vendor Files

Run Strauss to create namespace-isolated versions of the dependencies:

```bash
composer strauss
```

This will create the `includes/vendor/` directory with namespaced versions of Parsedown.

### 3. For Production/WordPress.org Submission

Before deploying or submitting to WordPress.org, run:

```bash
composer install --no-dev
composer strauss
```

This ensures only production dependencies are included.

## What This Does

- **Namespace Isolation**: Uses Strauss to prefix all vendor classes with `OMG_LOL_Now\Vendor\`
- **Conflict Prevention**: Prevents conflicts with other plugins that might use the same libraries
- **WordPress.org Compliance**: Meets WordPress.org requirements for library handling

## Files Generated

After running the setup commands, you'll have:
- `includes/vendor/autoload.php` - Composer autoloader
- `includes/vendor/parsedown/` - Namespaced Parsedown library
- Other vendor files as needed

## Troubleshooting

If you encounter issues:

1. Make sure Composer is installed on your system
2. Run `composer install` first, then `composer strauss`
3. Check that the `includes/vendor/autoload.php` file exists
4. Verify the plugin loads without errors

## Development

For development, you can run:

```bash
composer install
composer strauss
```

This includes development dependencies like Strauss itself. 