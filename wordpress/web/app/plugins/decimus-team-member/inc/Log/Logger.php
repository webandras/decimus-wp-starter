<?php

namespace Gulacsi\TeamMember\Log;

defined('ABSPATH') or die();

/**
 * For logging events
 */
trait Logger
{

    public function logger(int $debug = 0, int $logging = 1): void
    {
        if ( $debug ) {
            $info_text = "Entering - " . __FILE__ . ":" . __FUNCTION__ . ":" . __LINE__;
            echo '<div class="notice notice-info is-dismissible">' . $info_text . '</p></div>';
        }
        if ( $logging ) {
            global $decimus_team_member_log;
            $decimus_team_member_log->logInfo("Entering - " . __FILE__ . ":" . __FUNCTION__ . ":" . __LINE__);
        }
    }

    public function exception_logger(int $logging = 1, object $ex = null): void
    {
        if ( $logging ) {
            global $decimus_team_member_log;
            $decimus_team_member_log->logInfo(
                $ex->getMessage() . " - " . __FILE__ . ":" . __FUNCTION__ . ":" . __LINE__
            );
        }
    }
}
