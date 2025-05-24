import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { 
    PanelBody, 
    SelectControl, 
    RangeControl, 
    ToggleControl,
    RadioControl 
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit({ attributes, setAttributes }) {
    const blockProps = useBlockProps();
    
    // Updated post type options to include Articles CPT and User Profiles
    const postTypeOptions = [
        { label: 'Properties', value: 'property' },
        { label: 'Articles', value: 'article' },
        { label: 'Businesses', value: 'business' },
        { label: 'User Profiles', value: 'profile' },
    ];

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Content Settings', 'miblocks')}>
                    <SelectControl
                        label={__('Post Type', 'miblocks')}
                        value={attributes.postType}
                        options={postTypeOptions}
                        onChange={(postType) => setAttributes({ postType })}
                        help={__('Select the type of content to display', 'miblocks')}
                    />
                    
                    <RangeControl
                        label={__('Posts Per Page', 'miblocks')}
                        value={attributes.postsPerPage}
                        onChange={(postsPerPage) => setAttributes({ postsPerPage })}
                        min={1}
                        max={50}
                    />
                </PanelBody>

                <PanelBody title={__('Layout Settings', 'miblocks')}>
                    <RangeControl
                        label={__('Columns', 'miblocks')}
                        value={attributes.columns}
                        onChange={(columns) => setAttributes({ columns })}
                        min={1}
                        max={6}
                        help={__('Number of columns in the grid', 'miblocks')}
                    />
                    
                    <SelectControl
                        label={__('Card Style', 'miblocks')}
                        value={attributes.cardStyle}
                        options={[
                            { label: 'Default', value: 'default' },
                            { label: 'Minimal', value: 'minimal' },
                            { label: 'Featured', value: 'featured' },
                        ]}
                        onChange={(cardStyle) => setAttributes({ cardStyle })}
                    />
                </PanelBody>

                <PanelBody title={__('Filter Settings', 'miblocks')} initialOpen={false}>
                    <ToggleControl
                        label={__('Show Filter', 'miblocks')}
                        checked={attributes.showFilter}
                        onChange={(showFilter) => setAttributes({ showFilter })}
                    />
                    
                    {attributes.showFilter && (
                        <RadioControl
                            label={__('Filter Position', 'miblocks')}
                            selected={attributes.filterPosition}
                            options={[
                                { label: 'Left Sidebar', value: 'left' },
                                { label: 'Top Bar', value: 'top' },
                            ]}
                            onChange={(filterPosition) => setAttributes({ filterPosition })}
                        />
                    )}
                </PanelBody>

                <PanelBody title={__('Pagination', 'miblocks')} initialOpen={false}>
                    <ToggleControl
                        label={__('Show Pagination', 'miblocks')}
                        checked={attributes.showPagination}
                        onChange={(showPagination) => setAttributes({ showPagination })}
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <div className="card-loop-editor-preview">
                    <h3>{__('Card Loop Block', 'miblocks')}</h3>
                    <p>{__('Post Type:', 'miblocks')} <strong>{attributes.postType}</strong></p>
                    <p>{__('Posts Per Page:', 'miblocks')} <strong>{attributes.postsPerPage}</strong></p>
                    <p>{__('Columns:', 'miblocks')} <strong>{attributes.columns}</strong></p>
                    
                    <ServerSideRender
                        block="miblocks/card-loop"
                        attributes={attributes}
                        EmptyResponsePlaceholder={() => (
                            <div className="components-placeholder">
                                <p>{__('Loading preview...', 'miblocks')}</p>
                            </div>
                        )}
                    />
                </div>
            </div>
        </>
    );
}
