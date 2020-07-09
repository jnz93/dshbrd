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

		# REGISTERING AJAX ACTIONS
		add_action('wp_ajax_product_by_name_or_ref', array($this, 'product_by_name_or_ref')); // executed when logged in
		add_action('wp_ajax_add_product_to_cart', array($this, 'add_product_to_cart')); // Executed whe logged in
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
			
			<header class="">
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

					<div class="col-5">
						<div id="select_action" class="">
							<span class="">
								<input type="radio" id="order_product" name="order_product" value="" checked>
								<label for="order_product">Produto</label>
							</span>
							<span class="">
								<input type="radio" id="order_service" name="order_service" value="">
								<label for="order_service">Serviço</label>
							</span>
							<span class="">
								<input type="radio" id="order_client" name="order_client" value="">
								<label for="order_client">Dados do cliente</label>
							</span>
						</div>
						<!-- /End #select_action -->

						<div id="new_order_insert_data" class="col-12 card">

							<h4 class="mb-4">Adicionar Produto(s)</h4>
							<form id="add_product" action="">
								<div class="form-row">
									<div class="form-group row col-12">
										<label class="form-check-label col-3" for="name_ref">Nome do produto / Ref.</label>
										<input type="text" id="name_ref" name="name_ref" class="form-control col-9" onkeyup="looking_for_product(this.value)" placeholder="Digite o nome ou código de barras para procurar">
									</div>

									<div class="form-group row col-12">
										<label class="form-check-label col-3" for="item_qty">Quantidade</label>
										<input type="number" id="item_qty" name="item_qty" class="form-control col-9" placeholder="1 - 10" value="1">
									</div>

									<!-- <button type="button" id="" class="btn btn-primary btn-lg btn-block">Adicionar Item</button> -->
								</div>
							</form>
							<!-- /End #add_product -->

						</div>
						<!-- /End #new_order_insert_data -->

						<div id="new_order_insert_service" class="d-none col-12 card">

							<h3>Adicionar Serviço(s)</h3>
							<form id="add_service" action="">

								<div class="">
									<label for="name_ref">Nome do serviço / Ref.</label>
									<input type="text" id="" name="name_ref" class="" placeholder="Digite o nome ou código para procurar">
								</div>

								<div class="">
									<label for="item_qty">Quantidade</label>
									<input type="number" id="" name="item_qty" class="" placeholder="1 - 10" value="1">
								</div>

								<div class="">
									<label for="technician">Técnico Responsável</label>
									<select name="technician" id="technician">
										<option value="">Selecione uma opção</option>
										<option value="gabriel-cupini">Gabriel Cupini</option>
										<option value="everton-cupini">Everton Cupini</option>
										<option value="outro">Outro</option>
									</select>
								</div>

								<button id="" class="">Adicionar Item</button>
							</form>
							<!-- /End #add_service -->

						</div>
						<!-- /End #new_order_insert_service -->

						<div id="new_order_insert_customer" class="d-none col-12 card">

							<h3>Procurar Cliente</h3>
							<form id="add_customer_to_order" action="">

								<div class="">
									<label for="customer_name">Nome / Ref.</label>
									<input type="text" id="customer_name" name="customer_name" class="" placeholder="Digite o nome ou CPF">
								</div>
							</form>
							<!-- /End #add_customer_to_order -->
							<button id="" class="">Adicionar ao Pedido</button>
						</div>
						<!-- /End #new_order_insert_customer -->

						<div id="wrapper_results" class="">

						</div>
						<!-- /End #wrapper_results -->
					</div>
					<!-- /End add products, services and customer -->

					<div class="col-7">
						<div id="new_order_data" class="col-12 card">
							<h4 class="mb-4">Dados do pedido - #001</h4>

							<div id="order_products" class="">
								<table class="table">
									<thead>
										<tr>
											<th scope="col">Produtos(s)</th>
											<th scope="col">Ref.</th>
											<th scope="col">Qtd.</th>
											<th scope="col">Val. Unit.</th>
											<th scope="col">Val. Total</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th scope="row">Cabo áudio e vídeo 3RCA Macho x 3RCA...</th>
											<td>7899600916131</td>
											<td>1</td>
											<td>R$20,00</td>
											<td>R$20,00</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- /End #order_products  -->

							<div id="order_services" class="">
								<table class="table">
									<thead>
										<tr>
											<th scope="col">Serviços(s)</th>
											<th scope="col">Qtd.</th>
											<th scope="col">Unid.</th>
											<th scope="col">Val. Unit.</th>
											<th scope="col">Val. Total</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th scope="row">Formatação/Instalação windows 7|8|10 S/ BKP</th>
											<td>1</td>
											<td>SR</td>
											<td>R$65,00</td>
											<td>R$65,00</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- /End #order_services -->

							<div class="wrapper__totals">
								<div class="">
									<img src="" alt="">
									<span>Total em produtos</span>
									<h3>R$00,00</h3>
								</div>
								
								<div class="">
									<img src="" alt="">
									<span>Total em serviços</span>
									<h3>R$00,00</h3>
								</div>

								<div class="">
									<img src="" alt="">
									<span>Total</span>
									<h3>R$00,00</h3>
								</div>
							</div>
							<!-- /End totals -->
						</div>
						<!-- /End #new_order_data  -->
						<button>Finalizar Compra</button>
						<button>Cancelar</button>
					</div>

				</div>
				<!-- /End #new_order  -->

			</div>
			<!-- /End Body -->
		</div>

		<script type="text/javascript">
		
		function looking_for_product(value){

			var admin_url_ajax = window.location.protocol + "://" + window.location.host + "/wp-admin/admin-ajax.php";
			var wrapper_results = jQuery('#wrapper_results');
			jQuery.ajax({
				type: 'POST',
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
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
					wrapper_results.html(data);
				},
				complete: function()
				{
					jQuery('div.lds-facebook').fadeOut();
				}

			})
		}
		</script>
		<!-- /End Container All Orders -->
		<?php
	}

	/**
	 * Action for search product by name or REF. This function is called by json requisition;
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

		// $query = new WP_Query($args);
		$query = new WP_Query($args);

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
				?>
				<div class="col-12 card" product-id="<?php echo $id; ?>">
					<div class="row">
						<div class="col-3">
							<?php echo $thumb; ?>
						</div>
						<!-- /End Thumb -->

						<div class="col-9">
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
					</div>
					<button type="button" id="" class="btn btn-primary btn-lg btn-block" onclick="add_to_cart(jQuery(this).parent().attr('product-id'), jQuery('#item_qty').val())">Adicionar ao Carrinho</button>

					<!-- Ajax for add to cart -->
					<script type="text/javascript">
						function add_to_cart(id, qtd)
						{
							jQuery.ajax({
								type: 'POST',
								url: '<?php echo admin_url('admin-ajax.php'); ?>',
								data: {
									action: 'add_product_to_cart',
									item_id: id,
									item_qtd: qtd,
								},
								success: function(data){
									console.log(data);
								}
							});
						}
					</script>
				</div>
				<!-- /End template card product item -->
				<?php
			endwhile;
		
		else :
			echo 'Não encontramos nenhum item correspondente com a pesquisa.';			
		endif;
		
		// End json call
		die();
	}

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
}
