<?php


namespace tests;

use App\HtmlFileExtractor;
use PHPUnit\Framework\TestCase;

class HtmlFileExtractorTest extends TestCase
{
    /** @test */
    public function it_gets_filepath_from_atag()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlFileExtractor($htmlString);

        $result = [
            '/files/',
            '#url',
            '#'
        ];

        $this->assertEquals($result,$service->getFiles('a'));
    }

    /** @test */
    public function it_gets_filepath_from_img_tag()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlFileExtractor($htmlString);

        $result = [
            'img_source',
            'tests',
        ];

        $this->assertEquals($result,$service->getFiles('img'));
    }

    /** @test */
    public function it_gets_filepaths_from_multiple_types_of_tags()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlFileExtractor($htmlString);

        $result = [
            'img_source',
            'tests',
            '/files/',
            '#url',
            '#'
        ];

        $this->assertEquals($result,$service->getFiles(['img','a']));
    }

    /** @test */
    public function it_return_empty_file_array()
    {
        $service = new HtmlFileExtractor('');

        $this->assertEmpty($service->getFiles('img'));

        $service = new HtmlFileExtractor($this->getHtmlString());
        $this->assertEmpty($service->getFiles(['img' => 'not_existing']));
    }

    /** @test */
    public function it_gets_files_from_other_attributes()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlFileExtractor($htmlString);
        $result = [
            'bored',
            'test',
        ];

        $this->assertEquals($result,$service->getFiles(['span' => 'class']));
    }

    /** @test */
    public function it_get_files_from_other_attributes_multiple_tags()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlFileExtractor($htmlString);
        $result = [
            'bored',
            'test',
            'altertion/164'
        ];

        $this->assertEquals($result,$service->getFiles(['span' => 'class','img'=>'alt']));
    }

    /** @test */
    public function it_gets_files_with_specified_name_from_tag()
    {
        $htmlString = $this->getHtmlString();
        $service = new HtmlFileExtractor($htmlString);
        $result = [
            'test',
            'altertion/164'
        ];

        $this->assertEquals($result,$service->getFiles(['span' => 'class[es]','img'=>'alt']));
    }

    private function getHtmlString(): string
    {
        return '<html><body><span class="bored"></span><div><span class="test"><a href="/files/"></a><a class="url" href="#url">Redirect 5</a></span></div><h1>My First Heading</h1><img src="img_source" alt=""><p>My first paragraph.<a id="secondUrl" href="#"></a></p><img src="tests" alt="altertion/164"></body></html>';
    }
}
