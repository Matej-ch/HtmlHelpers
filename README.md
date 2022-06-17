![latest_tag](https://badgen.net/github/tag/Matej-ch/HtmlHelpers)

### Two PHP helper classes for removing html tags and extracting file paths

---
Install in your project
```
composer require matejch/html_helpers "^1.0.0"
```
---

### class HtmlTagRemover

With class ``HtmlTagRemover`` you can remove specified html tags and its content from html string

_Example: remove tags based on name_


```php

$service = new HtmlTagRemover($htmlString);
service->removeTags(['a','img']);
```
---

_Example: remove tags based on content of attribute_
```php
$service = new HtmlTagRemover($htmlString);

$service->removeTags(['a' => 'href[files]']);

/** remove 'a' tags where href attribute contains `files` substring and span elements contains class with `test` string */
$service->removeTags(['a' => 'href[files]','span' => 'class[test]']);
```

---

### class HtmlFileExtractor

I created this class, because I needed to extract file paths from html before sending email, and add the as attachment

This class can return source from _img_ or _a_ tags automatically

But also from custom tags or attributes

_Example: get file paths from **img** and **a** tag_
```PHP
$service = new HtmlFileExtractor($htmlString);

/** return files as array of strings directly from href and src */
$service->getFiles(['img','a'])
```
---
_Example: get file paths other tags or specified attributes_
```PHP
/** return files as array of strings from class attributes of span elements */
$service->getFiles(['span' => 'class'])

$service->getFiles(['span' => 'class','img'=>'alt'])

/** return files as array of strings from class attributes of span elements, 
 * where class contains substring 'es' and from alt attribute of image tags
 */
$service->getFiles(['span' => 'class[es]','img'=>'alt'])
```
---
Remove package from your project
```php 
composer remove matejch/html_helpers
```