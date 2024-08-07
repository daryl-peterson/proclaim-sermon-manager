<?php

namespace DRPPSM;

function app(): App {
	return App::init();
}

function app_get( string $item ) {
	return App::init()->get( $item );
}
