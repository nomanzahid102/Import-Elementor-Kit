# Import Elementor KIT

This script allows you to import an Elementor kit (EKIT) into your WordPress site. The import functionality is built into a class named `ImportElementorKIT` which can be easily integrated into your theme or plugin.

## Requirements

- WordPress
- Elementor (must be activated)

## Installation

1. **Add the Class**: Copy the `ImportElementorKIT` class into your theme's `functions.php` file or a custom plugin.

2. **File Path**: Ensure the `$file_path` variable in the `import_ekit_fun` method points to your Elementor kit file. For example:
   ```php
   $file_path = get_template_directory() . '/includes/demo/content/elementor-kit.zip';
3. **Initialize the Class:** Uncomment the line where the class is instantiated at the end of the script:
  ```php 
    //new ImportElementorKIT();

