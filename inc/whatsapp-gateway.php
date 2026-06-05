<?php
/**
 * Order via WhatsApp payment gateway.
 *
 * Records the order in WooCommerce (so all order data is tracked and
 * exportable), then sends the customer to WhatsApp with a prefilled
 * order summary for the store owner to confirm payment and delivery.
 *
 * @package TechPlugGH
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! class_exists( 'WC_Payment_Gateway' ) ) { return; }

class TPG_Gateway_WhatsApp extends WC_Payment_Gateway {

	public function __construct() {
		$this->id                 = 'tpg_whatsapp';
		$this->has_fields         = false;
		$this->method_title       = __( 'Order via WhatsApp', 'techpluggh' );
		$this->method_description = __( 'Saves the order in WooCommerce, then redirects the customer to WhatsApp with the order summary (order number, products, prices, total).', 'techpluggh' );

		$this->init_form_fields();
		$this->init_settings();
		$this->title       = $this->get_option( 'title' );
		$this->description = $this->get_option( 'description' );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_whatsapp' ) );
	}

	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable', 'techpluggh' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Order via WhatsApp', 'techpluggh' ),
				'default' => 'yes',
			),
			'title' => array(
				'title'   => __( 'Title shown at checkout', 'techpluggh' ),
				'type'    => 'text',
				'default' => __( 'Order via WhatsApp', 'techpluggh' ),
			),
			'description' => array(
				'title'   => __( 'Description shown at checkout', 'techpluggh' ),
				'type'    => 'textarea',
				'default' => __( 'Place your order and finish on WhatsApp. Pay by MoMo, bank transfer, or on delivery within Accra.', 'techpluggh' ),
			),
			'whatsapp' => array(
				'title'       => __( 'WhatsApp number or profile link', 'techpluggh' ),
				'type'        => 'text',
				'description' => __( 'International format e.g. 233XXXXXXXXX, or a wa.me link. Leave empty to use the number from Appearance > Customize > TechPlug GH Settings > Contact & Social.', 'techpluggh' ),
				'default'     => '',
			),
			'status' => array(
				'title'   => __( 'New order status', 'techpluggh' ),
				'type'    => 'select',
				'default' => 'on-hold',
				'options' => array(
					'on-hold'    => __( 'On hold (recommended: awaiting your confirmation)', 'techpluggh' ),
					'pending'    => __( 'Pending payment', 'techpluggh' ),
					'processing' => __( 'Processing', 'techpluggh' ),
				),
			),
		);
	}

	/** Digits-only WhatsApp number from the gateway setting or Customizer fallback. */
	private function wa_number() {
		$raw = trim( (string) $this->get_option( 'whatsapp' ) );
		if ( '' === $raw && function_exists( 'tpg_wa_number' ) ) {
			$raw = tpg_wa_number();
		}
		if ( preg_match( '~(?:wa\.me/|api\.whatsapp\.com/send[^ ]*phone=)\+?(\d+)~i', $raw, $m ) ) {
			return $m[1];
		}
		return preg_replace( '/\D+/', '', $raw );
	}

	/** Hide the gateway until a WhatsApp number is configured somewhere. */
	public function is_available() {
		return parent::is_available() && '' !== $this->wa_number();
	}

	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		$order->update_status(
			$this->get_option( 'status', 'on-hold' ),
			__( 'Order placed via WhatsApp checkout. Awaiting confirmation on WhatsApp.', 'techpluggh' )
		);
		wc_reduce_stock_levels( $order_id );
		WC()->cart->empty_cart();
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order ),
		);
	}

	/** Plain-text order summary for the WhatsApp message. */
	public function order_message( $order ) {
		$money = function ( $amount ) use ( $order ) {
			return html_entity_decode( wp_strip_all_tags( wc_price( $amount, array( 'currency' => $order->get_currency() ) ) ), ENT_QUOTES, 'UTF-8' );
		};
		$lines   = array();
		$lines[] = 'Hello TechPlug GH, I just placed order #' . $order->get_order_number() . ' on the website.';
		$lines[] = '';
		foreach ( $order->get_items() as $item ) {
			$lines[] = '- ' . $item->get_name() . ' x' . $item->get_quantity() . ' = ' . $money( $item->get_total() );
		}
		if ( (float) $order->get_shipping_total() > 0 ) {
			$lines[] = '- Delivery = ' . $money( $order->get_shipping_total() );
		}
		$lines[] = '';
		$lines[] = 'Total: ' . $money( $order->get_total() );
		$lines[] = 'Name: ' . $order->get_formatted_billing_full_name();
		if ( $order->get_billing_phone() ) {
			$lines[] = 'Phone: ' . $order->get_billing_phone();
		}
		return implode( "\n", $lines );
	}

	public function wa_url( $order ) {
		return 'https://wa.me/' . $this->wa_number() . '?text=' . rawurlencode( $this->order_message( $order ) );
	}

	/** Thank-you page: confirm the order, then hand off to WhatsApp. */
	public function thankyou_whatsapp( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order || '' === $this->wa_number() ) { return; }
		$url = $this->wa_url( $order );
		?>
		<div class="card" style="padding:2rem;margin:1.5rem 0;text-align:center">
			<h2 class="font-display" style="font-size:1.5rem;font-weight:700;margin-bottom:.5rem"><?php esc_html_e( 'One last step: send your order on WhatsApp', 'techpluggh' ); ?></h2>
			<p style="color:#9AA8A1;max-width:34rem;margin:0 auto 1.25rem"><?php esc_html_e( 'Your order is saved. Tap the button to send it to us on WhatsApp so we can confirm payment and arrange delivery or pickup.', 'techpluggh' ); ?></p>
			<a class="btn-wa" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Send order on WhatsApp', 'techpluggh' ); ?></a>
			<p style="color:#9AA8A1;font-size:.8rem;margin-top:1rem"><?php esc_html_e( 'Opening WhatsApp automatically...', 'techpluggh' ); ?></p>
		</div>
		<script>setTimeout(function(){ window.location.href = <?php echo wp_json_encode( $url ); ?>; }, 2500);</script>
		<?php
	}
}

add_filter( 'woocommerce_payment_gateways', function ( $methods ) {
	$methods[] = 'TPG_Gateway_WhatsApp';
	return $methods;
} );
