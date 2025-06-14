import { registerBlockType } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { TextControl, PanelBody } from '@wordpress/components';
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
    },
    edit: function Edit({ attributes, setAttributes }) {
        const [nowContent, setNowContent] = useState('');
        const [isLoading, setIsLoading] = useState(false);
        const [error, setError] = useState(null);

        useEffect(() => {
            if (!attributes.username) {
                setNowContent('');
                return;
            }

            const fetchNowContent = async () => {
                setIsLoading(true);
                setError(null);
                try {
                    const data = await apiFetch({
                        path: `/omg-lol-now/v1/now/${encodeURIComponent(attributes.username)}`,
                    });
                    
                    // Debug the raw content
                    console.log('Raw content:', data.content);
                    
                    // Parse markdown and sanitize HTML
                    const parsedContent = marked(data.content);
                    console.log('Parsed markdown:', parsedContent);
                    
                    const sanitizedContent = sanitizeHtml(parsedContent);
                    console.log('Sanitized HTML:', sanitizedContent);
                    
                    setNowContent(sanitizedContent);
                } catch (err) {
                    console.error('Error fetching now page:', err);
                    setError(err.message || __('Failed to fetch now page content. Please check if the username is correct and try again.', 'omg-lol-now'));
                } finally {
                    setIsLoading(false);
                }
            };

            fetchNowContent();
        }, [attributes.username]);

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('OMG.lol Now Page Settings', 'omg-lol-now')}>
                        <TextControl
                            label={__('OMG.lol Username', 'omg-lol-now')}
                            value={attributes.username}
                            onChange={(value) => setAttributes({ username: value })}
                            help={__('Enter the OMG.lol username to display.', 'omg-lol-now')}
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
                                <p>{__('Please enter an OMG.lol username in the block settings.', 'omg-lol-now')}</p>
                            </div>
                        </div>
                    )}
                    {nowContent && (
                        <div 
                            className="omg-lol-now-content markdown-body"
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