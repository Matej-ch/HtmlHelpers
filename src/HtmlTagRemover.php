<?php

namespace App;


class HtmlTagRemover extends BaseHtmlHelper
{
    /**
     * @var string
     */
    private $html;

    /**
     * @var \DOMDocument
     */
    private $domDocument;

    public function __construct($html)
    {
        $this->domDocument = new \DOMDocument();
        $this->html = $html;
    }

    /**
     * Remove specified elements or tags from html string, if you remove parent tag all tags inside it are removed too
     *
     * @param string|array $tags
     */
    public function removeTags($tags)
    {
        if(is_string($tags)) {
            $tags = [$tags];
        }

        $this->domDocument->loadHTML('<?xml encoding="UTF-8">' . $this->html);

        if($this->isAssociative($tags)) {
            $removableElements = $this->filterRemovableElementsBasedOnAttributes($tags);
        } else {
            $removableElements = $this->filterRemovableElements($tags);
        }

        foreach ($removableElements as $element) {
            $element->parentNode->removeChild($element);
        }

        $this->domDocument->encoding = 'UTF-8';
        $this->html = $this->domDocument->saveHTML();
    }

    /**
     * Filter removable elements based only on tag or tags
     *
     * @param array $tags
     * @return array
     */
    private function filterRemovableElements(array $tags): array
    {
        $elements = $removableElements = [];
        foreach ($tags as $tag) {
            $elements[] = $this->domDocument->getElementsByTagName($tag);
        }

        foreach ($elements as $element) {
            foreach ($element as $el) {
                $removableElements[] = $el;
            }
        }

        return $removableElements;
    }

    /**
     * Advanced filtering of removable tags based on name and value
     *
     * Value search can be only partial e.g. if search is [a => 'href[files]'] then every a tag with href attribute that
     * contains string containing substring file is selected to be removed
     *
     * @param array $tags
     * @return array
     */
    private function filterRemovableElementsBasedOnAttributes(array $tags): array
    {
        $elements = $removableElements = [];
        foreach (array_keys($tags) as $tag) {
            $elements[$tag] = $this->domDocument->getElementsByTagName($tag);
        }

        foreach ($elements as $tag => $element) {

            preg_match_all("/\[(.*?)\]/", $tags[$tag], $matches);
            $attrName = str_replace( $matches[0][0],'',$tags[$tag]);
            $attrValue = $matches[1][0];

            foreach ($element as $el) {
                if ($el->hasAttributes()) {
                    foreach ($el->attributes as $attr) {
                        if($attr->name === $attrName && $this->str_contains($attr->nodeValue,$attrValue)) {
                            $removableElements[] = $el;
                        }
                    }
                }
            }
        }

        return $removableElements;
    }

    public function getHtml(): string
    {
        return $this->cleanString();
    }

    private function cleanString()
    {
        $html = preg_replace('#(<!DOCTYPE.*?>)#', '',$this->html);
        return str_replace(['<?xml encoding="UTF-8">',"\n", "\r"],"",$html);
    }
}