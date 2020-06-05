<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/*
Plugin Name: Top Floor Gravity Forms Authorize.net Customizations
Plugin URI: https://www.github.com/TopFloorTech/topfloor-gf-authnet
Description: Adds Invoice Number and Company (Organization) fields to the Gravity Forms Authorize.Net integration.
Version: 1.0
Author: Tony Klose, Ben McClure
License: GPL-2.0+
Text Domain: topfloor-gf-authnet
*/

add_filter( 'gform_authorizenet_transaction_pre_authorize', 'topfloor_gf_authnet_get_payment_transaction', 10, 4 );

function topfloor_gf_authnet_get_payment_transaction( $transaction, $form_data, $config, $form ) {
	$transaction->invoice_num = $form_data['invoice_number'];
	$transaction->company     = $form_data['organization_name'];

	return $transaction;
}

add_filter( 'gform_authorizenet_form_data', 'topfloor_gf_authnet_form_data', 10, 3 );

function topfloor_gf_authnet_form_data( $form_data, $form, $config ) {
	$instance                       = gf_authorizenet();
	$entry                          = GFFormsModel::create_lead( $form );
	$feed                           = $instance->get_payment_feed( $entry, $form );
	$form_data['organization_name'] = $_POST[ 'input_' . $feed['meta']['billingInformation_organization_name'] ];
	$form_data['invoice_number']    = $_POST[ 'input_' . $feed['meta']['billingInformation_invoice_number'] ];

	return $form_data;
}
