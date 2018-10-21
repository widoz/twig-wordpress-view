<?php # -*- coding: utf-8 -*-

// phpcs:disable

namespace TwigWordPressView\Tests\Unit\Template\Model;

use TwigWp\Factory;
use TwigWordPressView\TwigController;
use TwigWordPressView\TwigData;
use TwigWordPressView\Tests\TestCase;
use WordPressModel\Category;
use WordPressModel\Terms;

class CategoryTest extends TestCase
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
        $data = new TwigData(new Category($postMock), 'post.category.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<div class="post-category"><span class="post-category__title">Posted In:</span><ul class="terms"><li class="term-class-0"><a href="#" class="term-class-link-0">Term 0</a></li></ul></div>',
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
        $data = new TwigData(new Category($postMock), 'post.category.twig');

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
