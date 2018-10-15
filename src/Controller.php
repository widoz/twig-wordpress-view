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
 */
interface Controller
{
    /**
     * Render Template
     *
     * @param Data $data
     * @return void
     */
    public function render(Data $data): void;
}
