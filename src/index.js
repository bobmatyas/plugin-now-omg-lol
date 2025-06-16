import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { TextControl, PanelBody, Button, RangeControl, ColorPicker, borderRadius } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { marked } from 'marked';
import './style.css';

// Configure marked options
marked.setOptions({
    breaks: true, // Convert line breaks to <br>
    gfm: true, // GitHub Flavored Markdown
    headerIds: false, // Disable header IDs for security
    mangle: false, // Disable mangling for security
    sanitize: false, // We'll sanitize the HTML after parsing
});

// Helper function to sanitize HTML
const sanitizeHtml = (html) => {
    const allowedTags = [
        'p', 'br', 'strong', 'em', 'a', 'ul', 'ol', 'li',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote',
        'code', 'pre', 'img', 'hr'
    ];
    const allowedAttributes = {
        'a': ['href', 'title', 'target'],
        'img': ['src', 'alt', 'title', 'width', 'height'],
    };

    const div = document.createElement('div');
    div.innerHTML = html;

    // Remove all elements that aren't in allowedTags
    const elements = div.getElementsByTagName('*');
    for (let i = elements.length - 1; i >= 0; i--) {
        const element = elements[i];
        if (!allowedTags.includes(element.tagName.toLowerCase())) {
            element.parentNode.removeChild(element);
            continue;
        }

        // Remove all attributes that aren't in allowedAttributes
        const attributes = element.attributes;
        for (let j = attributes.length - 1; j >= 0; j--) {
            const attribute = attributes[j];
            const tagName = element.tagName.toLowerCase();
            if (!allowedAttributes[tagName] || !allowedAttributes[tagName].includes(attribute.name)) {
                element.removeAttribute(attribute.name);
            }
        }
    }

    return div.innerHTML;
};

registerBlockType('omg-lol-now/now-page', {
    title: __('OMG.lol Now Page', 'omg-lol-now'),
    description: __('Display an OMG.lol now page.', 'omg-lol-now'),
    category: 'widgets',
    icon: 'clock',
    attributes: {
        username: {
            type: 'string',
            default: '',
        },
        backgroundColor: {
            type: 'string',
            default: '#ffffff',
        },
        margin: {
            type: 'number',
            default: 0,
        },
        padding: {
            type: 'number',
            default: 20,
        },
        borderRadius: {
            type: 'number',
            default: 0,
        },
    },
    edit: function Edit({ attributes, setAttributes }) {
        const [nowContent, setNowContent] = useState('');
        const [isLoading, setIsLoading] = useState(false);
        const [error, setError] = useState(null);
        const [tempUsername, setTempUsername] = useState(attributes.username);

        const fetchNowContent = async (username) => {
            setIsLoading(true);
            setError(null);
            try {
                const data = await apiFetch({
                    path: `/omg-lol-now/v1/now/${encodeURIComponent(username)}`,
                });
                
            
                // Parse markdown and sanitize HTML
                const parsedContent = marked(data.content);
                console.log(parsedContent);
                setNowContent(parsedContent);
            } catch (err) {
                console.error('Error fetching now page:', err);
                setError(err.message || __('Failed to fetch now page content. Please check if the username is correct and try again.', 'omg-lol-now'));
            } finally {
                setIsLoading(false);
            }
        };

        // Only fetch content when username changes in attributes
        useEffect(() => {
            if (attributes.username) {
                fetchNowContent(attributes.username);
            } else {
                setNowContent('');
            }
        }, [attributes.username]);

        const handleSetUsername = () => {
            setAttributes({ username: tempUsername });
        };

        const containerStyle = {
            backgroundColor: attributes.backgroundColor,
            margin: `${attributes.margin}px`,
            padding: `${attributes.padding}px`,
            borderRadius: `${attributes.borderRadius}px`,
        };

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('OMG.lol Now Page Settings', 'omg-lol-now')}>
                        <TextControl
                            label={__('OMG.lol Username', 'omg-lol-now')}
                            value={tempUsername}
                            onChange={(value) => setTempUsername(value)}
                            help={__('Enter the OMG.lol username to display.', 'omg-lol-now')}
							__next40pxDefaultSize={ true }
							__nextHasNoMarginBottom={ true }
						/>
                        <Button
                            variant="primary"
                            onClick={handleSetUsername}
                            style={{ marginTop: '10px' }}
                        >
                            {__('Set Username', 'omg-lol-now')}
                        </Button>
                    </PanelBody>
                    <PanelBody title={__('Style Settings', 'omg-lol-now')} initialOpen={false}>
                        <div style={{ marginBottom: '16px' }}>
                            <label style={{ display: 'block', marginBottom: '8px' }}>
                                {__('Background Color', 'omg-lol-now')}
                            </label>
                            <ColorPicker
                                color={attributes.backgroundColor}
                                onChangeComplete={(value) => setAttributes({ backgroundColor: value.hex })}
                                disableAlpha
                            />
                        </div>
                        <RangeControl
                            label={__('Margin (px)', 'omg-lol-now')}
                            value={attributes.margin}
                            onChange={(value) => setAttributes({ margin: value })}
                            min={0}
                            max={50}
							__next40pxDefaultSize={ true }
							__nextHasNoMarginBottom={ true }
                        />
                        <RangeControl
                            label={__('Padding (px)', 'omg-lol-now')}
                            value={attributes.padding}
                            onChange={(value) => setAttributes({ padding: value })}
                            min={0}
                            max={50}
							__next40pxDefaultSize={ true }
							__nextHasNoMarginBottom={ true }
                        />
						<RangeControl
                            label={__('Border Radius (px)', 'omg-lol-now')}
                            value={attributes.borderRadius}
                            onChange={(value) => setAttributes({ borderRadius: value })}
                            min={0}
                            max={50}
							__next40pxDefaultSize={ true }
							__nextHasNoMarginBottom={ true }
                        />
                    </PanelBody>
                </InspectorControls>
                <div className="wp-block-omg-lol-now">
                    {isLoading && <p>{__('Loading now page content...', 'omg-lol-now')}</p>}
                    {error && (
                        <div className="components-notice is-error">
                            <div className="components-notice__content">
                                <p>{error}</p>
                            </div>
                        </div>
                    )}
                    {!attributes.username && (
                        <div className="components-notice is-info">
                            <div className="components-notice__content">
                                <p>{__('Please enter an OMG.lol username and click "Set Username" in the block settings.', 'omg-lol-now')}</p>
                            </div>
                        </div>
                    )}
                    {nowContent && (
                        <div 
                            className="cows"
                            style={containerStyle}
                            dangerouslySetInnerHTML={{ __html: nowContent }}
                        />
                    )}
                </div>
            </>
        );
    },
    save: function Save() {
        return null; // Dynamic block, render handled by PHP
    },
}); 