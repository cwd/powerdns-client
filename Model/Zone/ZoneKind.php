<?php
/*
 * This file is part of Whistleblower Plattform
 *
 * ©2021 cwd.at GmbH <office@cwd.at>
 *
 * Unauthorized copying or modification of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 */
declare(strict_types=1);

namespace Cwd\PowerDNSClient\Model\Zone;

enum ZoneKind: string
{
    case MASTER = 'Master';
    case SLAVE = 'Slave';
    case NATIVE = 'Native';
}
