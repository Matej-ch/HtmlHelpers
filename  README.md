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

/** remove a tags where href attribute contains `files` and span elements contains class with `test` string */
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

/** return directly from href and src */
$service->getFiles(['img','a'])


/** returns from span elements from class attributes */
$service->getFiles(['span' => 'class'])

$service->getFiles(['span' => 'class','img'=>'alt'])

$service->getFiles(['span' => 'class[es]','img'=>'alt'])
```