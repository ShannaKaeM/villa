import { registerBlockType } from '@wordpress/blocks';
import { 
    InspectorControls, 
    RichText,
    useBlockProps,
    URLInput
} from '@wordpress/block-editor';
import { 
    PanelBody,
    TextControl
} from '@wordpress/components';
import { TypographyControls, SpacingControls, BackgroundControls } from '../../components';

registerBlockType('miblocks/cta-section', {
    edit: ({ attributes, setAttributes }) => {
        const {
            title,
            titleColor,
            titleSize,
            titleWeight,
            titleTracking,
            description,
            descriptionColor,
            descriptionSize,
            descriptionWeight,
            descriptionTracking,
            backgroundColor,
            backgroundImage,
            backgroundImageId,
            overlayColor,
            overlayOpacity,
            padding,
            buttonText,
            buttonUrl
        } = attributes;

        const blockProps = useBlockProps({
            className: 'cta-section',
            style: {
                '--cta-title-color': titleColor,
                '--cta-title-size': titleSize,
                '--cta-title-weight': titleWeight,
                '--cta-title-tracking': titleTracking,
                '--cta-description-color': descriptionColor,
                '--cta-description-size': descriptionSize,
                '--cta-description-weight': descriptionWeight,
                '--cta-description-tracking': descriptionTracking,
                '--cta-bg-color': backgroundColor,
                '--cta-overlay-color': overlayColor,
                '--cta-overlay-opacity': overlayOpacity / 100,
                '--cta-padding-top': padding?.top || '4rem',
                '--cta-padding-right': padding?.right || '2rem',
                '--cta-padding-bottom': padding?.bottom || '4rem',
                '--cta-padding-left': padding?.left || '2rem'
            }
        });

        return (
            <>
                <InspectorControls>
                    <SpacingControls
                        attributes={attributes}
                        setAttributes={setAttributes}
                        showPadding={true}
                    />

                    <BackgroundControls
                        attributes={attributes}
                        setAttributes={setAttributes}
                        showImage={true}
                        showColor={true}
                        showOverlay={true}
                    />

                    <TypographyControls
                        title="Title Typography"
                        attributes={attributes}
                        setAttributes={setAttributes}
                        prefix="title"
                    />

                    <TypographyControls
                        title="Description Typography"
                        attributes={attributes}
                        setAttributes={setAttributes}
                        prefix="description"
                    />

                    <PanelBody title="Button Settings" initialOpen={false}>
                        <TextControl
                            label="Button Text"
                            value={buttonText}
                            onChange={(value) => setAttributes({ buttonText: value })}
                        />
                        <URLInput
                            label="Button URL"
                            value={buttonUrl}
                            onChange={(value) => setAttributes({ buttonUrl: value })}
                        />
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    <div 
                        className="cta-section__container"
                        style={{
                            backgroundImage: backgroundImage ? `url(${backgroundImage})` : undefined
                        }}
                    >
                        {backgroundImage && <div className="cta-section__overlay"></div>}
                        <div className="cta-section__content">
                            <RichText
                                tagName="h2"
                                className="cta-section__title"
                                value={title}
                                onChange={(value) => setAttributes({ title: value })}
                                placeholder="Enter title..."
                            />
                            <RichText
                                tagName="p"
                                className="cta-section__description"
                                value={description}
                                onChange={(value) => setAttributes({ description: value })}
                                placeholder="Enter description..."
                            />
                            {buttonText && (
                                <div className="cta-section__button-wrapper">
                                    <a href={buttonUrl} className="cta-section__button">
                                        {buttonText}
                                    </a>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </>
        );
    },
    save: () => null
});
