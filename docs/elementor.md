# Elementor Project Structure and Data Format

This document provides an overview of the Elementor project structure and how it handles widgets and pages. This information is crucial for our OpenPress project, which aims to convert Elementor from WordPress to Laravel.

## Project Structure

Elementor's main directory structure includes:

- `includes/`: Core functionality
- `includes/widgets/`: Widget definitions
- `assets/`: Static assets (CSS, JS, images)
- `core/`: Core components and base classes
- `modules/`: Additional features and integrations

## Widget Structure

Widgets are defined in individual PHP files within the `includes/widgets/` directory. Each widget is a separate class that extends a base widget class.

Examples of widgets:
- `accordion.php`
- `button.php`
- `image.php`
- `text-editor.php`

## Data Storage

Elementor stores its data in the WordPress database using post meta. The key findings from the `DB` class in `includes/db.php` are:

1. Elementor data is stored with the meta key `_elementor_data`.
2. The data is stored as a JSON string and needs to be slashed before saving.
3. When retrieving data, it's typically decoded from JSON to an array.

## Data Format

Elementor's data format appears to be a nested array structure, likely representing the layout and content of a page. Key points:

1. Each element has an `elType` property, which could be 'widget', 'section', or 'column'.
2. Widgets have additional properties specific to their type.
3. The structure is recursive, with elements potentially containing child elements.

Example structure (pseudo-code):

```php
[
  {
    'elType' => 'section',
    'elements' => [
      {
        'elType' => 'column',
        'elements' => [
          {
            'elType' => 'widget',
            'widgetType' => 'text-editor',
            'settings' => [
              'editor' => 'Hello, World!'
            ]
          }
        ]
      }
    ]
  }
]
```

## Rendering Process

1. Elementor iterates through the data structure.
2. For each element, it creates an instance of the corresponding widget or element class.
3. Each widget's `render()` method is called to generate the HTML output.

## Plain Text Extraction

Elementor provides methods to extract plain text content from its data structure:

1. `get_plain_text()`: Retrieves plain text for a specific post.
2. `get_plain_text_from_data()`: Extracts plain text from any Elementor data array.
3. The extraction process involves rendering the content and then stripping HTML tags.

## Considerations for OpenPress

When adapting Elementor's structure for OpenPress:

1. Implement a similar nested data structure for storing layout and content.
2. Create a widget system that mirrors Elementor's, with individual widget classes.
3. Develop a rendering engine that can interpret the nested data structure and render appropriate output.
4. Implement methods for plain text extraction, which can be useful for search functionality or excerpts.
5. Consider how to handle Elementor-specific features like inline editing and drag-and-drop in the Laravel environment.

This overview provides a starting point for understanding Elementor's structure. Further investigation into specific components and features will be necessary as we progress with the OpenPress project.