<?php
namespace matejch\html;

class HtmlFileExtractor extends BaseHtmlHelper
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
     * @param string|array $tags
     * @return array
     */
    public function getFiles($tags): array
    {
        if(is_string($tags)) {
            $tags = [$tags];
        }

        $fileArray = [];

        if(empty($this->html) || empty($tags)) {
            return $fileArray;
        }

        $this->domDocument->loadHTML($this->html);

        if($this->isAssociative($tags)) {
            $fileArray = $this->filterFilesAdvanced($tags);
        } else {
            $fileArray = $this->filterFiles($tags);
        }

        return $fileArray;
    }

    private function filterFiles($tags): array
    {
        $fileArray = $elements = [];
        foreach ($tags as $tag) {
            $elements[$tag] = $this->domDocument->getElementsByTagName($tag);
        }

        $lookup = ['a' => 'href','img' => 'src'];

        foreach ($elements as $tag => $element) {
            foreach ($element as $el) {
                if ($el->hasAttributes()) {
                    foreach ($el->attributes as $attr) {
                        if(!empty($attr->nodeValue) && $attr->name === $lookup[$tag]) {
                            $fileArray[] = $attr->nodeValue;
                        }
                    }
                }
            }
        }

        return $fileArray;
    }

    private function filterFilesAdvanced($tags): array
    {
        $fileArray = $elements = [];
        foreach (array_keys($tags) as $tag) {
            $elements[$tag] = $this->domDocument->getElementsByTagName($tag);
        }

        foreach ($elements as $tag => $element) {

            preg_match_all("/\[(.*?)\]/", $tags[$tag], $matches);
            $attrName = str_replace($matches[0][0] ?? '','',$tags[$tag]);
            $attrValue = $matches[1][0] ?? '';

            foreach ($element as $el) {
                if ($el->hasAttributes()) {
                    foreach ($el->attributes as $attr) {
                        if(!empty($attrValue)) {
                            if($attr->name === $attrName && $this->str_contains($attr->nodeValue,$attrValue)) {
                                $fileArray[] =  $attr->nodeValue;
                            }
                        } elseif(!empty($attr->nodeValue) && $attr->name === $tags[$tag]) {
                            $fileArray[] = $attr->nodeValue;
                        }
                    }
                }
            }
        }

        return $fileArray;
    }
}