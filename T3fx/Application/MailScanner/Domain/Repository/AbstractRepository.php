<?php
/*
 * Copyright 2017 - Steffen Hastädt
 *
 * T3Fx Application Development
 *
 * info@t3x.ch | www.t3x.ch
 */


namespace T3fx\Application\MailScanner\Domain\Repository;

/**
 * Class BlackListRepository
 *
 * @package T3fx\Application\MailScanner\Domain\Repository
 */
class AbstractRepository extends \T3fx\Domain\Repository\StandardRepository
{

    const HIDDEN_FIELDS = ' AND deleted = 0 AND hidden = 0';

}
