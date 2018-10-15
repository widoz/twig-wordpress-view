<?php # -*- coding: utf-8 -*-

namespace TwigWordPressView\Tests\Integration\Template\Model;

use \Brain\Monkey\Functions;
use TwigWp\Factory;
use TwigWordPressView\TwigController;
use TwigWordPressView\TwigData;
use TwigWordPressView\Tests\TestCase;
use WordPressModel\PostPublishedDate;

class PostPublishedDateTest extends TestCase
{
    public function testHtmlOutput()
    {
        $postMock = \Mockery::mock('\WP_Post');
        $dateC = date('c');
        $dateFjY = date('F j, Y');
        $datelFjYgia = date('l, F j, Y g:i a');
        $expected = sprintf(
            '<div class="article-published-date"><span>Published On</span><a href="http://url.com" class="article-published-date__link"><time title="%1$s" datetime="%2$s">%3$s</time></a></div>',
            $datelFjYgia,
            $dateC,
            $dateFjY
        );

        Functions\expect('get_day_link')
            ->once()
            ->andReturn('http://url.com');

        Functions\expect('get_the_time')
            ->once()
            ->with('Y', $postMock)
            ->andReturn('');

        Functions\expect('get_the_time')
            ->once()
            ->with('m', $postMock)
            ->andReturn('');

        Functions\expect('get_the_time')
            ->once()
            ->with('d', $postMock)
            ->andReturn('');

        Functions\expect('get_the_time')
            ->once()
            ->with('c', $postMock)
            ->andReturn($dateC);

        Functions\expect('get_the_date')
            ->once()
            ->with('', $postMock)
            ->andReturn($dateFjY);

        Functions\expect('get_the_date')
            ->once()
            ->with('l, F j, Y g:i a', $postMock)
            ->andReturn($datelFjYgia);

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new PostPublishedDate($postMock), 'post.publishedDate.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame($expected, $output);
    }
}
