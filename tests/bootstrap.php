<?php
/** Verify the duplicate-load guard without relying on obsolete plugin identifiers. */
if ( PHP_SAPI !== 'cli' ) { exit; }
define( 'ABSPATH', __DIR__ );
define( 'AGJC_FILE', '/synthetic/already-loaded-gateway.php' );
function add_action( $hook, $callback ) { throw new RuntimeException( 'Unexpected hook registration: ' . $hook ); }
function add_filter( $hook, $callback ) { throw new RuntimeException( 'Unexpected filter registration: ' . $hook ); }
require __DIR__ . '/../cardraabta-payment-gateway-for-jazzcash.php';
if ( AGJC_FILE !== '/synthetic/already-loaded-gateway.php' || defined( 'AGJC_VERSION' ) ) {
    throw new RuntimeException( 'Duplicate-load guard failed.' );
}
echo "PASS: duplicate-load guard prevents repeated plugin initialization\n";
