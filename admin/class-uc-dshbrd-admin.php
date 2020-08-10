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
		add_action('wp_ajax_search_customer', array($this, 'search_customer')); // executed when logged in
		add_action('wp_ajax_add_product_and_update_order', array($this, 'add_product_and_update_order')); // Executed whe logged in
		add_action('wp_ajax_add_customer_and_update_order', array($this, 'add_customer_and_update_order')); // Executed whe logged in
		add_action('wp_ajax_calculate_subtotal_curr_order', array($this, 'calculate_subtotal_curr_order')); // Executed whe logged in
		add_action('wp_ajax_submit_register_customer', array($this, 'submit_register_customer')); // Executed whe logged in
		add_action('wp_ajax_custom_update_order_status', array($this, 'custom_update_order_status')); // Executed when logged in

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
		wp_enqueue_script( 'mask-money', plugin_dir_url( __FILE__ ) . 'js/maskMoney.min.js', array('jquery'), 'v3.1.1', false);
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
		$new_order = $this->create_new_wc_order();
		$order_id = $new_order->id;
		date_default_timezone_set('America/Sao_Paulo');
		require( plugin_dir_path(__FILE__) . 'partials/uc-dshbrd-admin-display.php');		
	}

	/**
	 * Function/Action for search product by name or REF. This function is called by json requisition;
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
				$thumb 			= get_the_post_thumbnail($id, 'small', array('style' => 'width: 100%; height: 100%; border-radius: 8px;'));
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
						<div class="col-3 border-right">
							<?php echo $thumb; ?>
						</div>
						<!-- /End Thumb -->

						<div class="col-8 d-flex flex-column justify-content-center">
							<div class="mb-2 pb-2 border-bottom">
								<h5><?php echo $title; ?></h5>
							</div>
							<div class="row justify-content-center">
								<div class="col-4 d-flex align-items-center">
									<h3 class=""><span class="badge badge-secondary mr-2"><?php echo 'R$' . $price ?></span></h3>
								</div>
								<div class="col-8 p-0 d-flex align-items-center">
									<span class="badge badge-secondary mr-2"><?php echo $stock ?></span>
									<span class="badge badge-secondary mr-2"><?php echo '#' . $id ?></span>
									<span class="badge badge-secondary mr-2"><?php echo 'REF.' . $ref ?></span>
								</div>
							</div>
						</div>

						<div class="form-group d-flex flex-column align-items-center col-1 m-0 p-0 border-left">
							<button class="btn btn-light" onclick="increment_number(jQuery(this).siblings('input'));"><i class="fas fa-plus"></i></button>
							<input type="text" id="product-<?php echo $id; ?>" name="item_qty" class="form-control text-center mt-2 mb-2 col-9" placeholder="1 - 10" value="1" style="max-height: 40px;">
							<button class="btn btn-light" onclick="decrement_number(jQuery(this).siblings('input'));"><i class="fas fa-minus"></i></button>
						</div>
						<!-- /End Informations -->
					</div>
				</div>
				<button type="button" id="" class="btn btn-primary btn-lg mt-1 mb-4 mr-1 float-right" onclick="add_product_on_order(jQuery('#the_order_id').attr('data-order-id'), jQuery(this).prev().attr('product-id'), jQuery(this).prev().attr('product-name'), jQuery(this).prev().attr('product-ref'), jQuery('#product-<?php echo $id ?>').val(), jQuery(this).prev().attr('product-price'), jQuery(this).prev().attr('product-type'))"><i class="fas fa-cart-plus"></i> Adicionar ao Pedido</button>
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
	 * Function/Action service_by_name_or_ref
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
				$thumb 			= get_the_post_thumbnail($id, 'small', array('style' => 'width: 100%; height: 100%; border-right: 8px;'));
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
						<div class="col-3 border-right">
							<?php echo $thumb; ?>
						</div>
						<!-- /End Thumb -->

						<div class="col-8 d-flex flex-column justify-content-center">
							<div class="mb-2 pb-2 border-bottom">
								<h5><?php echo $title; ?></h5>
							</div>
							<div class="row justify-content-center">
								<div class="col-4 d-flex align-items-center">
									<h3 class=""><span class="badge badge-secondary mr-2"><?php echo 'R$' . $price ?></span></h3>
								</div>
								<div class="col-8 p-0 d-flex align-items-center">
									<span class="badge badge-secondary mr-2"><?php echo $stock ?></span>
									<span class="badge badge-secondary mr-2"><?php echo '#' . $id ?></span>
									<span class="badge badge-secondary mr-2"><?php echo 'REF.' . $ref ?></span>
								</div>
							</div>
						</div>
						<!-- /End Informations -->

						<div class="form-group d-flex flex-column align-items-center col-1 m-0 p-0 border-left">
							<button class="btn btn-light" onclick="increment_number(jQuery(this).siblings('input'));"><i class="fas fa-plus"></i></button>
							<input type="text" id="product-<?php echo $id ?>" name="item_qty" class="form-control text-center mt-2 mb-2 col-9" placeholder="1 - 10" value="1" style="max-height: 40px;">
							<button class="btn btn-light" onclick="decrement_number(jQuery(this).siblings('input'));"><i class="fas fa-minus"></i></button>
						</div>
						<!-- /End quantity wrapper -->
					</div>
				</div>
				<button type="button" id="" class="btn btn-primary btn-lg mt-1 mb-4 mr-1 float-right" onclick="add_product_on_order(jQuery('#the_order_id').attr('data-order-id'), jQuery(this).prev().attr('product-id'), jQuery(this).prev().attr('product-name'), jQuery(this).prev().attr('product-ref'), jQuery('#product-<?php echo $id ?>').val(), jQuery(this).prev().attr('product-price'), jQuery(this).prev().attr('product-type'))"><i class="fas fa-cart-plus"></i> Adicionar ao Pedido</button>
				<?php
			endwhile;

		else :
			echo 'Não encontramos nenhum item correspondente com a pesquisa.';			
		endif;
		
		die();
	}


	/**
	 * Function/Action search_customer
	 */
	public function search_customer()
	{
		$customer = $_POST['term'];

		$users = get_users(array('search' => $customer));
		
		if (!empty($users)) :
			foreach ($users as $user) :
				$customer_name 		= $user->first_name . ' ' . $user->last_name;
				$customer_id 		= $user->ID;
				$customer_email		= $user->user_email;
				$customer_type 		= $user->user_type;
				$customer_avatar_url = get_avatar_url($customer_id, ['size' => '40']);
				?>
				<div class="card" customer-id="<?php echo $customer_id; ?>" customer-name="<?php echo $customer_name; ?>" customer-email="<?php echo $customer_email; ?>" customer-type="<?php echo $customer_type;?>">
					<div class="row">
						<div class="col-3">
							<img src="<?php echo $customer_avatar_url; ?>" alt="">
						</div>
						<!-- /End Thumb -->

						<div class="col-9">
							<div class="mb-2">
								<h4><?php echo $customer_name; ?></h4>
							</div>
							<div class="align-middle">
								<span class="badge badge-secondary mr-2"><?php echo $customer_email ?></span>
								<span class="badge badge-secondary mr-2"><?php echo $customer_type ?></span>
							</div>
						</div>
						<!-- /End Informations -->

					</div>
				</div>
				<button type="button" id="" class="btn btn-primary btn-lg btn-block" onclick="add_customer_on_order(jQuery('#the_order_id').attr('data-order-id'), jQuery(this).prev().attr('customer-id'), jQuery(this).prev().attr('customer-name'), jQuery(this).prev().attr('customer-email'), jQuery(this).prev().attr('customer-type'))">Adicionar ao Pedido</button>
				
				<?php		
			endforeach;
		endif;

		die();
	}

	/**
	 * Function for add product to cart with quantity by ajax call
	 * 
	 * @since 1.0.0
	 */
	public function add_product_and_update_order()
	{
		$order_id = (int) $_POST['order_id'];
		$product_id = (int) $_POST['item_id'];
		$product_qty = (int) $_POST['item_qtd'];

		if (empty($order_id)) :
			echo 'Erro: ID da ordem não informado';
			exit();
		endif;

		if (empty($product_id)) :
			echo 'Erro: ID do produto não informado';
			exit();
		endif;

		if (empty($product_qty) || $product_qty < 1 ) :
			echo 'Erro: Quantidade inválida ou menor que 1.';
			exit();
		endif;

		// Retrieve order object data
		$order = wc_get_order($order_id);

		// Add the products to te order
		$product = wc_get_product( $product_id );
		$order->add_product($product, $product_qty);

		die();
	}

	/**
	 * Function for add customer to curr order by ajax call
	 * 
	 * @since beta_1.0.0
	 */
	public function add_customer_and_update_order()
	{
		$order_id 		= $_POST['order'];
		$customer_id 	= $_POST['customer'];

		if (empty($order_id)) :
			echo 'Erro: Pedido inválido.';
			exit();
		endif;

		if (empty($customer_id)) :
			echo 'Erro: Id do usuário inválido.';
			exit();
		endif;
		
		update_post_meta($order_id, '_customer_user', $customer_id);

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

	/**
	 * Function create order and return the object data
	 * 
	 * Função vai criar a ordem assim que um novo pedido começa.
	 * 
	 * @since beta_1.0.0
	 */
	public function create_new_wc_order()
	{
		$order = wc_create_order();

		return $order;
	}


	/**
	 * Function calculate_subtotal_curr_order
	 * 
	 * Funcao vai receber o ID da ordem e retornar o total do pedido atual
	 * 
	 * @param $order = object
	 * @since beta_1.0.0
	 */
	public function calculate_subtotal_curr_order()
	{
		$order_id = (int) $_POST['order_id'];

		if (empty($order_id))
		{
			echo 'Id do pedido é inválido.';
			exit();
		}

		$order = wc_get_order($order_id);

		// Retrieve order object data
		$total_order 	= $order->calculate_totals();

		echo $total_order;

		die();
	}


	/**
	 * Function submit_register_customer
	 * 
	 * Função recebe os dados via ajax e efetua a inserção de um novo cliente no banco
	 * 
	 * @since beta_1.1.0
	 */
	public function submit_register_customer()
	{
		$data 	= $_POST['data'];
		$data 	= explode('///', $data);

		$c_fullname 	= trim($data[0]);
		$c_fullname 	= explode(' ', $c_fullname);

		$c_first_name 	= array_shift($c_fullname);
		$c_last_name 	= implode(' ', $c_fullname);
		$c_email 		= trim($data[1]);
		$c_number 		= trim($data[2]);
		$c_doc 			= trim($data[3]);
		$c_date 		= trim($data[4]);
		$c_address 		= trim($data[5]);
		$c_city 		= trim($data[6]);

		$userdata = array(
			'ID'                    => 0,
			'user_login'            => $c_first_name,
			'user_nicename'         => $c_first_name,
			'user_email'            => $c_email,
			'display_name'          => $c_first_name,
			'nickname'              => $c_first_name,
			'first_name'            => $c_first_name,
			'last_name'             => $c_last_name,
			'role'                  => 'customer',
		);

		$user_id = wp_insert_user($userdata);
		
		if(!is_wp_error($user_id)) :
			update_user_meta($user_id, 'customer_phone_number', $c_number);
			echo $c_first_name . ' cadastrado(a) com sucesso!';
		else :
			echo wp_remote_retrieve_response_message($user_id);
		endif;

		die();
	}


	/**
	 * Function custom_update_order_status
	 * 
	 * Recebe o método de pagamento via ajax e faz o update do status do pedido e adiciona uma nota com método selecionado
	 * 
	 * @since beta_1.1.0
	 */
	public function custom_update_order_status()
	{
		$payment_selected = $_POST['method'];
		$order_id = $_POST['order_id'];
	
		$order = new WC_Order($order_id);
		$order->update_status('wc-completed', 'order_note');
		$order->customer_note = $payment_selected;
		update_post_meta($order_id, '_payment_method', $payment_selected);
		update_post_meta($order_id, '_customer_note', 'Método de pagamento: ' . $payment_selected);
		// update_post_meta($order_id, 'order_note', $payment_selected);
		
		// echo '<pre>';
		// print_r($order);
		// echo '</pre>';d
		echo 'Pedido concluído com sucesso!';
		die();
	}
}
