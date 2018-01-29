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

class MailReaderController extends AbstractActionController
{


    public function indexAction()
    {
        // https://github.com/Homebrew/homebrew-php/issues/419
        // $auth = new \Delight\Auth\Auth($db);
    }
}