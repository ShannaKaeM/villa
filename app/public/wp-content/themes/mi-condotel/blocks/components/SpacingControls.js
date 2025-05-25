import { PanelBody, RangeControl } from '@wordpress/components';
import { __experimentalBoxControl as BoxControl } from '@wordpress/components';

export const SpacingControls = ({ 
    title = 'Spacing',
    attributes,
    setAttributes,
    showPadding = true,
    showMargin = false,
    showMinHeight = false,
    minHeightMin = 200,
    minHeightMax = 800
}) => {
    return (
        <PanelBody title={title} initialOpen={false}>
            {showPadding && (
                <BoxControl
                    label="Padding"
                    values={attributes.padding}
                    onChange={(value) => setAttributes({ padding: value })}
                />
            )}
            {showMargin && (
                <BoxControl
                    label="Margin"
                    values={attributes.margin}
                    onChange={(value) => setAttributes({ margin: value })}
                />
            )}
            {showMinHeight && (
                <RangeControl
                    label="Minimum Height"
                    value={attributes.minHeight}
                    onChange={(value) => setAttributes({ minHeight: value })}
                    min={minHeightMin}
                    max={minHeightMax}
                    step={10}
                />
            )}
        </PanelBody>
    );
};
