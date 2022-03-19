<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 10/07/16
 * Time: 08:48
 */

namespace T3fx\Application\MailReader\Controller;

use T3fx\Core\Controller\AbstractActionController;

class MailReaderController extends AbstractActionController
{

    /**
     * imapfolderRepository
     *
     * @var \T3fx\Application\MailScanner\Domain\Repository\ImapfolderRepository
     */
    var $imapfolderRepository;

    /**
     * MailReaderController constructor.
     */
    public function __construct()
    {
        $this->initView('MailReader');
    }

    /**
     * The index action
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function indexAction()
    {
        return $this->view->render(
            'index.html',
            [
                'navigation' => $this->getNavigation(),

            ]
        );
    }

    /**
     * Render folders
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function folderAction() {
        return $this->view->render(
            'index.html',
            [
                'navigation' => $this->getNavigation(),
            ]
        );
    }


    protected function getNavigation()
    {
        $navigation = [

            [
                'href'    => '/MailReader/Folder',
                'caption' => 'Folder'
            ],
            [
                'href'    => '/MailReader/#',
                'caption' => 'Sender'
            ],
            [
                'href'    => '/MailReader/#',
                'caption' => 'Blacklist'
            ],
            [
                'href'    => '/MailReader/#',
                'caption' => 'Whitelist'
            ],
            [
                'href'    => '/MailReader/#',
                'caption' => 'Settings'
            ],
        ];

        return $navigation;
    }
}
