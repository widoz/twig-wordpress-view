<?php # -*- coding: utf-8 -*-

namespace TwigWordPressView\Tests\Integration\Template\Model;

use \Brain\Monkey\Functions;
use TwigWp\Factory;
use TwigWordPressView\TwigController;
use TwigWordPressView\TwigData;
use TwigWordPressView\Tests\TestCase;
use WordPressModel\PostTitle;

class PostTitleTest extends TestCase
{
    public function testHtmlOutputForNonSingularPost()
    {
        $postMock = \Mockery::mock('\WP_Post');

        $queryMock = \Mockery::mock('\WP_Query');
        $queryMock
            ->shouldReceive('is_singular')
            ->once()
            ->andReturn(false);

        Functions\expect('get_permalink')
            ->once()
            ->with($postMock)
            ->andReturn('http://permalink.org');

        Functions\expect('get_the_title')
            ->once()
            ->with($postMock)
            ->andReturn('Post Title');

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new PostTitle($postMock, $queryMock), 'post.title.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<h2 class="article__title"><a class="article__link" href="http://permalink.org">Post Title</a></h2>',
            $output
        );
    }

    public function testHtmlOutputForSingularPost()
    {
        $postMock = \Mockery::mock('\WP_Post');

        $queryMock = \Mockery::mock('\WP_Query');
        $queryMock
            ->shouldReceive('is_singular')
            ->once()
            ->andReturn(true);

        Functions\expect('get_permalink')
            ->never();

        Functions\expect('get_the_title')
            ->once()
            ->with($postMock)
            ->andReturn('Post Title');

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new PostTitle($postMock, $queryMock), 'post.title.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<h1 class="article__title">Post Title</h1>',
            $output
        );
    }
}
