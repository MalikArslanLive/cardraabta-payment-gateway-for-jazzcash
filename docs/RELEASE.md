# Release and merchant acceptance — 1.0.1

This document describes the maintenance release of **CardRaabta Payment Gateway for JazzCash**. The source in this repository corresponds to the plugin distributed at [WordPress.org](https://wordpress.org/plugins/cardraabta-payment-gateway-for-jazzcash/). Directory publication is not JazzCash merchant certification or proof of live payment acceptance.

## Installation and configuration

Install from WordPress.org or use the official plugin ZIP. In WooCommerce → Settings → Payments, configure **JazzCash Credit / Debit Card** with the merchant-issued Sandbox credentials, HTTPS page-redirection URL, Return URL and signed SOAP IPN details. **JazzCash Mobile Wallet** is an independently enabled payment method that shares the merchant configuration. Wallet URLs and merchant capabilities must be confirmed with JazzCash. Read the root README and WordPress.org readme for full setup, external-service disclosures and security boundaries.

Use a staging store with PKR currency and working public HTTPS callback routes. Exclude return/IPN endpoints from caching, authentication walls and request-body logging. Do not put real credentials in GitHub issues or CI.

## Merchant acceptance checklist

Before enabling Live payments, confirm the provider-approved merchant profile and test successful, declined, abandoned, expired, duplicate and delayed payment responses. Exercise classic checkout and Checkout Blocks, card/3DS and wallet flows, return/IPN overlap, signed SOAP acknowledgement, order totals, single fulfilment and stock handling. Reconcile a small Live payment in the JazzCash merchant portal. Keep unsigned notifications disabled.

The bundled `includes/ipn.wsdl` is a local SOAP 1.1 service contract served from the plugin's IPN endpoint for JazzCash registration; it is not executable code or an external download. Protocol namespace URLs in the WSDL are identifiers, not remote requests.

## Source, build and tests

The repository contains the complete readable runtime source, test fixtures, release tooling and CI configuration. The planned WordPress.org release uses version `1.0.1`. The GitHub source archive is **not** an installable WordPress plugin package: development files are excluded from the distribution ZIP.

Run locally with PHP 8.1+, Node.js and Python 3:

```sh
php tests/run.php
php tests/security.php
php tests/wallet.php
php tests/bootstrap.php
node --check assets/blocks.js
node tests/blocks.js
python3 tools/security_scan.py
python3 tools/build.py
```

The builder writes `dist/cardraabta-payment-gateway-for-jazzcash-1.0.1.zip` and prints its SHA-256 checksum. The `dist/` directory is ignored by Git. The GitHub Actions workflow also checks PHP syntax, performs the available synthetic regression tests, builds the ZIP and invokes WordPress Plugin Check.

For full WordPress/WooCommerce integration checks, run `wp eval-file tests/integration.php` **only on a disposable local installation** with `WP_ENVIRONMENT_TYPE=local`. It modifies settings and creates synthetic products and orders. Do not execute it on a live store.

Automated tests use synthetic transactions. They do not demonstrate actual provider authorization, public TLS delivery, merchant acceptance or settlement.

## Publishing and maintenance

Public plugin: https://wordpress.org/plugins/cardraabta-payment-gateway-for-jazzcash/

GitHub source: https://github.com/MalikArslanLive/cardraabta-payment-gateway-for-jazzcash

The WordPress.org SVN repository is the official distribution source. Keep the PHP header, version constant, readme stable tag, changelog, GitHub source and release package aligned for future releases. Do not edit a published tag in place; prepare and verify a new release version when functionality changes.
