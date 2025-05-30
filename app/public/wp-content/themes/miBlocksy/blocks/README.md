# Custom Blocks Directory

This directory contains custom Gutenberg blocks for the miBlocksy theme.

## Block Structure

Each block should follow this structure:

```
block-name/
├── block.json          # Block configuration
├── index.js           # Block registration
├── edit.js            # Editor component
├── save.js            # Save component (for static blocks)
├── render.php         # Server-side rendering (for dynamic blocks)
├── style.scss         # Frontend styles
└── editor.scss        # Editor styles
```

## Creating a New Block

1. Create a new directory with your block name
2. Add the required files based on your block type
3. Register the block in the main `functions.php` file
4. Build your assets if using a build process

## Block Categories

All custom blocks should use the `miblocksy` category defined in the theme.

## Examples

See the existing Villa Community theme blocks for reference patterns and best practices.
