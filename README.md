# Now Page via OMG.lol Connector

**Display your OMG.lol Now Page on your WordPress site using blocks or shortcodes.**

Whether you're letting visitors know what you're currently working on, maintaining a personal status board, or just showing off your current vibe, this plugin makes it easy to embed your OMG.lol Now Page content directly into your WordPress site.

---

## Documentation

### Features

- **Block Editor Support** - Add your Now Page using the Gutenberg block editor with a user-friendly interface
- **Shortcode Support** - Embed your Now Page anywhere using simple shortcodes
- **Smart Caching** - Built-in 1-hour caching to reduce API calls and improve performance
- **Easy Configuration** - Simple settings page to define your default username
- **Icon Support** - Automatically converts OMG.lol icon aliases to Font Awesome icons
- **Markdown Support** - Converts markdown content from OMG.lol to properly formatted HTML
- **Flexible Username Override** - Use different usernames for different pages or posts

---

### Requirements

- **WordPress**: 6.8 or higher
- **PHP**: 8.2 or higher
- **OMG.lol Account**: You'll need an active OMG.lol account with a Now Page set up

---

### Installation

#### Method 1: WordPress Admin (Recommended)

1. Navigate to **Plugins > Add New** in your WordPress admin dashboard
2. Search for "Now Page via OMG.lol Connector"
3. Click **Install Now**
4. Click **Activate** after installation completes

#### Method 2: Manual Installation

1. Download the plugin files
2. Upload the plugin folder to `/wp-content/plugins/now-page-via-omg-lol-connector/`
3. Navigate to **Plugins > Installed Plugins** in your WordPress admin
4. Find "Now Page via OMG.lol Connector" and click **Activate**

---

### Configuration

After activating the plugin, configure your default OMG.lol username:

1. Navigate to **Settings > Now via OMG.lol** in your WordPress admin dashboard
2. Enter your OMG.lol username (without the @ symbol) in the **OMG.lol Username** field
3. Click **Save Changes**

This default username will be used whenever you don't specify a different username in a block or shortcode.

#### Clearing the Cache

If you've updated your Now Page on OMG.lol and want to see the changes immediately:

1. Go to **Settings > Now via OMG.lol**
2. Click the **Clear Cache** button
3. The cache will be cleared and fresh content will be fetched on the next page load

**Note**: The cache automatically refreshes every hour, so manual clearing is usually not necessary.

---

### Usage

#### Using the Block Editor

The easiest way to add your Now Page is through the WordPress block editor:

1. **Add a Block**: Click the **+** button in the editor or press `/` to open the block inserter
2. **Search for the Block**: Type "OMG.lol Now Page" in the search box
3. **Insert the Block**: Click on the "OMG.lol Now Page" block to add it to your page
4. **Configure (Optional)**: 
   - If you want to use a different username than your default, enter it in the block settings
   - The block will use your default username from settings if left blank
5. **Publish**: Save or publish your page/post

The block will automatically fetch and display your Now Page content from OMG.lol.

#### Using Shortcodes

You can embed your Now Page anywhere that supports shortcodes, including:

- Posts and pages
- Widgets (using a Text widget)
- Theme templates
- Other plugins that support shortcodes

##### Basic Shortcode

Use the default username from your settings:

```
[omg_lol_now]
```

##### Shortcode with Custom Username

Override the default username for a specific instance:

```
[omg_lol_now username="foobar"]
```

Replace `foobar` with the OMG.lol username you want to display.

##### Examples

**In a Post:**
```
Check out what I'm up to:

[omg_lol_now]
```

**Multiple Now Pages:**
```
Here's what I'm doing: [omg_lol_now]

And here's what my friend is up to: [omg_lol_now username="friend"]
```

---

### Caching

The plugin uses WordPress transients to cache Now Page content for **1 hour**. This provides several benefits:

- **Performance**: Reduces API calls to OMG.lol, making your site faster
- **Reliability**: Protects against temporary API issues
- **Efficiency**: Reduces bandwidth usage

#### How Caching Works

1. When a Now Page is requested, the plugin first checks the cache
2. If cached content exists and is less than 1 hour old, it's returned immediately
3. If the cache is empty or expired, fresh content is fetched from OMG.lol
4. The new content is cached for the next hour

#### Manual Cache Clearing

If you need to see updated content immediately after changing your Now Page on OMG.lol:

1. Go to **Settings > Now via OMG.lol**
2. Click **Clear Cache**
3. Refresh your page to see the updated content

---

### Troubleshooting

### Now Page Not Displaying

**Problem**: The Now Page content doesn't appear on your site.

**Solutions**:
1. **Check Username**: Verify your OMG.lol username is correct in Settings
2. **Verify Now Page Exists**: Make sure you have a Now Page set up on OMG.lol
3. **Clear Cache**: Try clearing the cache in Settings
4. **Check for Errors**: Look for error messages in the content area

#### "Please configure the OMG.lol username" Message

**Problem**: You see a message asking you to configure the username.

**Solution**: 
1. Go to **Settings > Now via OMG.lol**
2. Enter your OMG.lol username
3. Click **Save Changes**

#### Icons Not Displaying

**Problem**: Font Awesome icons aren't showing up.

**Solutions**:
1. The plugin automatically loads Font Awesome when needed
2. If icons still don't appear, check if another plugin or theme is conflicting
3. Clear your browser cache and WordPress cache

#### Content Looks Different on WordPress vs OMG.lol

**Problem**: The formatting doesn't match exactly.

**Explanation**: The plugin processes the content to:
- Remove OMG.lol-specific placeholders (like profile picture placeholders)
- Convert markdown to HTML
- Convert icon aliases to Font Awesome icons

This is expected behavior and ensures the content displays properly in WordPress.

---

### Frequently Asked Questions

#### What is a Now Page?

A Now Page is a simple way to share what you're currently working on, thinking about, or doing. It's like a status update that lives on your website. The concept was popularized by [Derek Sivers](https://sive.rs/now).

#### Do I need an OMG.lol account?

Yes, you'll need an active OMG.lol account and a Now Page set up there. Visit [omg.lol](https://omg.lol) to get started if you don't have an account yet.

#### How often does the content update?

The plugin caches content for 1 hour to improve performance. After an hour, it will automatically fetch fresh content from your OMG.lol Now Page. You can also manually clear the cache at any time.

#### Can I use different usernames for different pages?

Yes! You can override the default username in both the block editor and shortcode. This allows you to display different Now Pages on different parts of your site.

#### Does this plugin send any data to OMG.lol?

No. The plugin only retrieves (fetches) content from OMG.lol. No visitor or user data from your WordPress site is sent to OMG.lol.

#### Can I customize the styling?

The plugin includes basic styling that matches common markdown formatting. You can customize the appearance using CSS by targeting the `.omg-lol-now-content` or `.wp-block-omg-lol-now` classes.

#### What happens if OMG.lol is down?

If the OMG.lol API is unavailable, the plugin will display an error message. The cached content will continue to display until the cache expires, providing some resilience during outages.

#### External Services

This plugin connects to OMG.lol to retrieve the contents of your OMG.lol Now Page. No visitor or user data is sent to OMG.lol. The plugin only retrieves the content.

OMG.lol has their own [terms of service](https://home.omg.lol/info/legal#terms-of-service) and [privacy policy](https://home.omg.lol/info/legal#privacy-policy).

### Getting Help

- Check the [Troubleshooting](#troubleshooting) section above
- Review the [Frequently Asked Questions](#frequently-asked-questions)
- Visit the plugin's support page (if available)
- Contact the plugin author through their website