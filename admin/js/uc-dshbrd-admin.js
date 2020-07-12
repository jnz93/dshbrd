(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	function looking_for_product(value){
		// '<?php echo admin_url('admin-ajax.php'); ?>';
		var admin_url_ajax = window.location.protocol + "://" + window.location.host + "/wp-admin/admin-ajax.php";
		var wrapper_results = $('#wrapper_results');
		jQuery.ajax({
			type: 'POST',
			url: admin_url_ajax,
			data: {
				action: 'product_by_name_or_ref',
				string: value
			},
			beforeSend: function()
			{
				var loader = '<div class="lds-facebook"><div></div><div></div><div></div></div>';
				wrapper_results.before(loader);
			},
			success: function(data){
				wrapper_results.prepend(data);
			},
			complete: function()
			{
				jQuery('div.lds-ring').fadeOut();
			}

		})
	}


	/**
	 * Add item to work order
	 * 
	 * Após o retorno de sucesso da função ajax que adiciona o item ao carrinho essa função adiciona o item a ordem de serviço.
	 * @param {*} id 
	 * @param {*} name 
	 * @param {*} ref 
	 * @param {*} qty 
	 * @param {*} valUnt 
	 */
	function add_item_to_order(id, name, ref,  qty, valUnt)
	{
		var id = id,
			name = name,
			ref = ref,
			qty = qty,
			valUnt = valUnt;

		let itemToAdd = '<tr><td>'+ id +'</td><td>'+ name +'</td><td>'+ ref +'</td><td>R$'+ valUnt +'</td><td>R$65,00</td></tr>';
		let tableToAdd = jQuery('#order_products > table > tbody').append(itemToAdd);

		console.log(id + ' - ' + name + ' - ' + ref  + ' - ' + qty + ' - ' + valUnt + '</br>');
		console.log(itemToAdd);
	}

})( jQuery );
