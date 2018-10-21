<?php # -*- coding: utf-8 -*-

namespace TwigWordPressView\Tests\Integration\Template\Model;

use TwigWp\Factory;
use TwigWordPressView\TwigController;
use TwigWordPressView\TwigData;
use TwigWordPressView\Tests\TestCase;
use WordPressModel\Tags;
use WordPressModel\Terms;

class TagsTest extends TestCase
{
    public function testHtmlOutput()
    {
        $termsMock = \Mockery::mock('overload:' . Terms::class);
        $termsMock
            ->shouldReceive('data')
            ->andReturn($this->mockTerms());

        $postMock = \Mockery::mock('\WP_Post');
        $postMock->ID = 1;
        $postMock->post_type = 'post';

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new Tags($postMock), 'post.tags.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<section class="post-tags"><h5 class="post-tags__title">Tags:</h5><ul class="terms"><li class="term-class-0"><a href="#" class="term-class-link-0">Term 0</a></li></ul></section>',
            $output
        );
    }

    public function testEmptyHtmlOutputIfNoTermsFound()
    {
        $termsMock = \Mockery::mock('overload:' . Terms::class);
        $termsMock
            ->shouldReceive('data')
            ->andReturn([]);

        $postMock = \Mockery::mock('\WP_Post');
        $postMock->ID = 1;
        $postMock->post_type = 'post';

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new Tags($postMock), 'post.tags.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame('', $output);
    }

    private function mockTerms()
    {
        $terms = [];
        for ($count = 0; $count < 1; ++$count) {
            $terms[] = (object)[
                'name' => "Term {$count}",
                'attributes' => [
                    'class' => "term-class-{$count}",
                ],
                'link' => [
                    'attributes' => [
                        'class' => "term-class-link-{$count}",
                        'href' => '#',
                    ],
                ],
            ];
        }

        return $terms;
    }
}
