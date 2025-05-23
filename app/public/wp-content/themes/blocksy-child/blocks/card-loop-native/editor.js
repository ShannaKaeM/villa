/**
 * Card Loop Block - Editor Script
 * 
 * This implements the editor interface for the Card Loop block
 * using semantic class names that match our global CSS architecture.
 */

(function(blocks, element, blockEditor, components) {
    var el = element.createElement;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var TextControl = components.TextControl;
    var SelectControl = components.SelectControl;
    var ToggleControl = components.ToggleControl;
    
    blocks.registerBlockType('mi/card-loop', {
        title: 'Card Loop',
        icon: 'grid-view',
        category: 'miblocks',
        description: 'Displays a filterable grid of property cards',
        
        edit: function(props) {
            var attributes = props.attributes;
            
            // Inspector controls for the sidebar
            var inspectorControls = el(
                InspectorControls,
                { key: 'inspector' },
                
                // General Settings
                el(
                    PanelBody,
                    { title: 'General Settings', initialOpen: true },
                    el(
                        TextControl,
                        {
                            label: 'Title',
                            value: attributes.title,
                            help: 'Main title for the block',
                            onChange: function(value) {
                                props.setAttributes({ title: value });
                            }
                        }
                    ),
                    el(
                        SelectControl,
                        {
                            label: 'Post Type',
                            value: attributes.post_type,
                            help: 'Select which post type to display',
                            options: [
                                { label: 'Properties', value: 'property' },
                                { label: 'Businesses', value: 'business' },
                                { label: 'Articles', value: 'article' },
                                { label: 'User Profiles', value: 'user_profile' }
                            ],
                            onChange: function(value) {
                                props.setAttributes({ post_type: value });
                            }
                        }
                    ),
                    el(
                        ToggleControl,
                        {
                            label: 'Show Filters',
                            checked: attributes.show_filters,
                            help: 'Whether to show the filter sidebar',
                            onChange: function(value) {
                                props.setAttributes({ show_filters: value });
                            }
                        }
                    )
                ),
                
                // Query Settings
                el(
                    PanelBody,
                    { title: 'Query Settings', initialOpen: false },
                    el(
                        SelectControl,
                        {
                            label: 'Number of Cards',
                            value: attributes.posts_per_page,
                            help: 'Select how many items to display per page',
                            options: [
                                { label: '3 Cards', value: '3' },
                                { label: '6 Cards', value: '6' },
                                { label: '9 Cards', value: '9' },
                                { label: '12 Cards', value: '12' },
                                { label: '15 Cards', value: '15' },
                                { label: '18 Cards', value: '18' },
                                { label: '24 Cards', value: '24' },
                                { label: 'All Cards', value: '-1' }
                            ],
                            onChange: function(value) {
                                props.setAttributes({ posts_per_page: value });
                            }
                        }
                    )
                ),
                
                // Layout Settings
                el(
                    PanelBody,
                    { title: 'Layout Settings', initialOpen: false },
                    el(
                        SelectControl,
                        {
                            label: 'Columns',
                            value: attributes.columns,
                            help: 'Number of columns in the grid',
                            options: [
                                { label: '1 Column', value: '1' },
                                { label: '2 Columns', value: '2' },
                                { label: '3 Columns', value: '3' },
                                { label: '4 Columns', value: '4' }
                            ],
                            onChange: function(value) {
                                props.setAttributes({ columns: value });
                            }
                        }
                    ),
                    el(
                        SelectControl,
                        {
                            label: 'Card Style',
                            value: attributes.card_style,
                            help: 'Style of the cards - size or color variants',
                            options: [
                                { label: 'Default', value: 'default' },
                                { label: 'Small', value: 'sm' },
                                { label: 'Large', value: 'lg' },
                                { label: 'Primary', value: 'primary' },
                                { label: 'Secondary', value: 'secondary' },
                                { label: 'Neutral', value: 'neutral' }
                            ],
                            onChange: function(value) {
                                props.setAttributes({ card_style: value });
                            }
                        }
                    )
                )
            );
            
            // Block preview
            var blockPreview = el(
                'div',
                { 
                    className: 'mi-card-loop-editor-preview',
                    style: { 
                        padding: '20px',
                        backgroundColor: '#f8f9fa',
                        border: '1px solid #ddd',
                        borderRadius: '4px'
                    }
                },
                // Title
                attributes.title && el(
                    'h2',
                    { style: { marginTop: 0 } },
                    attributes.title
                ),
                
                // Info
                el(
                    'div',
                    { className: 'mi-card-loop-info' },
                    el(
                        'p',
                        {},
                        'Post Type: ',
                        el('strong', {}, attributes.post_type)
                    ),
                    el(
                        'p',
                        {},
                        'Columns: ',
                        el('strong', {}, attributes.columns)
                    ),
                    el(
                        'p',
                        {},
                        'Items: ',
                        el('strong', {}, attributes.posts_per_page)
                    ),
                    el(
                        'p',
                        {},
                        'Filters: ',
                        el('strong', {}, attributes.show_filters ? 'Shown' : 'Hidden')
                    )
                ),
                
                // Card Grid Preview
                el(
                    'div',
                    { 
                        className: 'mi-card-loop-grid-preview',
                        style: {
                            display: 'grid',
                            gridTemplateColumns: 'repeat(' + attributes.columns + ', 1fr)',
                            gap: '20px',
                            marginTop: '20px'
                        }
                    },
                    // Generate preview cards
                    Array.from({ length: Math.min(parseInt(attributes.columns) * 2, 8) }, function(_, i) {
                        return el(
                            'div',
                            {
                                key: i,
                                className: 'card card--' + attributes.card_style,
                                style: {
                                    backgroundColor: '#fff',
                                    borderRadius: '8px',
                                    boxShadow: '0 2px 4px rgba(0,0,0,0.1)',
                                    overflow: 'hidden'
                                }
                            },
                            el(
                                'div',
                                {
                                    className: 'card__image',
                                    style: {
                                        height: '160px',
                                        backgroundColor: '#e0e0e0'
                                    }
                                }
                            ),
                            el(
                                'div',
                                {
                                    className: 'card__content',
                                    style: {
                                        padding: '15px'
                                    }
                                },
                                el(
                                    'h3',
                                    {
                                        className: 'card__title',
                                        style: {
                                            margin: '0 0 10px',
                                            fontSize: '18px'
                                        }
                                    },
                                    'Sample ' + attributes.post_type + ' ' + (i + 1)
                                ),
                                el(
                                    'p',
                                    {
                                        className: 'card__excerpt',
                                        style: {
                                            margin: '0',
                                            fontSize: '14px',
                                            color: '#666'
                                        }
                                    },
                                    'This is a preview of how the cards will appear on the frontend.'
                                )
                            )
                        );
                    })
                ),
                
                // Note
                el(
                    'p',
                    {
                        style: {
                            marginTop: '20px',
                            fontSize: '13px',
                            fontStyle: 'italic',
                            color: '#666'
                        }
                    },
                    'Note: This is a preview. Actual content will be loaded from your ' + attributes.post_type + ' posts.'
                )
            );
            
            return [
                inspectorControls,
                blockPreview
            ];
        },
        
        save: function() {
            // Dynamic block, so we return null
            return null;
        }
    });
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components
));
