import { registerBlockType } from '@wordpress/blocks';
import { 
    InspectorControls, 
    RichText,
    useBlockProps,
    useSetting
} from '@wordpress/block-editor';
import { 
    PanelBody, 
    SelectControl
} from '@wordpress/components';
import { TypographyControls, SpacingControls, BackgroundControls } from '../../components';

registerBlockType('mi-condotel/hero', {
    edit: ({ attributes, setAttributes }) => {
        const {
            backgroundImage,
            backgroundImageId,
            overlayColor,
            overlayOpacity,
            title,
            titleColor,
            titleSize,
            titleWeight,
            titleTracking,
            subtitle,
            subtitleColor,
            subtitleSize,
            subtitleWeight,
            subtitleTracking,
            minHeight,
            padding,
            borderRadius,
            width
        } = attributes;
        
        // Get custom values from theme.json
        const borderRadiusOptions = [
            { label: 'None', value: '0' },
            { label: 'Extra Small', value: 'var(--wp--custom--border-radius--xs)' },
            { label: 'Small', value: 'var(--wp--custom--border-radius--sm)' },
            { label: 'Base', value: 'var(--wp--custom--border-radius--base)' },
            { label: 'Large', value: 'var(--wp--custom--border-radius--lg)' },
            { label: 'Extra Large', value: 'var(--wp--custom--border-radius--xl)' },
            { label: '2XL', value: 'var(--wp--custom--border-radius--2xl)' },
            { label: '3XL', value: 'var(--wp--custom--border-radius--3xl)' },
            { label: '4XL', value: 'var(--wp--custom--border-radius--4xl)' },
            { label: 'Full', value: 'var(--wp--custom--border-radius--full)' }
        ];

        const blockProps = useBlockProps({
            className: `hero-block hero-block--${width}`,
            style: {
                '--hero-min-height': minHeight + 'px',
                '--hero-overlay-color': overlayColor,
                '--hero-overlay-opacity': overlayOpacity / 100,
                '--hero-title-color': titleColor,
                '--hero-title-size': titleSize,
                '--hero-title-weight': titleWeight,
                '--hero-title-tracking': titleTracking,
                '--hero-subtitle-color': subtitleColor,
                '--hero-subtitle-size': subtitleSize,
                '--hero-subtitle-weight': subtitleWeight,
                '--hero-subtitle-tracking': subtitleTracking,
                '--hero-padding-top': padding?.top || '3rem',
                '--hero-padding-right': padding?.right || '1rem',
                '--hero-padding-bottom': padding?.bottom || '3rem',
                '--hero-padding-left': padding?.left || '1rem',
                '--hero-border-radius': width === 'full-width' ? '0' : borderRadius
            }
        });

        return (
            <>
                <InspectorControls>
                    <PanelBody title="Layout" initialOpen={true}>
                        <SelectControl
                            label="Width"
                            value={width}
                            options={[
                                { label: 'Wide (1400px max)', value: 'wide' },
                                { label: 'Content Width (1200px max)', value: 'content-width' },
                                { label: 'Full Width', value: 'full-width' }
                            ]}
                            onChange={(value) => setAttributes({ width: value })}
                        />
                        {width !== 'full-width' && (
                            <SelectControl
                                label="Border Radius"
                                value={borderRadius}
                                options={borderRadiusOptions}
                                onChange={(value) => setAttributes({ borderRadius: value })}
                            />
                        )}
                    </PanelBody>

                    <SpacingControls
                        attributes={attributes}
                        setAttributes={setAttributes}
                        showPadding={true}
                        showMinHeight={true}
                        minHeightMin={200}
                        minHeightMax={800}
                    />

                    <BackgroundControls
                        attributes={attributes}
                        setAttributes={setAttributes}
                        showImage={true}
                        showOverlay={true}
                    />

                    <TypographyControls
                        title="Title Typography"
                        attributes={attributes}
                        setAttributes={setAttributes}
                        prefix="title"
                    />

                    <TypographyControls
                        title="Subtitle Typography"
                        attributes={attributes}
                        setAttributes={setAttributes}
                        prefix="subtitle"
                    />
                </InspectorControls>

                <div {...blockProps}>
                    <div 
                        className="hero-block__container"
                        style={{
                            backgroundImage: backgroundImage ? `url(${backgroundImage})` : undefined
                        }}
                    >
                        <div className="hero-block__overlay"></div>
                        <div className="hero-block__content">
                            <RichText
                                tagName="h1"
                                className="hero-block__title"
                                value={title}
                                onChange={(value) => setAttributes({ title: value })}
                                placeholder="Enter title..."
                            />
                            <RichText
                                tagName="p"
                                className="hero-block__subtitle"
                                value={subtitle}
                                onChange={(value) => setAttributes({ subtitle: value })}
                                placeholder="Enter subtitle..."
                            />
                        </div>
                    </div>
                </div>
            </>
        );
    },
    save: () => null
});
