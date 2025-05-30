# miGV Custom Blocks

This directory contains custom Gutenberg blocks for the miGV theme.

## Block Development

### Structure

Each custom block should follow this structure:

```
blocks/
└── block-name/
    ├── block.json          # Block configuration
    ├── index.js           # Block registration
    ├── edit.js            # Editor component
    ├── save.js            # Frontend save component
    ├── style.scss         # Frontend styles
    ├── editor.scss        # Editor-specific styles
    └── README.md          # Block documentation
```

### Block Configuration

Each block must include a `block.json` file with the following structure:

```json
{
    "$schema": "https://schemas.wp.org/trunk/block.json",
    "apiVersion": 3,
    "name": "migv/block-name",
    "title": "Block Title",
    "category": "migv-blocks",
    "icon": "block-default",
    "description": "Block description",
    "keywords": ["keyword1", "keyword2"],
    "version": "1.0.0",
    "textdomain": "migv",
    "supports": {
        "html": false,
        "align": ["wide", "full"],
        "color": {
            "background": true,
            "text": true
        },
        "spacing": {
            "padding": true,
            "margin": true
        }
    },
    "attributes": {
        "content": {
            "type": "string",
            "default": ""
        }
    },
    "editorScript": "file:./index.js",
    "editorStyle": "file:./editor.css",
    "style": "file:./style.css"
}
```

### Block Registration

Blocks are automatically registered in `functions.php` using the `migv_register_blocks()` function.

### Development Workflow

1. Create a new directory in `/blocks/` with your block name
2. Add the required files (block.json, index.js, etc.)
3. Build your block using WordPress components
4. Test in the block editor
5. Style for both editor and frontend

### Available Components

Use WordPress components for consistent UI:

- `@wordpress/components` - UI components
- `@wordpress/block-editor` - Block editor components
- `@wordpress/element` - React-like elements
- `@wordpress/i18n` - Internationalization

### Example Block

Here's a basic example of a custom block:

```javascript
// index.js
import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';

registerBlockType('migv/example-block', {
    edit: Edit,
    save: Save,
});
```

```javascript
// edit.js
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit({ attributes, setAttributes }) {
    const { content } = attributes;
    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            <RichText
                tagName="p"
                value={content}
                onChange={(value) => setAttributes({ content: value })}
                placeholder="Enter your content..."
            />
        </div>
    );
}
```

```javascript
// save.js
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { content } = attributes;
    const blockProps = useBlockProps.save();

    return (
        <div {...blockProps}>
            <RichText.Content tagName="p" value={content} />
        </div>
    );
}
```

### Styling Guidelines

- Use CSS custom properties from the theme
- Follow BEM naming convention
- Ensure responsive design
- Test in both editor and frontend
- Use theme color palette

### Block Categories

Custom blocks are organized under the "miGV Blocks" category. This is defined in `functions.php`.

### Best Practices

1. **Performance**: Keep blocks lightweight
2. **Accessibility**: Use semantic HTML and ARIA labels
3. **Responsive**: Test on all device sizes
4. **Consistency**: Follow theme design patterns
5. **Documentation**: Document each block's purpose and usage

### Testing

Before deploying blocks:

1. Test in block editor
2. Test on frontend
3. Test responsive behavior
4. Test accessibility with screen readers
5. Validate HTML output

### Future Blocks

Planned custom blocks for miGV theme:

- Hero Section
- Feature Grid
- Testimonials
- Team Members
- Call to Action
- Stats Counter
- Portfolio Grid
- Contact Form

---

For more information on block development, see the [WordPress Block Editor Handbook](https://developer.wordpress.org/block-editor/).
