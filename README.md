# MageProfis_MegaMenu
Replaces the default topmenu with a custom menu.
Submenus are loaded via ajax.

### Recommendation
Remove the default css file and use "topmenu.less".
```xml
<?xml version="1.0"?>
<layout>
    <default>
        <reference name="head">
            <action method="removeItem"><type>skin_css</type><name>css/mageprofis/megamenu.css</name></action>
        </reference>
    </default>
</layout>
```

### To Do
- Get all Submenus in one request
- Create some Settings for Menu depth