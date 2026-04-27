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
	<div id="error_container" style="display:none; color: #a00; background: #fff1f1; padding: 10px; border: 1px solid #a00; margin-bottom: 15px;"></div>
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
			<label for="<?php echo $gateway_field_id; ?>cc_holder_door_number"><?php echo __('Altura de Dirección', 'wc-gateway-payway'); ?> <span class="required">*</span></label>
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
    var PaywayForm = {
        id: 'payway_gateway',
        $form: null,
        cart_total: 0,
        loadReady: false,

        init: function() {
            this.$form = $('#payway_gateway-cc-form');
            if (!this.$form.length) return;

            this.cart_total = parseFloat(this.$form.data('cart-total')) || 0;
            this.setupSelectors();
            this.attachListeners();
            this.loadPromotions();
            this.loadReady = true;
            console.log('Payway Form Initialized');
        },

        setupSelectors: function() {
            this.$banks = $('#payway_gateway_cc_bank', this.$form);
            this.$cards = $('#payway_gateway_cc_type', this.$form);
            this.$installments = $('#payway_gateway_cc_installments', this.$form);
            this.$error = $('#error_container');
            
            // Inputs para validación
            this.$expMonth = $('#payway_gateway_cc_exp_month', this.$form);
            this.$expYear = $('#payway_gateway_cc_exp_year', this.$form);
            this.$docNum = $('#payway_gateway_cc_doc_number', this.$form);
            this.$doorNum = $('#payway_gateway_cc_holder_door_number', this.$form);
        },

        attachListeners: function() {
            var self = this;
            this.$banks.on('change', this.onBankChange.bind(this));
            this.$cards.on('change', this.onCardChange.bind(this));
            
            // Re-mapear total si cambia el checkout (envíos, cupones)
            $(document.body).on('updated_checkout', function() {
                var newTotal = parseFloat($('#payway_gateway-cc-form').data('cart-total'));
                if (!isNaN(newTotal)) {
                    self.cart_total = newTotal;
                    if (self.$cards.val()) self.onCardChange();
                }
            });

            $('#place_order').on('click', this.capturePlaceOrder.bind(this));
        },

        loadPromotions: function() {
            var banks = wc_gateway_payway_params.promotions.banks;
            this.$banks.empty().append('<option value="">Por favor seleccione...</option>');
            if (!banks) return;

            $.each(banks, function(i, bank) {
                this.$banks.append($('<option>', { value: bank.value, text: bank.name }));
            }.bind(this));
        },

        onBankChange: function() {
            var bankId = this.$banks.val();
            this.$cards.empty().append('<option value="">Por favor seleccione...</option>');
            this.$installments.empty().append('<option value="">Por favor seleccione...</option>');

            if (!bankId || !wc_gateway_payway_params.promotions.cards[bankId]) return;

            $.each(wc_gateway_payway_params.promotions.cards[bankId], function(i, card) {
                this.$cards.append($('<option>', { value: card.value, text: card.name }));
            }.bind(this));
        },

        onCardChange: function() {
            var bankId = this.$banks.val();
            var cardId = this.$cards.val();
            this.$installments.empty().append('<option value="">Por favor seleccione...</option>');

            if (!bankId || !cardId || !wc_gateway_payway_params.promotions.plans[bankId][cardId]) return;

            var plans = wc_gateway_payway_params.promotions.plans[bankId][cardId];
            var self = this;

            $.each(plans, function(i, plan) {
                var coefficient = parseFloat(plan.coefficient) || 1;
                var feePeriod = parseInt(plan.fee_period);
                var totalWithInterest = self.cart_total * coefficient;
                var installmentAmount = totalWithInterest / feePeriod;

                var text = feePeriod + ' x ' + 
                           accounting.formatMoney(installmentAmount, wc_gateway_payway_accounting_format) + 
                           ' (' + accounting.formatMoney(totalWithInterest, wc_gateway_payway_accounting_format) + ')';

                self.$installments.append($('<option>', {
                    value: plan.rule_id + '-' + cardId + '-' + plan.fee_to_send,
                    text: text
                }));
            });
        },

        capturePlaceOrder: function(e) {
            if (!$('#payment_method_payway_gateway').is(':checked')) return true;
            
            // Validaciones básicas antes de tokenizar
            if (!this.$banks.val() || !this.$cards.val() || !this.$installments.val()) {
                alert('Por favor complete todos los campos de financiación.');
                return false;
            }

            e.preventDefault();
            this.$error.hide().empty();

            var self = this;
            var sdk = new Decidir(wc_gateway_payway_params.endpoint_url, !wc_gateway_payway_params.cybersource_enabled);
            sdk.setPublishableKey(wc_gateway_payway_params.creds.public_key);

            var formData = {
                card_number: $('#payway_gateway_cc_number').val().replace(/\s/g, ''),
                security_code: $('#payway_gateway_cc_cid').val(),
                card_expiration_month: $('#payway_gateway_cc_exp_month').val(),
                card_expiration_year: $('#payway_gateway_cc_exp_year').val(),
                card_holder_name: $('#payway_gateway_cc_holder_name').val(),
                card_holder_doc_type: $('#payway_gateway_cc_doc_type').val(),
                card_holder_doc_number: $('#payway_gateway_cc_doc_number').val(),
                card_holder_door_number: $('#payway_gateway_cc_holder_door_number').val()
            };

            sdk.createToken(formData, function(status, response) {
                if (status === 200 || status === 201) {
                    $('#payway_gateway_cc_token').val(response.id);
                    $('#payway_gateway_cc_bin').val(response.bin);
                    $('#payway_gateway_cc_last_digits').val(response.last_four_digits);
                    
                    if (typeof sdk.getDeviceId === 'function') {
                        $('#payway_gateway_device_fingerprint').val(sdk.getDeviceId());
                    }

                    // Re-enviar el formulario de WooCommerce
                    $('form.checkout').off('click', '#place_order');
                    $('#place_order').trigger('click');
                } else {
                    var msg = response.error_type || 'Error al validar la tarjeta';
                    if (response.validation_errors) msg += ': ' + response.validation_errors[0].code;
                    self.$error.text(msg).show();
                    $('html, body').animate({ scrollTop: self.$error.offset().top - 100 }, 500);
                }
            });

            return false;
        }
    };

    $(document).ready(function() { PaywayForm.init(); });
    $(document.body).on('updated_checkout', function() { PaywayForm.init(); });

})(jQuery);
</script>
