<?php

namespace tests;

use App\HtmlTagRemover;
use PHPUnit\Framework\TestCase;

class HtmlTagRemoverTest extends TestCase
{
    /** @test */
    public function it_removes_all_a_tags_from_html()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlTagRemover($htmlString);

        $service->removeTags('a');

        $this->assertEquals('<html><body><div><span class="test"></span></div><h1>My First Heading</h1><img src="" alt=""><p>My first paragraph.</p><img src="tests" alt=""></body></html>',$service->getHtml());
    }

    /** @test */
    public function it_removes_multiple_diferent_tags_from_html()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlTagRemover($htmlString);

        $service->removeTags(['a','img']);

        $this->assertEquals('<html><body><div><span class="test"></span></div><h1>My First Heading</h1><p>My first paragraph.</p></body></html>',$service->getHtml());
    }

    /** @test */
    public function it_removes_same_string_if_elements_is_not_found()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlTagRemover($htmlString);

        $service->removeTags(['h2']);

        $this->assertEquals('<html><body><div><span class="test"><a href="/files/"></a><a class="url" href="#url">Redirect 5</a></span></div><h1>My First Heading</h1><img src="" alt=""><p>My first paragraph.<a id="secondUrl" href="#"></a></p><img src="tests" alt=""></body></html>',$service->getHtml());
    }

    /** @test */
    public function it_removes_element_based_on_tag_value()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlTagRemover($htmlString);

        $service->removeTags(['a' => 'href[files]']);

        $this->assertEquals('<html><body><div><span class="test"><a class="url" href="#url">Redirect 5</a></span></div><h1>My First Heading</h1><img src="" alt=""><p>My first paragraph.<a id="secondUrl" href="#"></a></p><img src="tests" alt=""></body></html>',$service->getHtml());

    }

    /** @test */
    public function it_removes_elements_based_on_tag_value()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlTagRemover($htmlString);

        $service->removeTags(['a' => 'href[files]','span' => 'class[test]']);

        $this->assertEquals('<html><body><div></div><h1>My First Heading</h1><img src="" alt=""><p>My first paragraph.<a id="secondUrl" href="#"></a></p><img src="tests" alt=""></body></html>',$service->getHtml());
    }

    /** @test */
    public function it_removes_element_based_on_tag_value_when_value_not_found()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlTagRemover($htmlString);

        $service->removeTags(['img' => 'src[imager]']);

        $this->assertEquals('<html><body><div><span class="test"><a href="/files/"></a><a class="url" href="#url">Redirect 5</a></span></div><h1>My First Heading</h1><img src="" alt=""><p>My first paragraph.<a id="secondUrl" href="#"></a></p><img src="tests" alt=""></body></html>',$service->getHtml());
    }

    /** @test */
    public function it_remove_elements_based_on_tag_value_when_attribute_not_found()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlTagRemover($htmlString);

        $service->removeTags(['footer' => 'id[foot]']);

        $this->assertEquals('<html><body><div><span class="test"><a href="/files/"></a><a class="url" href="#url">Redirect 5</a></span></div><h1>My First Heading</h1><img src="" alt=""><p>My first paragraph.<a id="secondUrl" href="#"></a></p><img src="tests" alt=""></body></html>',$service->getHtml());
    }

    /** @test */
    public function it_returns_empty_string_when_empty_string_is_given()
    {
        $service = new HtmlTagRemover('');

        $service->removeTags(['a']);

        $this->assertEquals('',$service->getHtml());
    }

    private function getHtmlString(): string
    {
        return '<html><body><div><span class="test"><a href="/files/"></a><a class="url" href="#url">Redirect 5</a></span></div><h1>My First Heading</h1><img src="" alt=""><p>My first paragraph.<a id="secondUrl" href="#"></a></p><img src="tests" alt=""></body></html>';
    }
}
