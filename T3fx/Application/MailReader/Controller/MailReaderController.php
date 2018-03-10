<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 10/07/16
 * Time: 08:48
 */

namespace T3fx\Application\MailReader\Controller;

use T3fx\Application\MailScanner\Utility\BlackListUtility;
use T3fx\Core\Controller\AbstractActionController;
use T3fx\Library\Database\Doctrine\DbConnection;

class MailReaderController extends AbstractActionController
{

    /**
     * @var \Twig_Environment
     */
    protected $view;

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
        $loader     = new \Twig_Loader_Filesystem(DOCUMENT_ROOT . 'Application/MailReader/Template');
        $this->view = new \Twig_Environment(
            $loader, array(// 'cache' => TEMP_FOLDER,
            )
        );
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


    protected function getNavigation()
    {
        $navigation = [

            [
                'href'    => '/MailReader/#',
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