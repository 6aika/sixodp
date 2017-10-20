# Sixodp Styleguide
This file will describe the main conventions used in this project.

N.B. Many parts of the codebase do not comply with this guide. However the guide should be followed when implementing 
new features or upon refactoring existing code.

## SCSS / CSS

The project generally follows Bootstrap 3 conventions with some improvements. If something is not included in this 
guide, please refer to Bootstrap-guidelines: https://getbootstrap.com/docs/3.3/css/
 

* Use only class name selectors. However there are special cases when you may need to deviate from this: e.g. 
some CKAN-templates may require use the id-selector.
```
  // Good
  .btn-primary {
    background: $brand-primary;
  }
  
  // Bad
  #form-submit-button {
    background: $brand-primary;
  }
```

* Use SASS variables when possible to avoid need for multiple modifications when the theme is updated.
```
  // Good
  .btn {
    border-radius: $border-radius-small;
    background: $brand-lightgray;
    color: $brand-gray;
  }
  
  // Bad
  .btn-primary {
    border-radius: 3px;
    background: #F8F8F8;
    color: #FFFFFF;
  }
```

* Use extend and class name modifiers when naming base class variants to avoid the need for class nesting e.g. in 
situations where the base styles stay the same but a highlight color or new state is needed.
```
  // Good
  // HTML: <div class="sidebar-item--highlight">...</div>
  .sidebar-item {
    padding: 10px 10px;
    border-radius: 3px;
    background: $brand-gray;
  }
  
  .sidebar-item--highlight {
    @extend .sidebar-item;
    background: $brand-primary;
  }
  
  // Bad
  .sidebar-item {
      padding: 10px 10px;
      border-radius: 3px;
      background: $brand-gray;
    }
    
  .sidebar-item-highlight {
    padding: 10px 10px;
    border-radius: 3px;
    background: $brand-primary;
  }
  
  // Bad
  // HTML: <div class="sidebar-item sidebar-item-highlight">...</div>
  .sidebar-item {
    padding: 10px 10px;
    border-radius: 3px;
    background: $brand-gray;
    
    &.sidebar-item-highlight {
      background: $brand-primary;
    }
  }
```

* Use hyphen (-) when naming classes which consist of multiple words. Sometimes the use of double underscore (__) is 
encouraged to depict standalone styles, but this should be avoided since most of the project already follows 
Bootstrap 2/3 conventions due to CKAN and would require extensive rewrite to make uniform.
```
  // Good
  .btn-primary {
    background: $brand-primary;
  }
  
  // Bad
  .btn__primary {
    background: $brand-primary;
  }
```

* Use abbreviations only with the most generic elements to avoid misconceptions and to improve readability.
```
  // Good
  .sidebar-item {
    background: $brand-gray;
  }
  
  // Bad
  .sbar-item {
    background: $brand-gray;
  }
```

* Utilize mixins and placeholder classes to more easily follow the theme style and to avoid repetitive code (DRY)
```
  // Good
  @mixin box-shadow($box: 0 1px 6px, $rgba: rgba(0,0,0,0.2)) {
    -moz-box-shadow: $box $rgba;
    -webkit-box-shadow: $box $rgba;
    ...
    box-shadow: $box $rgba;
  }
  
  %box-shadow {
    @include box-shadow();
  }
  
  %box-shadow--light {
    @include box-shadow($rgba: rgba(255,255,255,0.6));
  }
  
  .card {
    @extend %box-shadow;
  }
  
  .card--light {
    @extend %box-shadow--light;
  }
  
  // Bad
  .card {
    -moz-box-shadow: 1px 6px rgba(0,0,0,0.2);
    -webkit-box-shadow: 1px 6px rgba(0,0,0,0.2);
    ...
    box-shadow: 1px 6px rgba(0,0,0,0.2);
  }
  
  .card--light {
    -moz-box-shadow: 3px 4px rgba(255,255,255,0.6);
    -webkit-box-shadow: 3px 4px rgba(255,255,255,0.6);
    ...
    box-shadow: 3px 4px rgba(255,255,255,0.6);
  }
```