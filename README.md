### Two helper classes for working with html


### class HtmlTagRemover

With this class you can remove tags based on name 

_Example_

```php

$service = new HtmlTagRemover($htmlString);
service->removeTags(['a','img']);
```

You can remove tags based on value of attribute

_Example_
```php
$service = new HtmlTagRemover($htmlString);

$service->removeTags(['a' => 'href[files]']);

/** remove 'a' tags where href attribute contains `files` substring and span elements contains class with `test` string */
$service->removeTags(['a' => 'href[files]','span' => 'class[test]']);
```


### class HtmlFileExtractor

I built this class, because I needed to extract file sources from html before sending email

And add it to attached files

This class can return source from _img_ or _a_ tags

But also from custom tags or attributes

_Example_
```PHP
$service = new HtmlFileExtractor($htmlString);

/** return files as array of strings directly from href and src */
$service->getFiles(['img','a'])


/** return files as array of strings from class attributes of span elements */
$service->getFiles(['span' => 'class'])

$service->getFiles(['span' => 'class','img'=>'alt'])

/** return files as array of strings from class attributes of span elements, 
 * where class contains substring 'es' and from alt attribute of image tags
 */
$service->getFiles(['span' => 'class[es]','img'=>'alt'])
```