<?php
/** Prevent a fatal collision during validation when another gateway implementation is active. */
if ( PHP_SAPI !== 'cli' ) { exit; }
define( 'ABSPATH', __DIR__ );
define( 'AGJC_FILE', '/synthetic/conflicting-gateway.php' );
$activation = null;
function register_activation_hook( $file, $callback ) { global $activation; $activation = $callback; }
function is_plugin_active( $path ) { global $test_active_path; return $path === $test_active_path; }
function esc_html__( $text, $domain ) { return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' ); }
function wp_die( $message ) { throw new RuntimeException( $message ); }
require __DIR__ . '/../cardraabta-payment-gateway-for-jazzcash.php';
if ( AGJC_FILE !== '/synthetic/conflicting-gateway.php' || ! is_callable( $activation ) ) {
    throw new RuntimeException( 'Bootstrap collision guard failed.' );
}
echo "PASS: conflicting gateway bootstrap retained without duplicate classes/constants\n";
foreach ( array(
    'jazzcash-card-gateway/jazzcash-card-gateway.php',
    'arslan-card-gateway-jazzcash/arslan-card-gateway-jazzcash.php',
    'payment-gateway-for-jazzcash/payment-gateway-for-jazzcash.php',
    'cardraabta-jazzcash-payment-gateway-for-woocommerce/cardraabta-jazzcash-payment-gateway-for-woocommerce.php',
    'cardraabta-payment-gateway-for-jazzcash-and-woocommerce/cardraabta-payment-gateway-for-jazzcash-and-woocommerce.php',
) as $test_active_path ) {
    $rejected = false;
    try { $activation(); } catch ( RuntimeException $e ) { $rejected = str_contains( $e->getMessage(), 'Deactivate' ); }
    if ( ! $rejected ) { throw new RuntimeException( 'Conflicting-gateway activation was not rejected: ' . $test_active_path ); }
    echo "PASS: conflicting-gateway activation rejected: " . $test_active_path . "\n";
}
$test_active_path = 'cardraabta-payment-gateway-for-jazzcash/cardraabta-payment-gateway-for-jazzcash.php';
$activation();
echo "PASS: current plugin basename is not rejected as a conflicting gateway\n";
