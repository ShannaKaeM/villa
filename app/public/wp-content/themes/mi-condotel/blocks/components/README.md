# Reusable Block Components

This directory contains reusable React components that can be shared across multiple WordPress blocks. These components help maintain consistency and reduce code duplication.

## Available Components

### TypographyControls
Provides a complete typography control panel including color, size, weight, and letter spacing.

```javascript
import { TypographyControls } from '../components';

// Usage
<TypographyControls
    title="Title Typography"
    attributes={attributes}
    setAttributes={setAttributes}
    prefix="title"
    showSize={true}
    showWeight={true}
    showTracking={true}
    showColor={true}
/>
```

**Props:**
- `title` - Panel title (default: 'Typography')
- `attributes` - Block attributes object
- `setAttributes` - Function to update attributes
- `prefix` - Prefix for attribute names (e.g., 'title' creates titleColor, titleSize, etc.)
- `showSize` - Show font size picker (default: true)
- `showWeight` - Show font weight selector (default: true)
- `showTracking` - Show letter spacing selector (default: true)
- `showColor` - Show color picker (default: true)

### SpacingControls
Provides controls for padding, margin, and minimum height.

```javascript
import { SpacingControls } from '../components';

// Usage
<SpacingControls
    attributes={attributes}
    setAttributes={setAttributes}
    showPadding={true}
    showMargin={false}
    showMinHeight={true}
    minHeightMin={200}
    minHeightMax={800}
/>
```

**Props:**
- `title` - Panel title (default: 'Spacing')
- `attributes` - Block attributes object
- `setAttributes` - Function to update attributes
- `showPadding` - Show padding controls (default: true)
- `showMargin` - Show margin controls (default: false)
- `showMinHeight` - Show minimum height control (default: false)
- `minHeightMin` - Minimum value for height (default: 200)
- `minHeightMax` - Maximum value for height (default: 800)

### BackgroundControls
Provides controls for background image, color, and overlay settings.

```javascript
import { BackgroundControls } from '../components';

// Usage
<BackgroundControls
    attributes={attributes}
    setAttributes={setAttributes}
    showImage={true}
    showColor={true}
    showOverlay={true}
/>
```

**Props:**
- `attributes` - Block attributes object
- `setAttributes` - Function to update attributes
- `showImage` - Show image upload controls (default: true)
- `showColor` - Show background color picker (default: true)
- `showOverlay` - Show overlay controls (default: true)

## Creating New Components

When creating new reusable components:

1. Create a new file in the `components` directory
2. Export the component from `index.js`
3. Follow these conventions:
   - Accept `attributes` and `setAttributes` as props
   - Use theme.json values via `useSetting` hook
   - Provide sensible defaults for all props
   - Document the component with JSDoc comments

## Example: Creating a BorderControl Component

```javascript
// components/BorderControls.js
import { PanelBody, SelectControl, RangeControl } from '@wordpress/components';
import { PanelColorSettings, useSetting } from '@wordpress/block-editor';

export const BorderControls = ({ 
    attributes,
    setAttributes,
    showRadius = true,
    showWidth = true,
    showColor = true,
    showStyle = true
}) => {
    const colors = useSetting('color.palette');
    
    const borderRadiusOptions = [
        { label: 'None', value: '0' },
        { label: 'Small', value: 'var(--wp--custom--border-radius--sm)' },
        { label: 'Base', value: 'var(--wp--custom--border-radius--base)' },
        { label: 'Large', value: 'var(--wp--custom--border-radius--lg)' },
        // ... more options
    ];

    return (
        <PanelBody title="Border" initialOpen={false}>
            {/* Border controls here */}
        </PanelBody>
    );
};
```

## Best Practices

1. **Use theme.json values**: Always pull values from theme.json when possible
2. **Consistent naming**: Use clear, descriptive names for props and attributes
3. **Flexible defaults**: Make components work with minimal configuration
4. **Documentation**: Document all props and provide usage examples
5. **Single responsibility**: Each component should handle one aspect of block configuration
