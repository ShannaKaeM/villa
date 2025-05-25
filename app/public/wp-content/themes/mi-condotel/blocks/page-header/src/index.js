import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { 
    useBlockProps, 
    InspectorControls,
    MediaUpload,
    MediaUploadCheck,
    RichText
} from '@wordpress/block-editor';
import { 
    PanelBody, 
    Button, 
    RangeControl,
    SelectControl,
    TextControl
} from '@wordpress/components';

import metadata from '../block.json';

registerBlockType(metadata.name, {
    edit: ({ attributes, setAttributes }) => {
        const {
            title,
            subtitle,
            backgroundImage,
            minHeight,
            borderRadius,
            overlayOpacity,
            titleSize,
            titleWeight,
            titleColor,
            titleTransform,
            titleTracking,
            titleSpacing,
            subtitleSize,
            subtitleWeight,
            subtitleColor,
            subtitleTransform,
            subtitleTracking,
            backgroundPositionY
        } = attributes;

        const blockProps = useBlockProps({
            className: 'page-header-block alignfull',
            style: {
                backgroundImage: backgroundImage ? `url(${backgroundImage})` : 'none',
                minHeight: minHeight,
                backgroundPositionY: backgroundPositionY
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
                        
                        <TextControl
                            label={__('Minimum Height', 'miblocks')}
                            value={minHeight}
                            onChange={(value) => setAttributes({ minHeight: value })}
                            help={__('e.g., 400px, 50vh', 'miblocks')}
                        />
                        
                        <SelectControl
                            label={__('Border Radius', 'miblocks')}
                            value={borderRadius || 'lg'}
                            options={[
                                { label: __('None', 'miblocks'), value: 'none' },
                                { label: __('Extra Small', 'miblocks'), value: 'xs' },
                                { label: __('Small', 'miblocks'), value: 'sm' },
                                { label: __('Medium', 'miblocks'), value: 'md' },
                                { label: __('Large', 'miblocks'), value: 'lg' },
                                { label: __('Extra Large', 'miblocks'), value: 'xl' },
                                { label: __('2XL', 'miblocks'), value: '2xl' },
                                { label: __('3XL', 'miblocks'), value: '3xl' },
                                { label: __('4XL', 'miblocks'), value: '4xl' },
                                { label: __('Full/Pill', 'miblocks'), value: 'full' },
                            ]}
                            onChange={(value) => setAttributes({ borderRadius: value })}
                        />
                        
                        <SelectControl
                            label={__('Background Position Y', 'miblocks')}
                            value={backgroundPositionY || 'center'}
                            options={[
                                { label: __('Top', 'miblocks'), value: 'top' },
                                { label: __('Center', 'miblocks'), value: 'center' },
                                { label: __('Bottom', 'miblocks'), value: 'bottom' },
                                { label: __('25%', 'miblocks'), value: '25%' },
                                { label: __('75%', 'miblocks'), value: '75%' },
                            ]}
                            onChange={(value) => setAttributes({ backgroundPositionY: value })}
                        />
                        <RangeControl
                            label={__('Overlay Opacity', 'miblocks')}
                            value={overlayOpacity}
                            onChange={(value) => setAttributes({ overlayOpacity: value })}
                            min={0}
                            max={1}
                            step={0.1}
                        />
                    </PanelBody>
                    
                    <PanelBody title={__('Title Typography', 'miblocks')} initialOpen={false}>
                        <TextControl
                            label={__('Title', 'miblocks')}
                            value={title}
                            onChange={(value) => setAttributes({ title: value })}
                        />
                        
                        <SelectControl
                            label={__('Title Size', 'miblocks')}
                            value={titleSize || '4xl'}
                            options={[
                                { label: __('Extra Small (12px)', 'miblocks'), value: 'xs' },
                                { label: __('Small (14px)', 'miblocks'), value: 'sm' },
                                { label: __('Base (16px)', 'miblocks'), value: 'base' },
                                { label: __('Large (18px)', 'miblocks'), value: 'lg' },
                                { label: __('XL (20px)', 'miblocks'), value: 'xl' },
                                { label: __('2XL (24px)', 'miblocks'), value: '2xl' },
                                { label: __('3XL (30px)', 'miblocks'), value: '3xl' },
                                { label: __('4XL (36px)', 'miblocks'), value: '4xl' },
                                { label: __('5XL (48px)', 'miblocks'), value: '5xl' },
                            ]}
                            onChange={(value) => setAttributes({ titleSize: value })}
                        />
                        
                        <SelectControl
                            label={__('Title Weight', 'miblocks')}
                            value={titleWeight || 'bold'}
                            options={[
                                { label: __('Normal (400)', 'miblocks'), value: 'normal' },
                                { label: __('Medium (500)', 'miblocks'), value: 'medium' },
                                { label: __('Semibold (600)', 'miblocks'), value: 'semibold' },
                                { label: __('Bold (700)', 'miblocks'), value: 'bold' },
                            ]}
                            onChange={(value) => setAttributes({ titleWeight: value })}
                        />
                        
                        <SelectControl
                            label={__('Title Color', 'miblocks')}
                            value={titleColor || 'white'}
                            options={[
                                { label: __('White', 'miblocks'), value: 'white' },
                                { label: __('Black', 'miblocks'), value: 'black' },
                                { label: __('Primary', 'miblocks'), value: 'primary' },
                                { label: __('Primary Light', 'miblocks'), value: 'primary-light' },
                                { label: __('Primary Dark', 'miblocks'), value: 'primary-dark' },
                                { label: __('Secondary', 'miblocks'), value: 'secondary' },
                                { label: __('Secondary Light', 'miblocks'), value: 'secondary-light' },
                                { label: __('Emphasis', 'miblocks'), value: 'emphasis' },
                                { label: __('Neutral Lightest', 'miblocks'), value: 'neutral-lightest' },
                                { label: __('Neutral Light', 'miblocks'), value: 'neutral-light' },
                                { label: __('Base Light', 'miblocks'), value: 'base-light' },
                                { label: __('Base', 'miblocks'), value: 'base' },
                                { label: __('Base Dark', 'miblocks'), value: 'base-dark' },
                                { label: __('Base Darkest', 'miblocks'), value: 'base-darkest' },
                            ]}
                            onChange={(value) => setAttributes({ titleColor: value })}
                        />
                        
                        <SelectControl
                            label={__('Title Transform', 'miblocks')}
                            value={titleTransform || 'uppercase'}
                            options={[
                                { label: __('None', 'miblocks'), value: 'none' },
                                { label: __('Uppercase', 'miblocks'), value: 'uppercase' },
                                { label: __('Lowercase', 'miblocks'), value: 'lowercase' },
                                { label: __('Capitalize', 'miblocks'), value: 'capitalize' },
                            ]}
                            onChange={(value) => setAttributes({ titleTransform: value })}
                        />
                        
                        <SelectControl
                            label={__('Title Tracking', 'miblocks')}
                            value={titleTracking || 'tight'}
                            options={[
                                { label: __('Tighter (-0.05em)', 'miblocks'), value: 'tighter' },
                                { label: __('Tight (-0.025em)', 'miblocks'), value: 'tight' },
                                { label: __('Normal (0)', 'miblocks'), value: 'normal' },
                                { label: __('Wide (0.025em)', 'miblocks'), value: 'wide' },
                                { label: __('Wider (0.05em)', 'miblocks'), value: 'wider' },
                                { label: __('Widest (0.1em)', 'miblocks'), value: 'widest' },
                            ]}
                            onChange={(value) => setAttributes({ titleTracking: value })}
                        />
                        
                        <SelectControl
                            label={__('Title Bottom Spacing', 'miblocks')}
                            value={titleSpacing || '4'}
                            options={[
                                { label: __('None (0)', 'miblocks'), value: '0' },
                                { label: __('XS (0.25rem)', 'miblocks'), value: '1' },
                                { label: __('SM (0.5rem)', 'miblocks'), value: '2' },
                                { label: __('MD (0.75rem)', 'miblocks'), value: '3' },
                                { label: __('LG (1rem)', 'miblocks'), value: '4' },
                                { label: __('XL (1.25rem)', 'miblocks'), value: '5' },
                                { label: __('2XL (1.5rem)', 'miblocks'), value: '6' },
                                { label: __('3XL (2rem)', 'miblocks'), value: '8' },
                                { label: __('4XL (2.5rem)', 'miblocks'), value: '10' },
                            ]}
                            onChange={(value) => setAttributes({ titleSpacing: value })}
                        />
                    </PanelBody>
                    
                    <PanelBody title={__('Subtitle Typography', 'miblocks')} initialOpen={false}>
                        <TextControl
                            label={__('Subtitle', 'miblocks')}
                            value={subtitle}
                            onChange={(value) => setAttributes({ subtitle: value })}
                        />
                        
                        <SelectControl
                            label={__('Subtitle Size', 'miblocks')}
                            value={subtitleSize || 'xl'}
                            options={[
                                { label: __('Extra Small (12px)', 'miblocks'), value: 'xs' },
                                { label: __('Small (14px)', 'miblocks'), value: 'sm' },
                                { label: __('Base (16px)', 'miblocks'), value: 'base' },
                                { label: __('Large (18px)', 'miblocks'), value: 'lg' },
                                { label: __('XL (20px)', 'miblocks'), value: 'xl' },
                                { label: __('2XL (24px)', 'miblocks'), value: '2xl' },
                                { label: __('3XL (30px)', 'miblocks'), value: '3xl' },
                                { label: __('4XL (36px)', 'miblocks'), value: '4xl' },
                                { label: __('5XL (48px)', 'miblocks'), value: '5xl' },
                            ]}
                            onChange={(value) => setAttributes({ subtitleSize: value })}
                        />
                        
                        <SelectControl
                            label={__('Subtitle Weight', 'miblocks')}
                            value={subtitleWeight || 'normal'}
                            options={[
                                { label: __('Normal (400)', 'miblocks'), value: 'normal' },
                                { label: __('Medium (500)', 'miblocks'), value: 'medium' },
                                { label: __('Semibold (600)', 'miblocks'), value: 'semibold' },
                                { label: __('Bold (700)', 'miblocks'), value: 'bold' },
                            ]}
                            onChange={(value) => setAttributes({ subtitleWeight: value })}
                        />
                        
                        <SelectControl
                            label={__('Subtitle Color', 'miblocks')}
                            value={subtitleColor || 'base-light'}
                            options={[
                                { label: __('White', 'miblocks'), value: 'white' },
                                { label: __('Black', 'miblocks'), value: 'black' },
                                { label: __('Primary', 'miblocks'), value: 'primary' },
                                { label: __('Primary Light', 'miblocks'), value: 'primary-light' },
                                { label: __('Primary Dark', 'miblocks'), value: 'primary-dark' },
                                { label: __('Secondary', 'miblocks'), value: 'secondary' },
                                { label: __('Secondary Light', 'miblocks'), value: 'secondary-light' },
                                { label: __('Emphasis', 'miblocks'), value: 'emphasis' },
                                { label: __('Neutral Lightest', 'miblocks'), value: 'neutral-lightest' },
                                { label: __('Neutral Light', 'miblocks'), value: 'neutral-light' },
                                { label: __('Base Light', 'miblocks'), value: 'base-light' },
                                { label: __('Base', 'miblocks'), value: 'base' },
                                { label: __('Base Dark', 'miblocks'), value: 'base-dark' },
                                { label: __('Base Darkest', 'miblocks'), value: 'base-darkest' },
                            ]}
                            onChange={(value) => setAttributes({ subtitleColor: value })}
                        />
                        
                        <SelectControl
                            label={__('Subtitle Transform', 'miblocks')}
                            value={subtitleTransform || 'none'}
                            options={[
                                { label: __('None', 'miblocks'), value: 'none' },
                                { label: __('Uppercase', 'miblocks'), value: 'uppercase' },
                                { label: __('Lowercase', 'miblocks'), value: 'lowercase' },
                                { label: __('Capitalize', 'miblocks'), value: 'capitalize' },
                            ]}
                            onChange={(value) => setAttributes({ subtitleTransform: value })}
                        />
                        
                        <SelectControl
                            label={__('Subtitle Tracking', 'miblocks')}
                            value={subtitleTracking || 'normal'}
                            options={[
                                { label: __('Tighter (-0.05em)', 'miblocks'), value: 'tighter' },
                                { label: __('Tight (-0.025em)', 'miblocks'), value: 'tight' },
                                { label: __('Normal (0)', 'miblocks'), value: 'normal' },
                                { label: __('Wide (0.025em)', 'miblocks'), value: 'wide' },
                                { label: __('Wider (0.05em)', 'miblocks'), value: 'wider' },
                                { label: __('Widest (0.1em)', 'miblocks'), value: 'widest' },
                            ]}
                            onChange={(value) => setAttributes({ subtitleTracking: value })}
                        />
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    <div className="page-header-overlay" style={{ opacity: overlayOpacity }}></div>
                    <div className="page-header-content">
                        <div className="page-header-inner">
                            <RichText
                                tagName="h1"
                                className="page-header-title"
                                value={title}
                                onChange={(value) => setAttributes({ title: value })}
                                placeholder={__('Page Title', 'miblocks')}
                                style={{
                                    textTransform: titleTransform,
                                    letterSpacing: titleTracking === 'tighter' ? '-0.05em' : titleTracking === 'tight' ? '-0.025em' : titleTracking === 'wide' ? '0.025em' : titleTracking === 'wider' ? '0.05em' : titleTracking === 'widest' ? '0.1em' : '0',
                                    marginBottom: titleSpacing === '0' ? '0' : titleSpacing === '1' ? '0.25rem' : titleSpacing === '2' ? '0.5rem' : titleSpacing === '3' ? '0.75rem' : titleSpacing === '4' ? '1rem' : titleSpacing === '5' ? '1.25rem' : titleSpacing === '6' ? '1.5rem' : titleSpacing === '8' ? '2rem' : titleSpacing === '10' ? '2.5rem' : '0'
                                }}
                            />
                            <RichText
                                tagName="p"
                                className="page-header-subtitle"
                                value={subtitle}
                                onChange={(value) => setAttributes({ subtitle: value })}
                                placeholder={__('Add a description or subtitle for this page', 'miblocks')}
                                style={{
                                    textTransform: subtitleTransform,
                                    letterSpacing: subtitleTracking === 'tighter' ? '-0.05em' : subtitleTracking === 'tight' ? '-0.025em' : subtitleTracking === 'wide' ? '0.025em' : subtitleTracking === 'wider' ? '0.05em' : subtitleTracking === 'widest' ? '0.1em' : '0'
                                }}
                            />
                        </div>
                    </div>
                </div>
            </>
        );
    },
    save: () => null // Dynamic block, rendered by PHP
});
