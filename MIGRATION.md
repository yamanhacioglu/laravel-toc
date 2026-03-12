# Migration Guide: PHP 8.4 & 8.5 Compatibility

## Overview
This package has been updated to support PHP 8.4 and PHP 8.5 while maintaining backward compatibility with PHP 8.0, 8.1, 8.2, and 8.3.

## Changes Made

### 1. **composer.json**
- Updated PHP version constraint from `^8.0|^8.1|^8.2|^8.3` to `^8.0|^8.1|^8.2|^8.3|^8.4|^8.5`
- All dependencies (masterminds/html5, knplabs/knp-menu, cocur/slugify) are compatible with PHP 8.4 and 8.5

### 2. **Type Hinting Improvements (PHP 8.0+ Feature)**
Added comprehensive type hints to all classes for better compatibility and code quality:

#### **HtmlHelper.php**
- `determineHeaderTags()`: Added return type `: array`
- `traverseHeaderTags()`: Added return type `: ArrayIterator`
- `isFullHtmlDocument()`: Added return type `: bool`

#### **Table.php**
- Added property type declarations:
  - `private HTML5 $domParser`
  - `private MenuFactory $menuFactory`
- `__construct()`: Added parameter and return types (`: void`)
- `getMenu()`: Added parameter types and return type (`: ItemInterface`)
- `getTableContent()`: Added parameter types and return type (`: string`)

#### **MarkupFixer.php**
- Added property type declaration: `private HTML5 $htmlParser`
- `__construct()`: Added parameter and return types (`: void`)
- `fix()`: Added parameter types and return type (`: string`)

#### **UniqueSluggifier.php**
- Added property type declarations:
  - `private Slugify $slugify`
  - `private array $used`
- `__construct()`: Added parameter and return types (`: void`)
- `slugify()`: Added parameter type (`: string`) and return type (`: string`)

### 3. **Bug Fixes**
- Fixed method name casing in **MarkupFixer.php**: Changed `$node->getattribute('title')` to `$node->getAttribute('title')`
  - This was a critical issue as PHP 8.4+ enforces stricter method name handling

### 4. **Nullable Type Declarations**
- Updated constructor parameter types to use nullable syntax (`?ClassName`) for optional parameters
- This makes the code more robust and clearer about parameter expectations

## Benefits of These Updates

✅ **PHP 8.4 & 8.5 Support**: Full compatibility with latest PHP versions  
✅ **Type Safety**: Better IDE support and error detection at development time  
✅ **Static Analysis**: Improved support for static analysis tools like PHPStan and Psalm  
✅ **Performance**: Modern type hints can lead to marginal performance improvements  
✅ **Code Quality**: Clearer intent and better maintainability  
✅ **Future-Proof**: Ready for upcoming PHP versions with stricter typing  

## Testing Recommendations

```bash
# Test with different PHP versions
composer install --no-interaction

# Run your test suite
vendor/bin/phpunit

# Static analysis (optional but recommended)
vendor/bin/phpstan analyse src/
```

## Backward Compatibility

✅ All changes are backward compatible with PHP 8.0, 8.1, 8.2, and 8.3

## Version Compatibility Matrix

| PHP Version | Supported |
|-------------|-----------|
| PHP 8.0     | ✅ Yes    |
| PHP 8.1     | ✅ Yes    |
| PHP 8.2     | ✅ Yes    |
| PHP 8.3     | ✅ Yes    |
| PHP 8.4     | ✅ Yes    |
| PHP 8.5     | ✅ Yes    |

