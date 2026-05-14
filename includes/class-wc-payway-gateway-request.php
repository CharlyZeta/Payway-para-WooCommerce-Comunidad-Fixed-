<?php
/**
 * @author Gerardo Maidana
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

use Automattic\WooCommerce\Utilities\NumberUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates requests to send
 */
class WC_Payway_Request {

	/**
     * @var string
     */
    const CS_DECISION = 'decision';

    /**
     * @var string
     */
    const STATUS = 'status';

    /**
     * @var string
     */
    const CS_FRAUD_DETECTION = 'fraud_detection';
	/**
     * @var array[]
     */
    const CS_DECISION_SUCCESS_VALUES = [
        WC_Payway_Cybersource_Validator_Interface::DECISION_GREEN,
        WC_Payway_Cybersource_Validator_Interface::DECISION_YELLOW
    ];

	/**
     * <code>
     * 400  malformed_request_error Error en el armado del json
     * 401  authentication_error    ApiKey Inválido
     * 402  invalid_request_error   Error por datos inválidos
     * 404  not_found_error         Error con datos no encontrados
     * 409  api_error               Error inesperado en la API REST
     * </code>
     *
     * @var array
     */
    const ERROR_CODES = [
        'unknown_error' => 0,
        'malformed_request_error' => 400,
        'authentication_error' => 401,
        'invalid_request_error' => 402,
        'not_found_error' => 404,
        'api_error' => 409
    ];

	/**
	 * Pointer to gateway making the request.
	 *
	 * @var WC_Payway_Api_Handler
	 */
	protected $api;

	/**
	 * Result from the gateway
	 *
	 * @var array
	 */
	protected $result = array();

	/**
	 * @var int[]
	 */
	protected $error_codes = array();

	/**
	 * @var string[]
	 */
	protected $error_messages = array();

	/**
	 * @var bool
	 */
	protected $success = false;

	/**
	 * if payment gets approved, then this will filled
	 * with the Payway Transaction ID
	 *
	 * @var int
	 */
	protected $transaction_id = 0;

	/**
	 * Flag for 3DS Challenge
	 *
	 * @var bool
	 */
	public $is_challenge_required = false;

	/**
	 * URL for 3DS Challenge redirect
	 *
	 * @var string
	 */
	public $challenge_url = '';

	/**
	 * Mapping of techical status codes/reason IDs to human-readable messages.
	 *
	 * @var array
	 */
	protected $friendly_messages = [
		'card_expired' => 'La tarjeta ha expirado. Por favor, verifica la fecha de vencimiento.',
		'insufficient_funds' => 'Fondos insuficientes. Por favor, intenta con otra tarjeta.',
		'security_code_error' => 'El código de seguridad (CVV) es incorrecto.',
		'card_restricted' => 'La tarjeta está restringida. Contacta a tu banco emisor.',
		'contact_cardholder' => 'Transacción rechazada por el emisor o el sistema antifraude. Contacta a tu banco o intenta con otra tarjeta.',
		'stolen_card' => 'Transacción rechazada. Contacta a tu banco emisor.',
		'invalid_amount' => 'Monto inválido para esta operación.',
		'invalid_card_number' => 'El número de tarjeta es inválido.',
		'invalid_expiry_date' => 'La fecha de expiración es inválida.',
		'3ds_auth_failed' => 'La autenticación 3D Secure ha fallado. Por favor, intenta de nuevo.',
		'cs_reject' => 'El pago no pudo ser procesado por políticas de seguridad. Por favor, intenta con otra tarjeta.',
		'cs_review_missing_url' => 'Tu pago requiere una validación adicional que no está disponible en este momento. Por favor, contacta con soporte.',
		'system_error' => 'Estamos experimentando dificultades técnicas para procesar tu pago. Por favor, intenta nuevamente en unos minutos.',
		'default' => 'No pudimos procesar tu pago. Por favor, verifica los datos e intenta nuevamente o usa otro medio de pago.'
	];

	/**
	 * Constructor.
	 * Initializes the API handler.
	 */
	public function __construct() {
		$this->api = new WC_Payway_Api_Handler();
	}

	/**
	 * Executes the payment request against the Gateway API.
	 *
	 * @param array $payment_data Structured data to be sent to the gateway.
	 * @return $this
	 * @throws \Exception If a communication or SDK error occurs.
	 */
	public function pay( $payment_data ) {
		try {
			/** @var WC_Payway_Logger */
			$logger = wc_payway_get_logger();
			$logger->debug( 'Iniciando petición de pago a Payway' );
			$logger->debug( print_r($payment_data, true) );

			/** @var \Decidir\Payment\PaymentResponse $result */
			$result = $this->api->post_payment( $payment_data );
			$logger->debug( 'Respuesta recibida de Payway:' );
			$logger->debug( print_r($result->getDataField(), true) );

			$this->set_result( $result->getDataField() );

			return $this->process_response( $result );

		} catch (\Decidir\Exception\SdkException $exception) {
			$this->set_success( false );
			$this->set_error_codes( $exception->getCode() );
			$this->set_error_messages( $this->extract_exception_message( $exception ) );

			$logger->error( 'Error de SDK de Payway (SdkException):' );
			$logger->error( 'Mensaje: ' . $exception->getMessage() );
			$logger->error( 'Datos: ' . print_r($exception->getData(), true) );

			throw $exception;

		} catch (\Exception $exception) {
			$this->set_success( false );
			$this->set_error_codes( $exception->getCode() );
			$this->set_error_messages( array( $this->get_friendly_message('system_error') ) );

			$logger->error( 'Error inesperado en proceso de pago (Exception):' );
			$logger->error( 'Mensaje: ' . $exception->getMessage() );

			throw $exception;
		}

		return $this;
	}

	/**
	 * Retrieves and formats error messages from an SDK exception.
	 *
	 * @param \Decidir\Exception\SdkException $exception
	 * @return array List of error messages.
	 */
	private function extract_exception_message( $exception ) {
		$data = $exception->getData();
		$messages = [];

		if ( isset($data['validation_errors']) && is_array( $data['validation_errors'] ) ) {
			foreach ($data['validation_errors'] as $item) {
				$messages[] = $this->get_friendly_message( $item['code'], $item['param'] );
			}
			return $messages;
		}

		// Handle top-level technical errors (e.g. 401, 400, 402)
		$technical_code = $exception->getCode();
		if ( in_array($technical_code, [400, 401, 402, 404, 409]) ) {
			return [ $this->get_friendly_message('system_error') ];
		}

		return [ $this->get_friendly_message('default') ];
	}

	/**
	 * Translates technical error codes to user-friendly messages.
	 *
	 * @param string|int $code Technical error code or ID.
	 * @param string $param Optional parameter name associated with the error.
	 * @return string Friendly message.
	 */
	protected function get_friendly_message( $code, $param = '' ) {
		$map = [
			'empty' => 'El campo %s es obligatorio.',
			'invalid' => 'El valor ingresado en %s es inválido.',
			'nan' => 'El campo %s debe ser numérico.',
			'invalid_expiry_date' => $this->friendly_messages['invalid_expiry_date'],
			'invalid_card_number' => $this->friendly_messages['invalid_card_number'],
			'system_error' => $this->friendly_messages['system_error'],
			'contact_cardholder' => $this->friendly_messages['contact_cardholder'],
			'3ds_auth_failed' => $this->friendly_messages['3ds_auth_failed'],
			// Decidir Reason IDs mapping
			'1' => $this->friendly_messages['card_expired'],
			'2' => $this->friendly_messages['contact_cardholder'],
			'3' => $this->friendly_messages['contact_cardholder'],
			'4' => $this->friendly_messages['stolen_card'],
			'5' => $this->friendly_messages['card_restricted'],
			'31' => $this->friendly_messages['security_code_error'],
			'51' => $this->friendly_messages['insufficient_funds'],
			'57' => $this->friendly_messages['card_restricted'],
			'62' => $this->friendly_messages['card_restricted'],
			'13' => $this->friendly_messages['invalid_amount'],
			// CyberSource specialized keys
			'REJECT' => $this->friendly_messages['cs_reject'],
			'MISSING_CHALLENGE_URL' => $this->friendly_messages['cs_review_missing_url'],
		];

		if ( isset( $map[$code] ) ) {
			return sprintf( $map[$code], $param );
		}

		return $this->friendly_messages['default'];
	}

	/**
	 * Processes the API response and triggers validation.
	 *
	 * @param \Decidir\Payment\PaymentResponse $response
	 * @return $this
	 */
	private function process_response( $response ) {
		$data = $response->getDataField();

		$this->validate( $data );

		if ( $this->get_success()) {
			if ( isset($data['id']) ) {
				$this->set_transaction_id( $data['id'] );
			}
		}

		return $this;
	}

	/**
	 * Orchestrates the validation of response data using multiple validators.
	 *
	 * @param array $response_data Data returned by the gateway.
	 * @return $this
	 */
	private function validate( $response_data ) {

		$validator_methods = array(
			'response_validator',
			'payment_validator'
		);

		if ( wc_payway_config_is_cs_enabled() ) {
			$validator_methods[] = 'cs_retail_validator';
		}

		foreach ($validator_methods as $method) {
			$result = $this->$method( $response_data );

			if ( ! $result->is_valid ) {
				$this->set_success( false );
				$this->set_error_codes( $result->error_codes );
				$this->set_error_messages( $result->error_messages );

				return $this;
			}
		}

		$this->set_success( true );
		$this->set_error_codes( array() );
		$this->set_error_messages( array() );
		return $this;
	}

	/**
	 * Checks if general validation errors occurred in the API response.
	 *
	 * @param array $data
	 * @return stdClass Validation result object.
	 */
	private function response_validator( $data ) {

		$is_valid = true;
		$error_messages = array();
		$error_codes = array();

		if (isset($data['validation_errors'])) {
			$is_valid = false;
			foreach ($data['validation_errors'] as $error) {
				$error_messages[] = $this->get_friendly_message($error['code'], $error['param']);
				$error_codes[] = $error['code'];
			}
		}

		return $this->validator_create_result(
			$is_valid,
			$error_messages,
			$error_codes
		);
	}

	/**
	 * Validates the specific payment status and reasons returned by Decidir.
	 *
	 * @param array $data
	 * @return stdClass Validation result object.
	 */
	private function payment_validator( $data ) {

		$is_valid = true;
		$error_messages = array();
		$error_codes = array();

		// Si el estado no es aprobado ni en revisión (3DS), es un error
		$status = isset($data['status']) ? $data['status'] : '';
		
		if ( $status !== 'approved' && $status !== 'review' ) {
			$is_valid = false;
			
			// Intentamos obtener el ID de razón técnica si existe
			$reason_id = isset($data['status_details']['error']['reason']['id']) 
				? $data['status_details']['error']['reason']['id'] 
				: 'contact_cardholder'; // Por defecto pedimos contactar al emisor
			
			$error_messages[] = $this->get_friendly_message($reason_id);
			$error_codes[] = $reason_id;
		}

		return $this->validator_create_result(
			$is_valid,
			$error_messages,
			$error_codes
		);
	}

	/**
	 * Validates CyberSource (fraud detection) results.
	 *
	 * @param array $data
	 * @return stdClass Validation result object.
	 */
	private function cs_retail_validator( $data ) {

		$is_valid = true;
		$error_messages = array();
		$error_codes = array();

		$decision_result = $this->cs_validate_decision( $data );

		if ($decision_result['errors']) {
			$is_valid = false;
			$error_messages = $error_codes = array();
			$error_messages[] = $decision_result['description'];
			$error_codes[] = $decision_result['reason_code'];
		} elseif (isset($decision_result['is_challenge']) && $decision_result['is_challenge']) {
			$this->is_challenge_required = true;
			$this->challenge_url = isset($decision_result['challenge_url']) ? $decision_result['challenge_url'] : '';
		}

		return $this->validator_create_result(
			$is_valid,
			$error_messages,
			$error_codes
		);
	}

	/**
	 * Analyzes the CyberSource decision and determines if a challenge (3DS) is needed.
	 *
	 * @param array $data
	 * @return array Result containing errors and challenge information.
	 */
	private function cs_validate_decision( array $data ) {

		$decision = array();
		$decision['errors'] = false;
		$decision['is_challenge'] = false;
		$fraud_status_result = isset($data['fraud_detection']['status'])
			? $data['fraud_detection']['status']
			: array();

		if (
			isset($fraud_status_result['decision'])
			&& !in_array($fraud_status_result['decision'], self::CS_DECISION_SUCCESS_VALUES)
		) {
			if ($fraud_status_result['decision'] === 'YELLOW' || (isset($data['status']) && $data['status'] === 'review')) {
				if (isset($fraud_status_result['review_url']) && !empty($fraud_status_result['review_url'])) {
					$decision['is_challenge'] = true;
					$decision['challenge_url'] = $fraud_status_result['review_url'];
				} else {
					$decision['errors'] = true;
					$decision['decision'] = $fraud_status_result['decision'];
					$decision['description'] = __('Transacción en revisión (3DS) pero la pasarela no proporcionó la URL de autenticación.', 'wc-gateway-payway');
					$decision['reason_code'] = 'MISSING_CHALLENGE_URL';
				}
			} else {
				$decision['errors'] = true;
				$decision['decision'] = $fraud_status_result['decision'];
				$decision['description'] = $this->get_friendly_message('contact_cardholder');
				$decision['reason_code'] = $fraud_status_result['reason_code'];
			}
		}

		return $decision;
	}

	/**
	 * Factory method to create a standardized validation result object.
	 *
	 * @param bool $is_valid Whether validation passed.
	 * @param array $messages List of user-facing messages.
	 * @param array $error_codes List of technical error codes.
	 * @return stdClass
	 */
	private function validator_create_result(
		$is_valid,
		array $messages = [],
		array $error_codes = []
	) {
		$result = new stdClass();
		$result->is_valid = (bool) $is_valid;
		$result->error_messages = $messages;
		$result->error_codes = $error_codes;
		return $result;
	}

	/**
	 * @return int[]
	 */
	public function get_error_codes() {
		return $this->error_codes;
	}

	/**
	 * @param int[] $codes
	 * @return $this
	 */
	public function set_error_codes( $codes ) {
		$this->error_codes = $codes;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function get_success() {
		return $this->success;
	}

	/**
	 *
	 * @param bool $status
	 * @return $this
	 */
	public function set_success( $status ) {
		$this->success = $status;
		return $this;
	}

	/**
	 * @return array
	 */
	public function get_error_messages() {
		return $this->error_messages;
	}

	/**
	 * @param array $message
	 * @return $this
	 */
	public function set_error_messages( $messages ) {
		$this->error_messages = $messages;
		return $this;
	}

	public function get_transaction_id() {
		return $this->transaction_id;
	}

	public function set_transaction_id( $transaction_id ) {
		$this->transaction_id = $transaction_id;
		return $this;
	}

	/**
	 * Only gets filled when request ends without exceptions
	 *
	 * @return $this
	 */
	public function get_result() {
		return $this->result;
	}

	/**
	 * Only gets called when request ends without exceptions
	 *
	 * @param array $result
	 * @return $this
	 */
	public function set_result( $result ) {
		$this->result = $result;
		return $this;
	}

}
