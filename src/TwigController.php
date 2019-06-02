<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the Twig WordPress View Theme package.
 *
 * (c) Guido Scialfa <dev@guidoscialfa.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TwigWordPressView;

use Error;
use Twig;
use Twig\Error\Error as TwigError;
use Twig\TemplateWrapper;
use WordPressTemplate\Controller;
use WordPressTemplate\ViewModel;

/**
 * Class TemplateRender
 */
final class TwigController implements Controller
{
    /**
     * @var Twig\Environment Instance of the class
     */
    private $env;

    /**
     * TemplateRender constructor
     *
     * @param Twig\Environment $env
     */
    public function __construct(Twig\Environment $env)
    {
        $this->env = $env;
    }

    /**
     * @inheritdoc
     *
     * @param TwigData $model
     * @throws Twig\Error\LoaderError
     * @throws Twig\Error\RuntimeError
     * @throws Twig\Error\SyntaxError
     */
    public function render(ViewModel $model): void
    {
        $modelData = $model->model();
        $templatePath = $model->templatePath();

        if (!$modelData) {
            return;
        }

        $this
            ->twigLoad($templatePath)
            ->display($modelData);
    }

    /**
     * Load Template Data
     *
     * @param string $path
     * @return TemplateWrapper
     * @throws TwigError
     * @throws Twig\Error\LoaderError
     * @throws Twig\Error\RuntimeError
     * @throws Twig\Error\SyntaxError
     */
    protected function twigLoad(string $path): TemplateWrapper
    {
        try {
            return $this->env->load($path);
        } catch (Error $exc) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                throw $exc;
            }
        }
    }
}
