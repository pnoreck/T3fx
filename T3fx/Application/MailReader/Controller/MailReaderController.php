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
     * @see https://github.com/delight-im/PHP-Auth
     *
     * @return void
     */
    public function indexAction()
    {
        /** @var DbConnection $db */
        $db = DbConnection::getInstance();
        $conn = $db->getConnection();

        $auth = new \Delight\Auth\Auth($conn);
    }
}