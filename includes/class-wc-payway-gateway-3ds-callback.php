<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

class WC_Payway_Gateway_3DS_Callback {

	public function __construct() {
		add_action( 'woocommerce_api_payway_3ds_return', array( $this, 'handle_3ds_return' ) );
	}

	public function handle_3ds_return() {
		$order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;
		if ( ! $order_id ) {
			wp_die( 'Order ID missing.', 'Payway 3DS', array( 'response' => 400 ) );
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			wp_die( 'Invalid order.', 'Payway 3DS', array( 'response' => 400 ) );
		}

		// Retrieve the payment from Decidir API to see the final status
		if (function_exists('Payway')){
            Payway()->init_payway_request_includes();
        }
		
		try {
			$transaction_id = WC_Payway_Meta::get_order_transaction_id( $order_id, true );
			if ( !$transaction_id ) {
				throw new Exception( __('Transaction ID missing for order.', 'wc-gateway-payway') );
			}

			$api = new WC_Payway_Api_Handler();
			$result = $api->get_payment_info( $transaction_id );
			$data = $result->getDataField();

			if ( isset($data['status']) && $data['status'] === 'approved' ) {
				$order->payment_complete( $transaction_id );
				$order->add_order_note( __('🛡️ Pago validado exitosamente mediante el protocolo 3D Secure (Autenticación Bancaria).', 'wc-gateway-payway') );
				
				// Mark as 3DS authenticated
				WC_Payway_Meta::set_order_3ds_authenticated( $order_id, 'yes' );

				wp_redirect( $this->get_return_url( $order ) );
				exit;
			} else {
				$status_msg = isset($data['status']) ? $data['status'] : 'unknown';
				$order->update_status( 'failed', sprintf(__('3D Secure Challenge fallido o rechazado por el banco. Estado: %s', 'wc-gateway-payway'), $status_msg) );
				wc_add_notice( __('Pago no autorizado o rechazado durante la autenticación 3D Secure.', 'wc-gateway-payway'), 'error' );
				wp_redirect( wc_get_checkout_url() );
				exit;
			}

		} catch ( Exception $e ) {
			$order->update_status( 'failed', __('Error validando confirmación 3DS: ', 'wc-gateway-payway') . $e->getMessage() );
			wp_redirect( wc_get_checkout_url() );
			exit;
		}
	}

	private function get_return_url( $order ) {
		$return_url = $order->get_checkout_order_received_url();
		return apply_filters( 'woocommerce_get_return_url', $return_url, $order );
	}
}
new WC_Payway_Gateway_3DS_Callback();
