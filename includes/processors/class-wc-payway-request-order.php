<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

class WC_Payway_Request_Order_Processor implements WC_Payway_Request_Processor_Interface {

	/**
	 * @var string
	 */
	const AMOUNT = 'amount';

	/**
     * @var string
     */
    const CURRENCY = 'currency';

	/**
     * @var string
     */
    const ESTABLISHMENT_NAME = 'establishment_name';

	/**
     * @var string
     */
	const SITE_TRANSACTION_ID = 'site_transaction_id';

	/**
	 *
	 * @param WC_Order $order
	 * @param array $checkout_posted_data
	 * @return array
	 */
	public function process( $order, $checkout_posted_data ) {
		return $this->get_data( $order );
	}

	/**
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	private function get_data( $order ) {
		return array(
			self::AMOUNT => $this->get_amount( $order ),
			self::CURRENCY => $order->get_currency(),
			self::ESTABLISHMENT_NAME => $this->get_establishment_name(),
			self::SITE_TRANSACTION_ID => $this->get_site_transaction_id( $order )
		);
	}

	/**
	 *
	 * @param WC_Order $order
	 * @return float
	 */
	private function get_amount( $order ) {
		return (float) $order->get_total();
	}

	/**
	 * Returns the WordPress blog site title
	 *
	 * @return string
	 */
	private function get_establishment_name()
	{
		return substr( get_bloginfo('name'), 0, 25);
	}

	/**
	 * Returns the site transaction id for the current Order
	 *
	 * @param WC_Order $order
	 * @return string
	 */
	private function get_site_transaction_id( $order ) {
		return (string) $order->get_order_number() . '-' . time();
	}
}
