<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Auth;

use EaseBaidu\Kernel\Exceptions\RuntimeException;
use EaseBaidu\Kernel\Ticket;

class VerifyTicket extends Ticket
{
    /**
     * @return string
     */
    protected function getCacheKey(): string
    {
        return 'easebaidu.smart_tp.ticket.'.$this->app['config']['client_id'];
    }
}