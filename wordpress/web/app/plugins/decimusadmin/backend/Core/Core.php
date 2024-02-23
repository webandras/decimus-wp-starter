<?php

namespace Guland\DecimusAdmin\Core;

use Guland\DecimusAdmin\Core\AdminPage as AdminPage;


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Core {
	use AdminPage;
}
