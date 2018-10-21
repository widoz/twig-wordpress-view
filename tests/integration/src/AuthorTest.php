<?php # -*- coding: utf-8 -*-

namespace TwigWordPressView\Tests\Integration\Template\Model;

use \Brain\Monkey\Functions;
use TwigWordPressView\TwigController;
use TwigWordPressView\TwigData;
use TwigWordPressView\Tests\TestCase;
use TwigWp\Factory;
use WordPressModel\Author;

class AuthorTest extends TestCase
{
    public function testOutputHtml()
    {
        $userMock = \Mockery::mock('\WP_User');
        $userMock->ID = 1;
        $userMock->display_name = 'User Display Name';
        $userMock
            ->shouldReceive('exists')
            ->once()
            ->andReturn(true);

        Functions\expect('get_author_posts_url')
            ->once()
            ->with($userMock->ID)
            ->andReturn('http://user-page-url.org');

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new Author($userMock), 'author.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<span class="author"><a href="http://user-page-url.org" class="author__posts-page" rel="author">User Display Name</a></span>',
            $output
        );
    }

    public function testAuthorMissingPageUrlNotShowLinkInTemplate()
    {
        $userMock = \Mockery::mock('\WP_User');
        $userMock->ID = 1;
        $userMock->display_name = 'User Display Name';
        $userMock
            ->shouldReceive('exists')
            ->once()
            ->andReturn(true);

        Functions\expect('get_author_posts_url')
            ->once()
            ->with($userMock->ID)
            ->andReturn('');

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new Author($userMock), 'author.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<span class="author">User Display Name</span>',
            $output
        );
    }
}
