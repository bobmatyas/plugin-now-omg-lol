const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
    ...defaultConfig,
    entry: {
        'index': './src/index.js',
        'style': './src/style.css',
        'editor': './src/editor.css',
    },
}; 