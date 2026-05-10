(function($) {
	'use strict';

	var CSPAdmin = {

		init: function() {
			this.initSortable();
			this.bindGeneralSettings();
			this.bindCheckoutFields();
			this.bindToggleDetails();
			this.bindEnabledToggle();
		},

		initSortable: function() {
			var $list = $('#csp-checkout-fields-list');
			if ($list.length && $.fn.sortable) {
				$list.sortable({
					handle: '.csp-drag-handle',
					placeholder: 'csp-field-item ui-sortable-placeholder',
					axis: 'y',
					tolerance: 'pointer',
					forcePlaceholderSize: true,
					cursor: 'move',
					update: function() {
						CSPAdmin.updatePriorities();
					}
				});
				$list.disableSelection();
			}
		},

		updatePriorities: function() {
			var step = 10;
			$('#csp-checkout-fields-list .csp-field-item').each(function(index) {
				$(this).find('.csp-field-priority').val((index + 1) * step);
			});
		},

		bindGeneralSettings: function() {
			$('#csp-save-general').on('click', function(e) {
				e.preventDefault();
				var $btn = $(this);
				var $spinner = $btn.siblings('.csp-spinner');
				var $status = $btn.siblings('.csp-save-status');

				$btn.prop('disabled', true);
				$spinner.addClass('is-active');
				$status.text('');

				$.post(cspAdmin.ajaxUrl, {
					action: 'csp_save_general_settings',
					nonce: cspAdmin.nonce,
					enabled: $('#csp-enabled').val()
				}, function(response) {
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');

					if (response.success) {
						$status.text(response.data.message).removeClass('error');
					} else {
						$status.text(response.data.message).addClass('error');
					}

					setTimeout(function() { $status.text(''); }, 3000);
				}).fail(function() {
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
					$status.text(cspAdmin.i18n.error).addClass('error');
				});
			});
		},

		bindCheckoutFields: function() {
			$('#csp-save-checkout-fields').on('click', function(e) {
				e.preventDefault();
				var fields = CSPAdmin.collectFields();
				var $btn = $(this);
				var $spinner = $btn.siblings('.csp-spinner');
				var $status = $btn.siblings('.csp-save-status');

				$btn.prop('disabled', true);
				$spinner.addClass('is-active');
				$status.text('');

				$.post(cspAdmin.ajaxUrl, {
					action: 'csp_save_checkout_fields',
					nonce: cspAdmin.nonce,
					fields: fields
				}, function(response) {
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');

					if (response.success) {
						$status.text(response.data.message).removeClass('error');
					} else {
						$status.text(response.data.message).addClass('error');
					}

					setTimeout(function() { $status.text(''); }, 3000);
				}).fail(function() {
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
					$status.text(cspAdmin.i18n.error).addClass('error');
				});
			});

			$('#csp-reset-checkout-fields').on('click', function(e) {
				e.preventDefault();
				if (!confirm(cspAdmin.i18n.confirm)) {
					return;
				}

				var $btn = $(this);
				var $spinner = $btn.siblings('.csp-spinner');
				var $status = $btn.siblings('.csp-save-status');

				$btn.prop('disabled', true);
				$spinner.addClass('is-active');

				$.post(cspAdmin.ajaxUrl, {
					action: 'csp_reset_checkout_fields',
					nonce: cspAdmin.nonce
				}, function(response) {
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');

					if (response.success) {
						$status.text(response.data.message).removeClass('error');
						location.reload();
					} else {
						$status.text(response.data.message).addClass('error');
					}
				}).fail(function() {
					$btn.prop('disabled', false);
					$spinner.removeClass('is-active');
					$status.text(cspAdmin.i18n.error).addClass('error');
				});
			});
		},

		collectFields: function() {
			var fields = {};
			$('#csp-checkout-fields-list .csp-field-item').each(function() {
				var $item = $(this);
				var key = $item.data('key');
				fields[key] = {
					label: $item.find('.csp-field-label').val(),
					placeholder: $item.find('.csp-field-placeholder').val(),
					priority: parseInt($item.find('.csp-field-priority').val(), 10) || 50,
					enabled: $item.find('.csp-field-enabled').is(':checked'),
					required: $item.find('.csp-field-required').is(':checked'),
					type: $item.find('.csp-field-type').val()
				};
			});
			return fields;
		},

		bindToggleDetails: function() {
			$(document).on('click', '.csp-field-toggle-details', function(e) {
				e.preventDefault();
				e.stopPropagation();
				var $btn = $(this);
				var $details = $btn.closest('.csp-field-item').find('.csp-field-details');
				$details.slideToggle(200);
				$btn.toggleClass('open');
			});
		},

		bindEnabledToggle: function() {
			$(document).on('change', '.csp-field-enabled', function() {
				var $item = $(this).closest('.csp-field-item');
				if ($(this).is(':checked')) {
					$item.removeClass('csp-disabled');
				} else {
					$item.addClass('csp-disabled');
				}
			});

			// Set initial disabled state for unchecked fields on page load
			$('.csp-field-enabled').not(':checked').each(function() {
				$(this).closest('.csp-field-item').addClass('csp-disabled');
			});
		}
	};

	$(function() {
		CSPAdmin.init();
	});
})(jQuery);
