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

/**
 * Class Template
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class Template
{
    /**
     * @var TwigController
     */
    private $controller;

    /**
     * Template constructor.
     *
     * @param Controller $controller
     */
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @param Data $data
     */
    public function render(Data $data)
    {
        $this->controller->render($data);
    }
}
