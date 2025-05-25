import { PanelBody, Button, RangeControl } from '@wordpress/components';
import { MediaUpload, MediaUploadCheck, PanelColorSettings, useSetting } from '@wordpress/block-editor';

export const BackgroundControls = ({ 
    attributes,
    setAttributes,
    showImage = true,
    showColor = true,
    showOverlay = true
}) => {
    const colors = useSetting('color.palette');
    
    return (
        <>
            {(showImage || showColor) && (
                <PanelBody title="Background" initialOpen={false}>
                    {showImage && (
                        <>
                            <MediaUploadCheck>
                                <MediaUpload
                                    onSelect={(media) => setAttributes({ 
                                        backgroundImage: media.url,
                                        backgroundImageId: media.id 
                                    })}
                                    allowedTypes={['image']}
                                    value={attributes.backgroundImageId}
                                    render={({ open }) => (
                                        <Button onClick={open} variant="secondary">
                                            {attributes.backgroundImage ? 'Change Background Image' : 'Select Background Image'}
                                        </Button>
                                    )}
                                />
                            </MediaUploadCheck>
                            {attributes.backgroundImage && (
                                <Button 
                                    onClick={() => setAttributes({ backgroundImage: '', backgroundImageId: 0 })}
                                    variant="link"
                                    isDestructive
                                    style={{ marginTop: '8px' }}
                                >
                                    Remove Background Image
                                </Button>
                            )}
                        </>
                    )}
                    {showColor && !attributes.backgroundImage && (
                        <PanelColorSettings
                            colorSettings={[
                                {
                                    value: attributes.backgroundColor,
                                    onChange: (color) => setAttributes({ backgroundColor: color }),
                                    label: 'Background Color',
                                    colors: colors
                                }
                            ]}
                        />
                    )}
                </PanelBody>
            )}
            
            {showOverlay && (
                <PanelBody title="Overlay" initialOpen={false}>
                    <PanelColorSettings
                        title="Overlay Settings"
                        colorSettings={[
                            {
                                value: attributes.overlayColor,
                                onChange: (color) => setAttributes({ overlayColor: color }),
                                label: 'Overlay Color',
                                colors: colors
                            }
                        ]}
                    >
                        <RangeControl
                            label="Overlay Opacity"
                            value={attributes.overlayOpacity}
                            onChange={(value) => setAttributes({ overlayOpacity: value })}
                            min={0}
                            max={100}
                            step={5}
                        />
                    </PanelColorSettings>
                </PanelBody>
            )}
        </>
    );
};
