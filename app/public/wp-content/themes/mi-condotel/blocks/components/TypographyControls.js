import { PanelBody, SelectControl } from '@wordpress/components';
import { PanelColorSettings, FontSizePicker, useSetting } from '@wordpress/block-editor';

export const TypographyControls = ({ 
    title = 'Typography',
    attributes,
    setAttributes,
    prefix = '',
    showSize = true,
    showWeight = true,
    showTracking = true,
    showColor = true
}) => {
    const colors = useSetting('color.palette');
    const fontSizes = useSetting('typography.fontSizes');
    
    const fontWeightOptions = [
        { label: 'Light', value: 'var(--wp--custom--font-weight--light)' },
        { label: 'Normal', value: 'var(--wp--custom--font-weight--normal)' },
        { label: 'Medium', value: 'var(--wp--custom--font-weight--medium)' },
        { label: 'Semibold', value: 'var(--wp--custom--font-weight--semibold)' },
        { label: 'Bold', value: 'var(--wp--custom--font-weight--bold)' },
        { label: 'Extra Bold', value: 'var(--wp--custom--font-weight--extrabold)' }
    ];

    const letterSpacingOptions = [
        { label: 'Tighter', value: 'var(--wp--custom--letter-spacing--tighter)' },
        { label: 'Tight', value: 'var(--wp--custom--letter-spacing--tight)' },
        { label: 'Normal', value: 'var(--wp--custom--letter-spacing--normal)' },
        { label: 'Wide', value: 'var(--wp--custom--letter-spacing--wide)' },
        { label: 'Wider', value: 'var(--wp--custom--letter-spacing--wider)' },
        { label: 'Widest', value: 'var(--wp--custom--letter-spacing--widest)' }
    ];

    return (
        <PanelBody title={title} initialOpen={false}>
            {showColor && (
                <PanelColorSettings
                    colorSettings={[
                        {
                            value: attributes[`${prefix}Color`],
                            onChange: (color) => setAttributes({ [`${prefix}Color`]: color }),
                            label: 'Color',
                            colors: colors
                        }
                    ]}
                />
            )}
            {showSize && (
                <FontSizePicker
                    value={attributes[`${prefix}Size`]}
                    onChange={(value) => setAttributes({ [`${prefix}Size`]: value })}
                    fontSizes={fontSizes}
                    withReset={false}
                />
            )}
            {showWeight && (
                <SelectControl
                    label="Font Weight"
                    value={attributes[`${prefix}Weight`]}
                    options={fontWeightOptions}
                    onChange={(value) => setAttributes({ [`${prefix}Weight`]: value })}
                />
            )}
            {showTracking && (
                <SelectControl
                    label="Letter Spacing"
                    value={attributes[`${prefix}Tracking`]}
                    options={letterSpacingOptions}
                    onChange={(value) => setAttributes({ [`${prefix}Tracking`]: value })}
                />
            )}
        </PanelBody>
    );
};
