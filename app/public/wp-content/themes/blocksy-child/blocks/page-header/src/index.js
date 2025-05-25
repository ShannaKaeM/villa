import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import {
    useBlockProps,
    RichText,
    InspectorControls,
    MediaUpload,
    MediaUploadCheck
} from '@wordpress/block-editor';
import {
    PanelBody,
    RangeControl,
    Button,
    TextControl
} from '@wordpress/components';

import metadata from '../block.json';

registerBlockType(metadata.name, {
    edit: ({ attributes, setAttributes }) => {
        const {
            title,
            subtitle,
            backgroundImage,
            overlayOpacity,
            minHeight
        } = attributes;

        const blockProps = useBlockProps({
            className: 'page-header-block alignfull',
            style: {
                backgroundImage: backgroundImage ? `url(${backgroundImage})` : 'none',
                minHeight: minHeight
            }
        });

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Background Settings', 'miblocks')}>
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={(media) => setAttributes({ backgroundImage: media.url })}
                                allowedTypes={['image']}
                                value={backgroundImage}
                                render={({ open }) => (
                                    <div>
                                        {backgroundImage && (
                                            <img
                                                src={backgroundImage}
                                                alt="Background"
                                                style={{ width: '100%', marginBottom: '10px' }}
                                            />
                                        )}
                                        <Button
                                            onClick={open}
                                            variant="primary"
                                            style={{ marginBottom: '10px' }}
                                        >
                                            {backgroundImage ? __('Change Image', 'miblocks') : __('Select Image', 'miblocks')}
                                        </Button>
                                        {backgroundImage && (
                                            <Button
                                                onClick={() => setAttributes({ backgroundImage: '' })}
                                                variant="secondary"
                                                isDestructive
                                            >
                                                {__('Remove Image', 'miblocks')}
                                            </Button>
                                        )}
                                    </div>
                                )}
                            />
                        </MediaUploadCheck>
                        
                        <RangeControl
                            label={__('Overlay Opacity', 'miblocks')}
                            value={overlayOpacity}
                            onChange={(value) => setAttributes({ overlayOpacity: value })}
                            min={0}
                            max={1}
                            step={0.1}
                        />
                        
                        <TextControl
                            label={__('Minimum Height', 'miblocks')}
                            value={minHeight}
                            onChange={(value) => setAttributes({ minHeight: value })}
                            help={__('e.g., 400px, 50vh', 'miblocks')}
                        />
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    <div 
                        className="page-header-overlay" 
                        style={{ opacity: overlayOpacity }}
                    ></div>
                    <div className="page-header-content">
                        <div className="page-header-inner">
                            <RichText
                                tagName="h1"
                                className="page-header-title"
                                value={title}
                                onChange={(value) => setAttributes({ title: value })}
                                placeholder={__('Page Title', 'miblocks')}
                            />
                            <RichText
                                tagName="p"
                                className="page-header-subtitle"
                                value={subtitle}
                                onChange={(value) => setAttributes({ subtitle: value })}
                                placeholder={__('Add a description or subtitle for this page', 'miblocks')}
                            />
                        </div>
                    </div>
                </div>
            </>
        );
    },
    save: () => null // Dynamic block, rendered by PHP
});
