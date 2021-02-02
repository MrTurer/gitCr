<?php
defined('B_PROLOG_INCLUDED') || die;

Bitrix\Main\Loader::registerAutoloadClasses(
	"industrial.office",
	[
		#'RNS\Industrial\Office\Handler\Epilog'=>'lib/handlers/epilog.php',
		'RNS\Industrial\Office\Helpers\Helper'=>'lib/helpers/helper.php'
	]
);