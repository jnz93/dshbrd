<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Dshbrd
 * @subpackage Uc_Dshbrd/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Uc_Dshbrd
 * @subpackage Uc_Dshbrd/admin
 * @author     UnityCode <contato@unitycode.tech>
 */
class Uc_Dshbrd_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('admin_menu', array($this, 'register_menu_admin_area'));


		# CUSTOM TAXONOMIES
		add_action('init', array($this, 'customer_custom_category_product'));

		# REGISTERING AJAX ACTIONS
		add_action('wp_ajax_product_by_name_or_ref', array($this, 'product_by_name_or_ref')); // executed when logged in
		add_action('wp_ajax_service_by_name_or_ref', array($this, 'service_by_name_or_ref')); // executed when logged in
		add_action('wp_ajax_add_product_to_cart', array($this, 'add_product_to_cart')); // Executed whe logged in

		# ADD SHORTCODES
		add_shortcode('apply_backup_services', array($this, 'apply_backup_services_from_list'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Uc_Dshbrd_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Uc_Dshbrd_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css', array(), $this->version, 'all');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/uc-dshbrd-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Uc_Dshbrd_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Uc_Dshbrd_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/uc-dshbrd-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'awesome-icons', 'https://kit.fontawesome.com/f18f521cf8.js', array(), $this->version, false);
		wp_enqueue_script( 'bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js', array(), $this->version, false);

	}

	/** 
	 * Register menu page for admin area
	 * 
	 * @since 1.0.0
	*/
	public function register_menu_admin_area()
	{
		add_menu_page('Pedidos', 'pedidos dshbrd', 'manage_options', 'pedidos_dshbrd', array($this,'uc_dshbrd_orders'), '', '10');
	}

	/**
	 * Template for all orders
	 * 
	 * @since 1.0.0
	 */
	public function uc_dshbrd_orders()
	{
		?>
		<div class="">
			
			<header class="d-none">
				<ul class="row">
					<li><a href="">Todos os pedidos</a></li>
					<li><a href="">Novo pedido</a></li>
				</ul>
			</header>
			<!-- /End header -->
			<div class="body container-fluid">

				<div id="all_orders" class="" style="display: none;">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">#ID</th>
								<th scope="col">Descrição do pedido</th>
								<th scope="col">Data</th>
								<th scope="col">Estatus</th>
								<th scope="col">Total</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th scope="row">#123456</th>
								<td>#123456 - Ana maria silva</td>
								<td>24/06/2020 - 20:21</td>
								<td><span class="">Pagamento confirmado</span></td>
								<td>R$20,00</td>
							</tr>
						</tbody>
					</table>
					<!-- /End Table orders list -->

					<div class="">
						<span>1</span>
						<span>2</span>
						<span>3</span>
					</div>
					<!-- /End Pagination -->
				</div>
				<!-- /End #all_orders -->
				
				<div id="new_order" class="row">

					<div class="col-5 mt-4 card">
						<nav>
							<ul class="nav nav-pills mb-3" id="nav-tab" role="tablist">
								<li class="nav-item" role="presentation">
									<a class="nav-link active" id="order-product" data-toggle="pill" href="#search-product" role="tab" aria-controls="search-product" aria-selected="true">Produtos</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link" id="order-service" data-toggle="pill" href="#search-service" role="tab" aria-controls="search-service" aria-selected="false">Serviços</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link" id="order-customer" data-toggle="pill" href="#search-costumer" role="tab" aria-controls="search-costumer" aria-selected="false">Cliente</a>
								</li>
							</ul>
						</nav>
						<!-- /End nav selection -->

						<div class="tab-content" id="nav-tabContent">
							<div id="search-product" class="tab-pane fade show active border-bottom" role="tabpanel" aria-labelledby="order-product-tab">

								<h4 class="mb-4">Adicionar Produto(s)</h4>
								<form id="add_product" action="">
									<div class="form-row">
										<div class="form-group row col-12">
											<label class="form-check-label col-3" for="name_ref">Nome do produto / Ref.</label>
											<input type="text" id="name_ref" name="name_ref" class="form-control col-9" onkeyup="looking_for_product(this.value)" placeholder="Digite o nome ou código de barras para procurar">
										</div>

										<div class="form-group row col-12 d-none">
											<label class="form-check-label col-3" for="item_qty">Quantidade</label>
											<input type="number" id="item_qty" name="item_qty" class="form-control col-9" placeholder="1 - 10" value="1">
										</div>

										<!-- <button type="button" id="" class="btn btn-primary btn-lg btn-block">Adicionar Item</button> -->
									</div>
								</form>
								<!-- /End #add_product -->

							</div>
							<!-- /End #new_order_insert_data -->

							<div id="search-service" class="tab-pane fade border-bottom" role="tabpanel" aria-labelledby="order-service-tab">

								<h4 class="mb-4">Adicionar Serviço(s)</h4>
								<form id="add_service" action="">
									<div class="form-row">
										<div class="form-group row col-12">
											<label for="name_ref" class="form-check-label col-3">Nome do serviço / Ref.</label>
											<input type="text" id="" name="name_ref" class="form-control col-9" onkeyup="looking_for_service(this.value)" placeholder="Digite o nome ou código para procurar">
										</div>

										<div class="form-group row col-12 d-none">
											<label for="item_qty" class="form-check-label col-3">Quantidade</label>
											<input type="number" id="" name="item_qty" class="form-control col-9" placeholder="1 - 10" value="1">
										</div>

										<div class="form-group row col-12 d-none">
											<label for="technician" class="form-check-label col-3">Técnico Responsável</label>
											<select name="technician" id="technician" class="form-control col-9">
												<option value="">Selecione uma opção</option>
												<option value="gabriel-cupini">Gabriel Cupini</option>
												<option value="everton-cupini">Everton Cupini</option>
												<option value="outro">Outro</option>
											</select>
										</div>
									</div>
								</form>
								<!-- /End #add_service -->

							</div>
							<!-- /End #new_order_insert_service -->

							<div id="search-costumer" class="tab-pane fade border-bottom" role="tabpanel" aria-labelledby="order-customer-tab">

								<h4 class="mb-4">Procurar Cliente</h4>
								<form id="add_customer_to_order" action="">
									<div class="form-row">
										<div class="form-group row col-12">
											<label for="customer_name" class="form-check-label col-3">Nome / Ref.</label>
											<input type="text" id="customer_name" name="customer_name" class="form-control col-9" placeholder="Digite o nome ou CPF">
										</div>
									</div>
								</form>
								<!-- /End #add_customer_to_order -->
							</div>
							<!-- /End #new_order_insert_customer -->
						</div>
						
						<div class="flex-row d-flex justify-content-center">
							<div id="lds-loader" class="lds-facebook position-absolute" style="display: none; z-index: 999;"><div></div><div></div><div></div></div>
						</div>
						<!-- /End loader -->

						<div id="wrapper_results" class="overflow-auto" style="height: 420px;">

						</div>
						<!-- /End #wrapper_results -->
					</div>
					<!-- /End add products, services and customer -->

					<div class="col-7">
						<div id="new_order_data" class="col-12 card mb-4">
							<h4 class="mb-4">Dados do pedido - #001</h4>

							<div id="order_products" class="">
								<table class="table">
									<thead>
										<tr>
											<th scope="col">Qtd.</th>
											<th scope="col">Nome</th>
											<th scope="col">Ref.</th>
											<th scope="col">Val. Unit.</th>
											<th scope="col">Val. Total</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
							<!-- /End #order_products  -->

							<div id="order_services" class="">
								<table class="table">
									<thead>
										<tr>
											<th scope="col">Qtd.</th>
											<th scope="col">Serviços(s)</th>
											<th scope="col">Unid.</th>
											<th scope="col">Val. Unit.</th>
											<th scope="col">Val. Total</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
							<!-- /End #order_services -->

							<div class="d-flex justify-content-end">
								<div class="col-4 d-flex-column">
									<div class="d-flex col-12 mb-2">
										<div class="d-flex col-3 bg-info align-items-center justify-content-center p-0">
											<i class="text-white fas fa-microchip" style="font-size: 1.4em;"></i>
										</div>
										<div class="col-9 d-flex-column">
											<span style="font-size: .8em;">Total em produtos</span>
											<h6 id="total_products">R$00,00</h6>
										</div>
									</div>
									<!-- /End total products -->
									
									<div class="d-flex col-12">
										<div class="d-flex col-3 bg-info align-items-center justify-content-center p-0">
											<i class="text-white fas fa-briefcase" style="font-size: 1.4em;"></i>
										</div>
										<div class="col-9 d-flex-column">
											<span style="font-size: .8em;">Total em serviços</span>
											<h6 id="total_services">R$00,00</h6>
										</div>
									</div>
									<!-- /End total services -->
								</div>

								<div class="d-flex col-4">
									<div class="d-flex col-3 bg-danger align-items-center justify-content-center p-0">
										<i class="text-white fas fa-shopping-cart" style="font-size: 1.4em;"></i>
									</div>
									<div class="col-9 d-flex-column justify-content-center">
										<span style="font-size: .8em">Total</span>
										<h4 id="total_cart">R$00,00</h4>
									</div>
								</div>
							</div>
							<!-- /End totals -->
						</div>
						<!-- /End #new_order_data  -->
						<button class="btn btn-primary btn-lg">Finalizar Compra</button>
						<button class="btn btn-danger">Cancelar</button>
					</div>

				</div>
				<!-- /End #new_order  -->

			</div>
			<!-- /End Body -->
		</div>
		<!-- /End Container All Orders -->

		<script type="text/javascript">
		
			function looking_for_product(value)
			{

				var admin_url_ajax = window.location.protocol + "://" + window.location.host + "/wp-admin/admin-ajax.php",
					wrapper_results = jQuery('#wrapper_results'),
					loader = jQuery('#lds-loader');
				jQuery.ajax({
					type: 'POST',
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					data: {
						action: 'product_by_name_or_ref',
						string: value
					},
					beforeSend: function()
					{
						loader = jQuery('#lds-loader')
						loader.show().fadeIn();
					},
					success: function(data){
						wrapper_results.html(data);
					},
					complete: function()
					{
						loader.hide().fadeOut();
					}

				});
			}

			function looking_for_service(value)
			{
				var wp_admin_ajax_url = window.location.protocol + "://" + window.location.host + "/wp-admin/admin-ajax.php",
					wrapper_results = jQuery('#wrapper_results'),
					loader = jQuery('#lds-loader');

				jQuery.ajax({
					type: 'POST',
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					data: {
						action: 'service_by_name_or_ref',
						term: value,
					},
					beforeSend: function()
					{
						loader.fadeIn();
					},
					success: function(data)
					{
						wrapper_results.html(data);
					},
					complete: function()
					{
						loader.fadeOut();
					}
				});
			}

			// Ajax for add to cart
			function add_to_cart(id, name, ref, qty, valUnt, itemType)
			{
				jQuery.ajax({
					type: 'POST',
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					data: {
						action: 'add_product_to_cart',
						item_id: id,
						item_qtd: qty,
					},
					success: function(data){
						console.log(data);
						add_item_to_order(id, name, ref, qty, valUnt, itemType);
					}
				});
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
			* @param {*} itemType
			*/
			function add_item_to_order(id, name, ref,  qty, valUnt, itemType)
			{
				var id = id,
					name = name,
					ref = ref,
					qty = qty,
					valUnt = valUnt,
					valTotal = calc_total_item(qty, valUnt),
					itemType = itemType;

				let itemToAdd = '<tr><td>'+ qty +'</td><td>'+ name +'</td><td>'+ ref +'</td><td>'+ valUnt +'</td><td class="total-item">'+ valTotal +'</td></tr>';

				if (itemType == 'servico')
				{
					var tableToAdd = jQuery('#order_services > table > tbody');
					tableToAdd.append(itemToAdd);
					sum_total_services_added();
				}
				else
				{
					var tableToAdd = jQuery('#order_products > table > tbody');
					tableToAdd.append(itemToAdd);
					sum_total_products_added();
				}

				console.log(id + ' - ' + name + ' - ' + ref  + ' - ' + qty + ' - ' + valUnt + '</br>');
				console.log(itemToAdd);
			}


			/**
			* Calc item total
			* 
			* Calcula o total do item adicionado(qtd x val unit)
			* @param {*} qty 
			* @param {*} valUnt 
			*/
			function calc_total_item(qty, valUnt)
			{
				var qty = parseFloat(qty),
					valUnt = parseFloat(valUnt);

				let total = (valUnt * qty);

				return total.toLocaleString('pt-br', {minimunFractionDigits: 2});
			}

			
			/**
			* Function increment item quantity
			* 
			* @param {*} val 
			* 
			* @since beta_1.0.0
			*/
			function increment_number(element)
			{
				var currVal = parseInt(element.val()),
					newVal = currVal + 1;

				element.val(newVal);
			}

			/**
			* Function increment item quantity
			* 
			* @param {*} val 
			* 
			* @since beta_1.0.0
			*/
			function decrement_number(element)
			{
				var currVal = parseInt(element.val()),
					newVal = currVal - 1;

				element.val(newVal);
			}

			/**
			 * Function sum_total_services_added()
			 * 
			 * Soma do total de serviços adicionados na nota
			 * 
			 * @since beta_1.0.0
			 */
			function sum_total_services_added()
			{
				var totalOnTable = jQuery('#order_services tbody tr .total-item');

				var sum = 0;
				totalOnTable.each(function(){

					sum = parseInt(jQuery(this).text()) + sum;
					
					// console.log(parseInt(varTotalServices);
				});
				jQuery('#total_services').text('R$' + sum);
				console.log(sum);
			}

			/**
			 * Function sum_total_products_added()
			 * 
			 * Soma do total de produtos adicionados na nota
			 * 
			 * @since beta_1.0.0
			 */
			function sum_total_products_added()
			{
				var valuesOnTable = jQuery('#order_products tbody tr .total-item');

				var sumTotal = 0.0;
				valuesOnTable.each(function(){

					sumTotal = parseFloat(jQuery(this).text()) + sumTotal;
				});
				jQuery('#total_products').text('R$' + sumTotal);
				console.log(sumTotal);
			}
		</script>
		<?php
	}

	/**
	 * Function for search product by name or REF. This function is called by json requisition;
	 * 
	 * @param $string = name or ref;
	 * @since 1.0.0
	 */
	public function product_by_name_or_ref($string)
	{
		// Search and return product by ID, Name or Ref;
		$s = $_POST['string'];

		if (empty($s)) :
			echo 'Termo de busca não fornecido.';
			exit();
		endif;
		
		$args = array(
			'post_type'		=> 'product',
			's'				=> $s,
			'tax_query'		=> array(
				'relation'	=> 'and',
				array(
					'taxonomy'		=> 'type-products',
					'field'			=> 'slug',
					'terms'			=> array('servico'),
					'operator'		=> 'NOT IN'
				),
			),
		);

		$args_meta = array(
			'post_type'		=> 'product',
			'meta_query'	=> array(
				array(
					'key'		=> '_sku',
					'value'		=> $s
				),
			),
		);

		$query = new WP_Query($args);
		// $query = new WP_Query($args_meta);

		if ($query->have_posts()) :
			while($query->have_posts()) :
				$query->the_post();
				$id 			= get_the_ID();
				$title 			= get_the_title($id);
				$thumb 			= get_the_post_thumbnail($id, 'small', array('style' => 'width: 100%; height: 100%;'));
				$price 			= get_post_meta($id, '_regular_price', true);
				$promo_price 	= get_post_meta($id, '_sale_price', true);
				$stock 			= get_post_meta($id, '_stock_status', true);
				$ref 			= get_post_meta($id, '_sku', true);
				$item_type_name = '';
				$item_type_slug = '';

				foreach ($terms_of_type as $type) :
					$item_type_name = $type->name;
					$item_type_slug = $type->slug;
				endforeach;
				?>
				<div class="col-12 card" product-id="<?php echo $id; ?>" product-name="<?php echo $title; ?>" product-ref="<?php echo $ref; ?>" product-price="<?php echo ($promo_price != '' ? $promo_price : $price); ?>" product-type="<?php echo (!empty($item_type_slug) ? $item_type_slug : 'produto' ) ?>">
					<div class="row">
						<div class="col-3">
							<?php echo $thumb; ?>
						</div>
						<!-- /End Thumb -->

						<div class="col-8">
							<div class="col-12 mb-4">
								<h4><?php echo $title; ?></h4>
							</div>
							<div class="row align-middle">
								<div class="col-4">
									<h2 class=""><span class="badge badge-secondary mr-2"><?php echo 'R$' . $price ?></span></h2>
								</div>
								<div class="col-8">
									<span class="badge badge-secondary mr-2"><?php echo $stock ?></span>
									<span class="badge badge-secondary mr-2"><?php echo '#' . $id ?></span>
									<span class="badge badge-secondary mr-2"><?php echo 'REF.' . $ref ?></span>
								</div>
							</div>
						</div>

						<div class="form-group d-flex flex-column align-items-center col-1 m-0 p-0 border-left">
							<button class="btn btn-light" onclick="increment_number(jQuery(this).siblings('input'));"><i class="fas fa-plus"></i></button>
							<input type="text" id="product-<?php echo $id; ?>" name="item_qty" class="form-control mt-2 mb-2 col-9" placeholder="1 - 10" value="1" style="max-height: 40px;">
							<button class="btn btn-light" onclick="decrement_number(jQuery(this).siblings('input'));"><i class="fas fa-minus"></i></button>
						</div>
						<!-- /End Informations -->
					</div>
				</div>
				<button type="button" id="" class="btn btn-primary btn-lg btn-block mt-1" onclick="add_to_cart(jQuery(this).prev().attr('product-id'), jQuery(this).prev().attr('product-name'), jQuery(this).prev().attr('product-ref'), jQuery('#product-<?php echo $id ?>').val(), jQuery(this).prev().attr('product-price'), jQuery(this).prev().attr('product-type'))">Adicionar ao Carrinho</button>
				<!-- /End template card product item -->
				<?php
			endwhile;
		
		else :
			echo '<p class="text-reset text-center">Não encontramos item(s) relacionados a busca: <b>'. $s .'</b></p>';			
		endif;
		
		// End json call
		die();
	}

	/**
	 * Function service_by_name_or_ref
	 * 
	 * Essa ação é a resposta para uma requisição ajax
	 * 
	 * @since 1.0.0
	 */
	public function service_by_name_or_ref()
	{
		$term = $_POST['term'];
		
		if (empty($term)) :
			echo 'Termo de busca não fornecido.';
			exit();
		endif;

		$args = array(
			'post_type'		=> 'product',
			's'				=> $term,
			'tax_query'		=> array(
				'relation'	=> 'and',
				array(
					'taxonomy'		=> 'type-products',
					'field'			=> 'slug',
					'terms'			=> array('servico'),
				),
			),
		);

		$query = new WP_Query($args);

		if ($query->have_posts()) :
			while ($query->have_posts()) :
				$query->the_post();
				$id 			= get_the_ID();
				$title 			= get_the_title($id);
				$thumb 			= get_the_post_thumbnail($id, 'small', array('style' => 'width: 100%; height: 100%;'));
				$price 			= get_post_meta($id, '_regular_price', true);
				$promo_price 	= get_post_meta($id, '_sale_price', true);
				$stock 			= get_post_meta($id, '_stock_status', true);
				$ref 			= get_post_meta($id, '_sku', true);
				$terms_of_type 	= get_the_terms($id, 'type-products');
				$item_type_name = '';
				$item_type_slug = '';

				foreach ($terms_of_type as $type) :
					$item_type_name = $type->name;
					$item_type_slug = $type->slug;
				endforeach;
				?>
				<div class="col-12 card" product-id="<?php echo $id; ?>" product-name="<?php echo $title; ?>" product-ref="<?php echo $ref; ?>" product-price="<?php echo ($promo_price != '' ? $promo_price : $price); ?>" product-type="<?php echo (!empty($item_type_slug) ? $item_type_slug : 'servico' ) ?>">
					<div class="row">
						<div class="col-3">
							<?php echo $thumb; ?>
						</div>
						<!-- /End Thumb -->

						<div class="col-8">
							<div class="col-12 mb-4">
								<h4><?php echo $title; ?></h4>
							</div>
							<div class="row align-middle">
								<div class="col-4">
									<h2 class=""><span class="badge badge-secondary mr-2"><?php echo 'R$' . $price ?></span></h2>
								</div>
								<div class="col-8">
									<span class="badge badge-secondary mr-2"><?php echo $stock ?></span>
									<span class="badge badge-secondary mr-2"><?php echo '#' . $id ?></span>
									<span class="badge badge-secondary mr-2"><?php echo 'REF.' . $ref ?></span>
								</div>
							</div>
						</div>
						<!-- /End Informations -->

						<div class="form-group d-flex flex-column align-items-center col-1 m-0 p-0 border-left">
							<button class="btn btn-light" onclick="increment_number(jQuery(this).siblings('input'));"><i class="fas fa-plus"></i></button>
							<input type="text" id="product-<?php echo $id ?>" name="item_qty" class="form-control mt-2 mb-2 col-9" placeholder="1 - 10" value="1" style="max-height: 40px;">
							<button class="btn btn-light" onclick="decrement_number(jQuery(this).siblings('input'));"><i class="fas fa-minus"></i></button>
						</div>
						<!-- /End quantity wrapper -->
					</div>
				</div>
				<button type="button" id="" class="btn btn-primary btn-lg btn-block" onclick="add_to_cart(jQuery(this).prev().attr('product-id'), jQuery(this).prev().attr('product-name'), jQuery(this).prev().attr('product-ref'), jQuery('#product-<?php echo $id ?>').val(), jQuery(this).prev().attr('product-price'), jQuery(this).prev().attr('product-type'))">Adicionar ao Carrinho</button>
				<?php
			endwhile;

		else :
			echo 'Não encontramos nenhum item correspondente com a pesquisa.';			
		endif;
		
		die();
	}


	/**
	 * Function for add product to cart with quantity by ajax call
	 * 
	 * @since 1.0.0
	 */
	public function add_product_to_cart()
	{
		$product_id = $_POST['item_id'];
		$product_qtd = (int) $_POST['item_qtd'];

		if (empty($product_id)) :
			echo 'Erro: ID do produto não informado';
			exit();
		endif;

		if (empty($product_qtd) || $product_qtd < 1 ) :
			echo 'Erro: Quantidade inválida ou menor que 1.';
			exit();
		endif;

		WC()->cart->add_to_cart($product_id, $product_qtd);
		echo 'item adicionado com successo';
		die();
	}


	/**
	 * Function customer custom category
	 * 
	 * Cada produto cadastrado deverá ser marcado com uma tag de produto ou serviço
	 * @since 1.0.0
	 */
	public function customer_custom_category_product()
	{
		register_taxonomy(
			'type-products',
			'product',
			array(
				'label' => __( 'Type of Product' ),
				'public' => true,
				'rewrite' => array('slug' => 'type-of-product'),
				'hierarchical' => false,
			)
		);
	}


	/**
	 * Function All services to products stock
	 * 
	 * Função para adicionar os serviços e respectivos preços como produtos da lista enviada pelo cliente
	 * 
	 * @since 1.0.0
	 */
	public function apply_backup_services_from_list()
	{
		// serviços
		$services = '002/001 - [MAN. IMPRESSORA HP 1020[ SR 48,95 -|- 
			002/002 - [MAN. IMPRESSORA BROTHER DCP L2540DW[ SR 67,31 -|- 
			002/003 - [MAN. IMPRESSORA BROTHER HL L2320D[ SR 67,31 -|- 
			002/004 - [MAN. IMPRESSORA EPSON FX 2190[ SR 67,31 -|- 
			002/005 - [MAN. IMPRESSORA EPSON L220[ SR 67,31 -|- 
			002/006 - [MAN. IMPRESSORA EPSON L3110[ SR 67,31 -|- 
			002/007 - [MAN. IMPRESSORA L380[ SR 67,31 -|- 
			002/008 - [MAN. IMPRESSORA EPSON L395[ SR 67,31 -|- 
			002/009 - [MAN. IMPRESSORA EPSON LX-300+II[ SR 67,30 -|- 
			002/010 - [MAN. IMPRESSORA HP 1022[ SR 67,30 -|- 
			002/011 - [MAN IMPRESSORA HP 1102[ SR 61,19 -|- 
			002/012 - [MAN. IMPRESSORA HP 4500[ SR 67,30 -|- 
			002/013 - [MAN. IMPRESSORA HP 4630[ SR 67,30 -|- 
			002/014 - [MAN. IMPRESSORA HP D1560[ SR 61,18 -|- 
			002/015 - [MAN. IMPRESSORA HP DESINGJET T120[ SR 152,97 -|- 
			002/016 - [MAN. IMPRESSORA HP DESKJET 2050[ SR 55,07 -|- 
			002/017 - [MAN. IMPRESSORA HP DESKJET 3050[ SR 55,07 -|- 
			002/018 - [MAN. IMPRESSORA HP LASERJET P1102W[ SR 73,42 -|- 
			002/019 - [MAN IMPRESSORA HP LASERJETPRO M102W[ SR 79,54 -|- 
			002/020 - [MAN. IMPRESSORA HP PRO M127FN[ SR 97,90 -|-
			002/021 - [MAN. IMPRESSORA LEXMARK MX310DN[ SR 122,37 -|-
			002/022 - [MAN. IMPRESSORA MATRICIAL LX 300[ SR 122,37 -|-
			002/023 - [MAN. IMPRESSORA PHASER 3200[ SR 91,78 -|-
			002/024 - [MAN. IMPRESSORA SAMSUMG MC 2851ND[ SR 91,78 -|-
			002/025 - [MAN. IMPRESSORA SAMSUNG SCX 4623F[ SR 110,14 -|-
			[Ativação do Windows 7/8.1/10[ Sr 35,00 -|-
			[Ativação Microsoft Office[ Sr 35,00 -|-
			[Atualização em Xbox 360[ Sr 50,00 -|-
			[CONFIGURAÇÃO CAMERA DE LAMPADA[ SR 35,00 -|-
			[Configuração da BIOS[ Sr 35,00 -|-
			[Configuração de Mikrotik e Switch[ Sr 250,00 -|-
			[Configuração de rede para cada Computador[ Sr 35,00 -|-
			[Conserto de impressora (limpeza, manu. e repa[ Sr 100,00 -|-
			[Conserto de No-breaks simples - Ñ inclu. peça[ Sr 65,00 -|-
			[Contrato de manutenção mensal (por PC)[ Sr 80,00 -|-
			[Deslocamento até o cliente dentro da cidade[ Km 1,00 -|-
			[Deslocamento até o cliente fora da cidade[ Km 1,50 -|-
			[Formatação/Instalação Windows 7|8.1|10 c/ BKP[ Sr 80,00 -|- 
			[Formatação/Instalação Windows 7|8.1|10 s/ BKP[ Sr 65,00 -|- 
			[Hora técnica simples[ Hs 30,00 -|- 
			[Instalação Avasta PRO - Licença de 01 ano.[ Sr 60,00 -|- 
			[Instalação de Hardware - Peças[ Sr 35,00 -|- 
			[Instalação de Impressora[ Sr 35,00 -|- 
			[Instalação de Softwares[ Sr 50,00 -|- 
			[Instalação do Pacote Microsoft Office[ Sr 35,00 -|- 
			[Instalação e Configuração de um Programa[ Sr 65,00 -|- 
			[Instalação/Configuração Windows Server[ Sr 300,00 -|- 
			[Limpeza completa impressora HP[ Sr 170,00 -|- 
			[Limpeza Impressora HP LaseJet Pro M102W[ Sr 50,00 -|- 
			[Limpeza Interna c/ troca de pasta termica[ Sr 100,00 -|- 
			[Limpeza Memoria Ram[ Sr 35,00 -|- 
			[Limpeza preventiva[ Sr 80,00 -|- 
			[Orçamento de conserto balcão[ Sr 0,00 -|- 
			[Otimização de Sistema Operacional[ Sr 50,00 -|- 
			[Recarga Cartucho HP 122XL - Preto[ Sr 25,00 -|- 
			[Recarga Cartucho HP 901 Colorido[ Sr 25,00 -|- 
			[Recuperação de Dados (por hora)[ Hs 10,00 -|- 
			[Recuperação de Dados de HD (para cada GB)[ Hs 1,50 -|- 
			[Recuperação de dados simples (para cada GB)[ Sr 1,50 -|- 
			[Recuperação Sistema Operacional[ Sr 50,00 -|- 
			[Remoção de vírus sem formatação[ Sr 60,00 -|- 
			[Remoção do vírus, spyware, malware e adware[ Sr 60,00 -|- 
			[Reparo Basico Impressora[ Sr 50,00 -|- 
			[Reparo completo HP LaserJet P1102W[ Sr 100,00 -|- 
			[Reparo completo HP OfficeJet 4500[ Sr 50,00 -|- 
			[Reparo Conector[ Sr 50,00 -|- 
			[Reparo do sistema operacional[ Sr 50,00 -|- 
			[Reparo e Limpeza Impressora Epson L355[ Sr 180,00 -|- 
			[Reparo e Troca de Tela LCD e Led Originais[ Sr 120,00 -|- 
			[Reparo e/ou troca de Cooler[ Sr 35,00 -|- 
			[Reparo em Playstation 2[ Sr 35,00 -|- 
			[Restauração de Software Android, IOS.[ Sr 70,00 -|- 
			[Serviços de Solda em Placa[ Sr 35,00 -|- 
			[Suporte Técnico Remoto[ Hs 65,00 -|- 
			[Troca bateria BIOS - Notebook[ Sr 50,00 -|- 
			[Troca da Bateria[ Sr 70,00 -|- 
			[Troca da pasta térmica[ Sr 35,00 -|- 
			[Troca de Fonte[ Sr 35,00 -|- 
			[Troca de HD, Memória, Bateria e Placa Mãe[ Sr 60,00 -|- 
			[Troca de Teclados Originais[ Sr 100,00 -|- 
			[Troca de Tela Original[ Sr 35,00 -|- 
			[Troca de Tela Original Smartphone LG K10[ Sr 200,00 -|- 
			[Troca do Conector de carga[ Sr 60,00 -|- 
			[Troca Drivers Ópticos - (DVD, CD, BluRay)[ Sr 50,00 -|- 
			[Upgrade, Memória, HDD, Processador[ Sr 100,00 -|- 
			[Visita técnica complexa[ Hs 120,00';
		
		$list_of_services = explode('-|-', $services);

		$count = 1;
		$ref_pattern = 'SR';
		foreach ($list_of_services as $service)
		{
			// Sanitize service data
			$break_service 			= explode('[', $service);
			$service_name 			= $break_service[1];

			$break_service_price 	= explode(' ', $break_service[2]);
			$service_und 			= $break_service_price[1];
			$service_price 			= $break_service_price[2];
			$service_ref 			= $ref_pattern . '-' . $count;

			// Create post
			$post = array(
				'post_author' => get_current_user_id(),
				'post_content' => '',
				'post_status' => "publish",
				'post_title' => $service_name,
				'post_parent' => '',
				'post_type' => "product",
			);
			$post_id = wp_insert_post( $post, $wp_error );

			// Update meta
			if ($post_id) :
				update_post_meta( $post_id, '_visibility', 'visible' );
				update_post_meta( $post_id, '_stock_status', 'instock');
				update_post_meta( $post_id, 'total_sales', '0');
				update_post_meta( $post_id, '_downloadable', 'no');
				update_post_meta( $post_id, '_virtual', 'no');
				update_post_meta( $post_id, '_regular_price', $service_price );
				update_post_meta( $post_id, '_sale_price', '' );
				update_post_meta( $post_id, '_purchase_note', "" );
				update_post_meta( $post_id, '_featured', "no" );
				update_post_meta( $post_id, '_weight', "" );
				update_post_meta( $post_id, '_length', "" );
				update_post_meta( $post_id, '_width', "" );
				update_post_meta( $post_id, '_height', "" );
				update_post_meta( $post_id, '_sku', $service_ref);
				update_post_meta( $post_id, '_product_attributes', array());
				update_post_meta( $post_id, '_sale_price_dates_from', "" );
				update_post_meta( $post_id, '_sale_price_dates_to', "" );
				update_post_meta( $post_id, '_price', $service_price );
				update_post_meta( $post_id, '_sold_individually', "" );
				update_post_meta( $post_id, '_manage_stock', "no" );
				update_post_meta( $post_id, '_backorders', "no" );
				update_post_meta( $post_id, '_stock', "" );
				update_post_meta( $post_id, '_service_und', $service_und);

				// Set tax
				wp_set_object_terms( $post_id, 'Serviço', 'type-products' );

				// Log de sucesso
				echo 'REF: ' . $service_ref . ' - NOME: ' . $service_name . ' - UND: ' . $service_und . ' - VALOR: ' . $service_price . '</br>';
			
			else :
				
				echo 'Houve um erro e o serviço "'. $service_name .'" não foi cadastrado.';

			endif;

			$count++;
		}
	}
}