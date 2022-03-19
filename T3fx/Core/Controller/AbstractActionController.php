<?php
/*
 * Copyright 2018 - Steffen Hastädt
 *
 * t3fx@t3x.ch | www.t3x.ch
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace T3fx\Core\Controller;

/**
 * Class AbstractActionController
 *
 * @package T3fx\Core\Controller
 */
class AbstractActionController
{

    /**
     * @var \Twig_Environment
     */
    protected $view;

    /**
     * MailReaderController constructor.
     */
    public function initView(string $application)
    {
        $loader     = new \Twig\Loader\FilesystemLoader(DOCUMENT_ROOT . 'Application/' . $application . '/Template');
        $this->view = new \Twig\Environment(
            $loader, array(// 'cache' => TEMP_FOLDER,
            )
        );
    }

    /**
     * Index Action
     */
    public function indexAction()
    {
    }

}
