<?php

namespace Decimus\Team_member\Interface;

defined('ABSPATH') or die();

interface Member_interface
{
    const TEXT_DOMAIN = 'decimus-team-member';
    const TABLE_NAME = 'decimus_team_member';
    const DB_VERSION = '1.0';
    const EXPORT_FILENAME = 'decimus-team-members-filename';
    const DEBUG = 0;
    const LOGGING = 1;

}
