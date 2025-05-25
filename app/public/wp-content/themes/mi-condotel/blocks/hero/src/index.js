import { registerBlockType } from '@wordpress/blocks';
import { 
    InspectorControls, 
    MediaUpload, 
    MediaUploadCheck,
    useBlockProps,
    RichText
} from '@wordpress/block-editor';
import { 
    PanelBody, 
    SelectControl, 
    TextControl, 
    RangeControl,
    Button
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

// Import block metadata
import metadata from '../block.json';

// Edit component
const Edit = ({ attributes, setAttributes }) => {
    const {
        title,
        subtitle,
        backgroundImage,
        minHeight,
        widthOption,
        borderRadius,
        overlayColor,
        overlayVariation,
        overlayOpacity,
        titleSize,
        titleWeight,
        titleColor,
        titleColorVariation,
        titleTracking,
        titleTransform,
        subtitleSize,
        subtitleWeight,
        subtitleColor,
        subtitleColorVariation,
        subtitleTracking,
        subtitleTransform,
        titleSpacing
    } = attributes;

    // Helper function to get color CSS variable
    const getColorVar = (color, variation = 'med') => {
        if (color === 'white' || color === 'black') {
            return `var(--color-${color})`;
        }
        return variation === 'med' 
            ? `var(--color-${color}-med)` 
            : `var(--color-${color}-${variation})`;
    };

    // Build classes
    const wrapperClasses = ['hero-block'];
    if (widthOption === 'content') {
        wrapperClasses.push('hero-block--content-width');
    } else if (widthOption === 'full') {
        wrapperClasses.push('hero-block--full-width');
    }

    const containerClasses = ['hero-block__container'];
    // Only add border radius if not full width
    if (borderRadius !== 'none' && widthOption !== 'full') {
        containerClasses.push(`hero-block__container--radius-${borderRadius}`);
    }

    // Build styles
    const containerStyle = {
        minHeight: minHeight,
        backgroundImage: backgroundImage ? `url(${backgroundImage})` : undefined,
    };

    const overlayStyle = {
        backgroundColor: `color-mix(in srgb, ${getColorVar(overlayColor, overlayVariation)} ${overlayOpacity * 100}%, transparent)`,
    };

    const titleStyle = {
        fontSize: `var(--font-size-${titleSize})`,
        fontWeight: `var(--font-weight-${titleWeight})`,
        color: getColorVar(titleColor, titleColorVariation),
        letterSpacing: `var(--letter-spacing-${titleTracking})`,
        textTransform: `var(--text-transform-${titleTransform})`,
        marginBottom: `var(--spacing-${titleSpacing})`,
    };

    const subtitleStyle = {
        fontSize: `var(--font-size-${subtitleSize})`,
        fontWeight: `var(--font-weight-${subtitleWeight})`,
        color: getColorVar(subtitleColor, subtitleColorVariation),
        letterSpacing: `var(--letter-spacing-${subtitleTracking})`,
        textTransform: `var(--text-transform-${subtitleTransform})`,
    };

    const blockProps = useBlockProps({
        className: wrapperClasses.join(' '),
    });

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Layout Settings', 'mi-condotel')}>
                    <SelectControl
                        label={__('Width Option', 'mi-condotel')}
                        value={widthOption}
                        options={[
                            { label: 'Wide (1400px max)', value: '90' },
                            { label: 'Content Width (1200px max)', value: 'content' },
                            { label: 'Full Width', value: 'full' },
                        ]}
                        onChange={(value) => setAttributes({ widthOption: value })}
                    />
                    <TextControl
                        label={__('Minimum Height', 'mi-condotel')}
                        value={minHeight}
                        onChange={(value) => setAttributes({ minHeight: value })}
                        help={__('e.g., 400px, 50vh', 'mi-condotel')}
                    />
                    <SelectControl
                        label={__('Border Radius', 'mi-condotel')}
                        value={borderRadius}
                        options={[
                            { label: 'None', value: 'none' },
                            { label: 'Extra Small', value: 'xs' },
                            { label: 'Small', value: 'sm' },
                            { label: 'Medium', value: 'md' },
                            { label: 'Large', value: 'lg' },
                            { label: 'Extra Large', value: 'xl' },
                            { label: '2X Large', value: '2xl' },
                            { label: '3X Large', value: '3xl' },
                            { label: '4X Large', value: '4xl' },
                            { label: 'Full', value: 'full' },
                        ]}
                        onChange={(value) => setAttributes({ borderRadius: value })}
                        disabled={widthOption === 'full'}
                        help={widthOption === 'full' ? __('Border radius is disabled for full width heroes', 'mi-condotel') : ''}
                    />
                </PanelBody>

                <PanelBody title={__('Background Settings', 'mi-condotel')}>
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={(media) => setAttributes({ backgroundImage: media.url })}
                            allowedTypes={['image']}
                            value={backgroundImage}
                            render={({ open }) => (
                                <div>
                                    <Button onClick={open} variant="secondary">
                                        {backgroundImage ? __('Change Image', 'mi-condotel') : __('Select Image', 'mi-condotel')}
                                    </Button>
                                    {backgroundImage && (
                                        <Button
                                            onClick={() => setAttributes({ backgroundImage: '' })}
                                            variant="link"
                                            isDestructive
                                            style={{ marginLeft: '10px' }}
                                        >
                                            {__('Remove', 'mi-condotel')}
                                        </Button>
                                    )}
                                </div>
                            )}
                        />
                    </MediaUploadCheck>
                </PanelBody>

                <PanelBody title={__('Overlay Settings', 'mi-condotel')}>
                    <SelectControl
                        label={__('Overlay Color', 'mi-condotel')}
                        value={overlayColor}
                        options={[
                            { label: 'Primary', value: 'primary' },
                            { label: 'Secondary', value: 'secondary' },
                            { label: 'Tertiary', value: 'tertiary' },
                            { label: 'Neutral', value: 'neutral' },
                            { label: 'Base', value: 'base' },
                            { label: 'White', value: 'white' },
                            { label: 'Black', value: 'black' },
                        ]}
                        onChange={(value) => setAttributes({ overlayColor: value })}
                    />
                    {overlayColor !== 'white' && overlayColor !== 'black' && (
                        <SelectControl
                            label={__('Color Variation', 'mi-condotel')}
                            value={overlayVariation}
                            options={[
                                { label: 'Lightest', value: 'lightest' },
                                { label: 'Light', value: 'light' },
                                { label: 'Medium', value: 'med' },
                                { label: 'Dark', value: 'dark' },
                                { label: 'Darkest', value: 'darkest' },
                            ]}
                            onChange={(value) => setAttributes({ overlayVariation: value })}
                        />
                    )}
                    <RangeControl
                        label={__('Overlay Opacity', 'mi-condotel')}
                        value={overlayOpacity}
                        onChange={(value) => setAttributes({ overlayOpacity: value })}
                        min={0}
                        max={1}
                        step={0.1}
                    />
                </PanelBody>

                <PanelBody title={__('Title Settings', 'mi-condotel')}>
                    <SelectControl
                        label={__('Title Size', 'mi-condotel')}
                        value={titleSize}
                        options={[
                            { label: 'Extra Small', value: 'xs' },
                            { label: 'Small', value: 'sm' },
                            { label: 'Base', value: 'base' },
                            { label: 'Large', value: 'lg' },
                            { label: 'Extra Large', value: 'xl' },
                            { label: '2X Large', value: '2xl' },
                            { label: '3X Large', value: '3xl' },
                            { label: '4X Large', value: '4xl' },
                            { label: '5X Large', value: '5xl' },
                        ]}
                        onChange={(value) => setAttributes({ titleSize: value })}
                    />
                    <SelectControl
                        label={__('Title Weight', 'mi-condotel')}
                        value={titleWeight}
                        options={[
                            { label: 'Extra Light', value: 'extra-light' },
                            { label: 'Light', value: 'light' },
                            { label: 'Normal', value: 'normal' },
                            { label: 'Semibold', value: 'semibold' },
                            { label: 'Bold', value: 'bold' },
                            { label: 'Extra Bold', value: 'extra-bold' },
                        ]}
                        onChange={(value) => setAttributes({ titleWeight: value })}
                    />
                    <SelectControl
                        label={__('Title Color', 'mi-condotel')}
                        value={titleColor}
                        options={[
                            { label: 'Primary', value: 'primary' },
                            { label: 'Secondary', value: 'secondary' },
                            { label: 'Tertiary', value: 'tertiary' },
                            { label: 'Neutral', value: 'neutral' },
                            { label: 'Base', value: 'base' },
                            { label: 'White', value: 'white' },
                            { label: 'Black', value: 'black' },
                        ]}
                        onChange={(value) => setAttributes({ titleColor: value })}
                    />
                    {titleColor !== 'white' && titleColor !== 'black' && (
                        <SelectControl
                            label={__('Title Color Variation', 'mi-condotel')}
                            value={titleColorVariation}
                            options={[
                                { label: 'Lightest', value: 'lightest' },
                                { label: 'Light', value: 'light' },
                                { label: 'Medium', value: 'med' },
                                { label: 'Dark', value: 'dark' },
                                { label: 'Darkest', value: 'darkest' },
                            ]}
                            onChange={(value) => setAttributes({ titleColorVariation: value })}
                        />
                    )}
                    <SelectControl
                        label={__('Title Letter Spacing', 'mi-condotel')}
                        value={titleTracking}
                        options={[
                            { label: 'Tighter', value: 'tighter' },
                            { label: 'Tight', value: 'tight' },
                            { label: 'Normal', value: 'normal' },
                            { label: 'Wide', value: 'wide' },
                            { label: 'Wider', value: 'wider' },
                            { label: 'Widest', value: 'widest' },
                        ]}
                        onChange={(value) => setAttributes({ titleTracking: value })}
                    />
                    <SelectControl
                        label={__('Title Transform', 'mi-condotel')}
                        value={titleTransform}
                        options={[
                            { label: 'None', value: 'none' },
                            { label: 'Uppercase', value: 'uppercase' },
                            { label: 'Lowercase', value: 'lowercase' },
                            { label: 'Capitalize', value: 'capitalize' },
                        ]}
                        onChange={(value) => setAttributes({ titleTransform: value })}
                    />
                    <SelectControl
                        label={__('Title Bottom Spacing', 'mi-condotel')}
                        value={titleSpacing}
                        options={[
                            { label: 'Extra Small', value: 'xs' },
                            { label: 'Small', value: 'sm' },
                            { label: 'Medium', value: 'md' },
                            { label: 'Large', value: 'lg' },
                            { label: 'Extra Large', value: 'xl' },
                            { label: '2X Large', value: '2xl' },
                        ]}
                        onChange={(value) => setAttributes({ titleSpacing: value })}
                    />
                </PanelBody>

                <PanelBody title={__('Subtitle Settings', 'mi-condotel')}>
                    <SelectControl
                        label={__('Subtitle Size', 'mi-condotel')}
                        value={subtitleSize}
                        options={[
                            { label: 'Extra Small', value: 'xs' },
                            { label: 'Small', value: 'sm' },
                            { label: 'Base', value: 'base' },
                            { label: 'Large', value: 'lg' },
                            { label: 'Extra Large', value: 'xl' },
                            { label: '2X Large', value: '2xl' },
                            { label: '3X Large', value: '3xl' },
                        ]}
                        onChange={(value) => setAttributes({ subtitleSize: value })}
                    />
                    <SelectControl
                        label={__('Subtitle Weight', 'mi-condotel')}
                        value={subtitleWeight}
                        options={[
                            { label: 'Extra Light', value: 'extra-light' },
                            { label: 'Light', value: 'light' },
                            { label: 'Normal', value: 'normal' },
                            { label: 'Semibold', value: 'semibold' },
                            { label: 'Bold', value: 'bold' },
                            { label: 'Extra Bold', value: 'extra-bold' },
                        ]}
                        onChange={(value) => setAttributes({ subtitleWeight: value })}
                    />
                    <SelectControl
                        label={__('Subtitle Color', 'mi-condotel')}
                        value={subtitleColor}
                        options={[
                            { label: 'Primary', value: 'primary' },
                            { label: 'Secondary', value: 'secondary' },
                            { label: 'Tertiary', value: 'tertiary' },
                            { label: 'Neutral', value: 'neutral' },
                            { label: 'Base', value: 'base' },
                            { label: 'White', value: 'white' },
                            { label: 'Black', value: 'black' },
                        ]}
                        onChange={(value) => setAttributes({ subtitleColor: value })}
                    />
                    {subtitleColor !== 'white' && subtitleColor !== 'black' && (
                        <SelectControl
                            label={__('Subtitle Color Variation', 'mi-condotel')}
                            value={subtitleColorVariation}
                            options={[
                                { label: 'Lightest', value: 'lightest' },
                                { label: 'Light', value: 'light' },
                                { label: 'Medium', value: 'med' },
                                { label: 'Dark', value: 'dark' },
                                { label: 'Darkest', value: 'darkest' },
                            ]}
                            onChange={(value) => setAttributes({ subtitleColorVariation: value })}
                        />
                    )}
                    <SelectControl
                        label={__('Subtitle Letter Spacing', 'mi-condotel')}
                        value={subtitleTracking}
                        options={[
                            { label: 'Tighter', value: 'tighter' },
                            { label: 'Tight', value: 'tight' },
                            { label: 'Normal', value: 'normal' },
                            { label: 'Wide', value: 'wide' },
                            { label: 'Wider', value: 'wider' },
                            { label: 'Widest', value: 'widest' },
                        ]}
                        onChange={(value) => setAttributes({ subtitleTracking: value })}
                    />
                    <SelectControl
                        label={__('Subtitle Transform', 'mi-condotel')}
                        value={subtitleTransform}
                        options={[
                            { label: 'None', value: 'none' },
                            { label: 'Uppercase', value: 'uppercase' },
                            { label: 'Lowercase', value: 'lowercase' },
                            { label: 'Capitalize', value: 'capitalize' },
                        ]}
                        onChange={(value) => setAttributes({ subtitleTransform: value })}
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <div className={containerClasses.join(' ')} style={containerStyle}>
                    <div className="hero-block__overlay" style={overlayStyle}></div>
                    <div className="hero-block__content">
                        <div className="hero-block__text-wrapper">
                            <RichText
                                tagName="h1"
                                className="hero-block__title"
                                value={title}
                                onChange={(value) => setAttributes({ title: value })}
                                placeholder={__('Enter title...', 'mi-condotel')}
                                style={titleStyle}
                            />
                            <RichText
                                tagName="p"
                                className="hero-block__subtitle"
                                value={subtitle}
                                onChange={(value) => setAttributes({ subtitle: value })}
                                placeholder={__('Enter subtitle...', 'mi-condotel')}
                                style={subtitleStyle}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

// Register the block
registerBlockType(metadata.name, {
    edit: Edit,
    save: () => null, // Dynamic block
});
