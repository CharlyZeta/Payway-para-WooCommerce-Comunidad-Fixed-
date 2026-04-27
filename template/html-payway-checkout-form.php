<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

$gateway_identifier = $this->id;
$gateway_field_id = $gateway_identifier . '_';
$description = $this->get_option('description');
$cart_total = $this->get_order_total();

?>
<fieldset id="<?php echo $gateway_identifier; ?>-cc-form" class="wc-gateway-payway" data-cart-total="<?php echo esc_attr( $cart_total ); ?>">
	<?php if (!empty($description)) : ?>
		<hr style="border: 1px solid #ddd;">
		<p class="fieldset-description">
			<?php echo $description; ?>
		</p>
		<hr style="border: 1px solid #ddd; margin: 20px 0;">
	<?php endif; ?>
	<div id="payway_error_container" style="display:none; color: #a00; background: #fff1f1; padding: 15px; border: 1px solid #a00; margin-bottom: 20px; font-weight: bold;"></div>
	
	<div class="fields-wrapper">
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_bank"><?php echo __('Bank', 'wc-gateway-payway'); ?> <span class="required">*</span></label>
			<select id="<?php echo $gateway_field_id; ?>cc_bank"
				class="input-text wc-credit-card-form-cc-bank"
				name="<?php echo $gateway_field_id; ?>cc_bank">
				<option value="">Por favor seleccione...</option>
			</select>
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_type"><?php echo __('Card', 'wc-gateway-payway'); ?> <span class="required">*</span></label>
			<select id="<?php echo $gateway_field_id; ?>cc_type"
				class="input-text wc-credit-card-form-cc-type"
				name="<?php echo $gateway_field_id; ?>cc_type">
				<option value="">Por favor seleccione...</option>
			</select>
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_installments"><?php echo __('Installments', 'wc-gateway-payway'); ?> <span class="required">*</span></label>
			<select id="<?php echo $gateway_field_id; ?>cc_installments"
				class="input-text wc-credit-card-form-cc-installments"
				name="<?php echo $gateway_field_id; ?>cc_installments">
				<option value="">Por favor seleccione...</option>
			</select>
		</div>

		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_number"><?php echo __('Credit Card Number', 'wc-gateway-payway'); ?> <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_number"
				name="<?php echo $gateway_field_id; ?>cc_number"
				placeholder="XXXX XXXX XXXX XXXX"
				class="input-text"
				autocomplete="off" />
		</div>

		<div class="form-row form-row-half validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_exp_month"><?php echo __('Card Expiration Month', 'wc-gateway-payway'); ?> <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_exp_month"
				name="<?php echo $gateway_field_id; ?>cc_exp_month"
				placeholder="MM"
				class="input-text"
				autocomplete="off" />
		</div>
		<div class="form-row form-row-half validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_exp_year"><?php echo __('Card Expiration Year', 'wc-gateway-payway'); ?> <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_exp_year"
				name="<?php echo $gateway_field_id; ?>cc_exp_year"
				placeholder="AA"
				class="input-text"
				autocomplete="off" />
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_cid">CVV: <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_cid"
				name="<?php echo $gateway_field_id; ?>cc_cid"
				placeholder="XXX"
				class="input-text"
				autocomplete="off" />
		</div>

		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_holder_name"><?php echo __('Holder Name', 'wc-gateway-payway'); ?> <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_holder_name"
				name="<?php echo $gateway_field_id; ?>cc_holder_name"
				placeholder="<?php echo __('Holder Name', 'wc-gateway-payway'); ?>"
				class="input-text"
				autocomplete="off" />
		</div>

		<div class="form-row form-row-half validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_doc_type"><?php echo __('Document Type', 'wc-gateway-payway'); ?> <span class="required">*</span></label>
			<select id="<?php echo $gateway_field_id; ?>cc_doc_type" name="<?php echo $gateway_field_id; ?>cc_doc_type">
				<option selected value="dni">DNI</option>
			</select>
		</div>
		<div class="form-row form-row-half validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_doc_number"><?php echo __('Document Number', 'wc-gateway-payway'); ?> <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_doc_number"
				name="<?php echo $gateway_field_id; ?>cc_doc_number"
				class="input-text"
				autocomplete="off" />
		</div>

		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_holder_street"><?php echo __('Domicilio (Calle)', 'wc-gateway-payway'); ?> <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_holder_street"
				name="<?php echo $gateway_field_id; ?>cc_holder_street"
				placeholder="Ej: Av. Siempre Viva"
				class="input-text"
				autocomplete="off" />
		</div>

		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_holder_door_number"><?php echo __('Altura', 'wc-gateway-payway'); ?> <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_holder_door_number"
				name="<?php echo $gateway_field_id; ?>cc_holder_door_number"
				placeholder="Ej: 123"
				class="input-text"
				autocomplete="off" />
		</div>
		
		<input type="hidden" id="<?php echo $gateway_field_id; ?>cc_token" name="<?php echo $gateway_field_id; ?>cc_token" />
		<input type="hidden" id="<?php echo $gateway_field_id; ?>cc_bin" name="<?php echo $gateway_field_id; ?>cc_bin" />
		<input type="hidden" id="<?php echo $gateway_field_id; ?>cc_last_digits" name="<?php echo $gateway_field_id; ?>cc_last_digits" />
		<input type="hidden" id="<?php echo $gateway_field_id; ?>device_fingerprint" name="<?php echo $gateway_field_id; ?>device_fingerprint" />
	</div>
	<div class="clear"></div>
</fieldset>

<script type="text/javascript">
(function($) {
    var PaywayHandler = {
        fid: '<?php echo $gateway_field_id; ?>',
        isTokenized: false,

        init: function() {
            console.log('PaywayHandler: Initializing...');
            this.bindEvents();
            this.loadBanks();
        },

        bindEvents: function() {
            var self = this;
            $('#' + this.fid + 'cc_bank').on('change', this.onBankChange.bind(this));
            $('#' + this.fid + 'cc_type').on('change', this.onCardChange.bind(this));
            
            $(document.body).on('updated_checkout', function() {
                console.log('PaywayHandler: Checkout updated');
                self.loadBanks();
            });

            // Interceptar el envío del formulario de WooCommerce
            $('form.checkout').on('checkout_place_order_' + '<?php echo $gateway_identifier; ?>', function() {
                if (self.isTokenized) return true;
                self.processTokenization();
                return false;
            });
        },

        loadBanks: function() {
            var $bankSelect = $('#' + this.fid + 'cc_bank');
            var banks = wc_gateway_payway_params.promotions.banks;
            
            $bankSelect.empty().append('<option value="">Por favor seleccione...</option>');
            if (banks) {
                $.each(banks, function(i, bank) {
                    $bankSelect.append($('<option>', { value: bank.value, text: bank.name }));
                });
            }
        },

        onBankChange: function() {
            var bankId = $('#' + this.fid + 'cc_bank').val();
            var $cardSelect = $('#' + this.fid + 'cc_type');
            var $insSelect = $('#' + this.fid + 'cc_installments');

            $cardSelect.empty().append('<option value="">Por favor seleccione...</option>');
            $insSelect.empty().append('<option value="">Por favor seleccione...</option>');

            if (bankId && wc_gateway_payway_params.promotions.cards[bankId]) {
                $.each(wc_gateway_payway_params.promotions.cards[bankId], function(i, card) {
                    $cardSelect.append($('<option>', { value: card.value, text: card.name }));
                });
            }
        },

        onCardChange: function() {
            var bankId = $('#' + this.fid + 'cc_bank').val();
            var cardId = $('#' + this.fid + 'cc_type').val();
            var $insSelect = $('#' + this.fid + 'cc_installments');
            var cartTotal = parseFloat($('#<?php echo $gateway_identifier; ?>-cc-form').data('cart-total')) || 0;

            $insSelect.empty().append('<option value="">Por favor seleccione...</option>');

            if (bankId && cardId && wc_gateway_payway_params.promotions.plans[bankId][cardId]) {
                $.each(wc_gateway_payway_params.promotions.plans[bankId][cardId], function(i, plan) {
                    var total = cartTotal * (parseFloat(plan.coefficient) || 1);
                    var cuota = total / parseInt(plan.fee_period);
                    var text = plan.fee_period + ' x ' + accounting.formatMoney(cuota, wc_gateway_payway_accounting_format) + ' (' + accounting.formatMoney(total, wc_gateway_payway_accounting_format) + ')';
                    $insSelect.append($('<option>', { 
                        value: plan.rule_id + '-' + cardId + '-' + plan.fee_to_send, 
                        text: text 
                    }));
                });
            }
        },

        processTokenization: function() {
            var self = this;
            var $errorBox = $('#payway_error_container');
            $errorBox.hide().empty();

            console.log('PaywayHandler: Starting tokenization...');

            // Validar campos locales
            var requiredFields = ['cc_bank', 'cc_type', 'cc_installments', 'cc_number', 'cc_exp_month', 'cc_exp_year', 'cc_cid', 'cc_holder_name', 'cc_doc_number', 'cc_holder_street', 'cc_holder_door_number'];
            var missing = false;
            $.each(requiredFields, function(i, field) {
                if (!$('#' + self.fid + field).val()) {
                    missing = true;
                    return false;
                }
            });

            if (missing) {
                alert('Por favor complete todos los campos de la tarjeta y domicilio.');
                return;
            }

            var sdk = new Decidir(wc_gateway_payway_params.endpoint_url, !wc_gateway_payway_params.cybersource_enabled);
            sdk.setPublishableKey(wc_gateway_payway_params.creds.public_key);

            var formData = {
                card_number: $('#' + this.fid + 'cc_number').val().replace(/\s/g, ''),
                security_code: $('#' + this.fid + 'cc_cid').val(),
                card_expiration_month: $('#' + this.fid + 'cc_exp_month').val(),
                card_expiration_year: $('#' + this.fid + 'cc_exp_year').val(),
                card_holder_name: $('#' + this.fid + 'cc_holder_name').val(),
                card_holder_doc_type: $('#' + this.fid + 'cc_doc_type').val(),
                card_holder_doc_number: $('#' + this.fid + 'cc_doc_number').val(),
                card_holder_street: $('#' + this.fid + 'cc_holder_street').val(),
                card_holder_door_number: $('#' + this.fid + 'cc_holder_door_number').val()
            };

            console.log('PaywayHandler: Sending data to SDK', formData);

            sdk.createToken(formData, function(status, response) {
                console.log('PaywayHandler: SDK Response', status, response);
                if (status === 200 || status === 201) {
                    $('#' + self.fid + 'cc_token').val(response.id);
                    $('#' + self.fid + 'cc_bin').val(response.bin);
                    $('#' + self.fid + 'cc_last_digits').val(response.last_four_digits);
                    
                    if (typeof sdk.getDeviceId === 'function') {
                        $('#' + self.fid + 'device_fingerprint').val(sdk.getDeviceId());
                    }

                    self.isTokenized = true;
                    console.log('PaywayHandler: Token success, submitting form...');
                    $('form.checkout').submit();
                } else {
                    var errorMsg = "Error en la tarjeta";
                    if (response.error_type) errorMsg = response.error_type;
                    if (response.validation_errors) errorMsg += ": " + response.validation_errors[0].code;
                    
                    $errorBox.text(errorMsg).show();
                    $('html, body').animate({ scrollTop: $errorBox.offset().top - 100 }, 500);
                }
            });
        }
    };

    $(document).ready(function() { PaywayHandler.init(); });
})(jQuery);
</script>
