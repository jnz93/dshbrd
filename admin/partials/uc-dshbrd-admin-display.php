<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Dshbrd
 * @subpackage Uc_Dshbrd/admin/partials
 */
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

            <div class="col-5 mt-4">
                <nav>
                    <ul class="nav nav-pills mb-3" id="nav-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="order-product" data-toggle="pill" href="#search-product" role="tab" aria-controls="search-product" aria-selected="true">Produtos</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="order-service" data-toggle="pill" href="#search-service" role="tab" aria-controls="search-service" aria-selected="false">Serviços</a>
                        </li>
                    </ul>
                </nav>
                <!-- /End nav selection -->

                <div class="tab-content mb-2" id="nav-tabContent">
                    <div id="search-product" class="tab-pane col-12 card fade show active" role="tabpanel" aria-labelledby="order-product-tab">

                        <h4 class="mb-4">Procurar Produto(s)</h4>
                        <form id="add_product" action="">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-check-label mb-2" for="name_ref">Nome do produto / Ref.</label>
                                    <input type="text" id="name_ref" name="name_ref" class="form-control col-12" onkeyup="looking_for_product(this.value)" placeholder="Digite o nome ou código de barras para procurar">
                                </div>
                            </div>
                        </form>
                        <!-- /End #add_product -->

                    </div>
                    <!-- /End #new_order_insert_data -->

                    <div id="search-service" class="tab-pane col-12 card fade" role="tabpanel" aria-labelledby="order-service-tab">

                        <h4 class="mb-4">Procurar Serviço(s)</h4>
                        <form id="add_service" action="">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label for="name_ref" class="form-check-label mb-2">Nome do serviço / Ref.</label>
                                    <input type="text" id="" name="name_ref" class="form-control" onkeyup="looking_for_service(this.value)" placeholder="Digite o nome ou código para procurar">
                                </div>
                            </div>
                        </form>
                        <!-- /End #add_service -->

                    </div>
                    <!-- /End #new_order_insert_service -->
                </div>
                
                <div class="flex-row d-flex justify-content-center">
                    <div id="lds-loader" class="lds-facebook position-absolute" style="display: none; z-index: 999;"><div></div><div></div><div></div></div>
                </div>
                <!-- /End loader -->

                <div id="wrapper_results" class="mt-2 overflow-auto" style="height: 420px;">

                </div>
                <!-- /End #wrapper_results -->
            </div>
            <!-- /End add products, services and customer -->

            <div class="col-1"></div>
            <div class="col-6">

                <div id="new_order_data" class="col-12 card mb-4 pt-4">

                    <div id="order_header" class="header col-12 d-flex p-0 mb-4 pb-2 border-bottom">
                        <div class="col-6 pl-0 ">
                            <h4 id="the_order_id" data-order-id="<?php echo $order_id; ?>" class="">Pedido - <?php echo '#' . $order_id; ?></h4>
                            <div class="d-flex mt-1">
                                <p class="mb-0 mr-2"><i class="fas fa-calendar-day"></i> <?php echo date("d/m/Y"); ?></p>
                                <p class="mb-0"><i class="fas fa-clock"></i> <?php echo date('H:i'); ?></p>
                            </div>
                        </div>
                        <!-- /End titulo e infos -->

                        <div id="user_info" class="col-6 d-flex pr-0">
                            <div class="col-2 pl-0">
                                <div class="border d-flex justify-content-center align-items-center" style="width: 56px; height: 56px; border-radius: 100%">
                                    <i class="fas fa-user-alt" style="font-size: 28px;"></i>
                                </div>
                            </div>
                            <div class="col-10 d-flex flex-column justify-content-center pr-0">
                                <span class="user_name">Nome do cliente</span>
                                <p class="m-0 user_email">user@domain.com</p>
                            </div>
                        </div>
                        <!-- /End #user_info -->
                    </div>
                    <!-- /End #order_header -->

                    <div id="order_products" class="">
                        <table class="table">
                            <thead>
                                <tr class="row">
                                    <th class="col-5" scope="col">Produto(s)</th>
                                    <th class="col-2" scope="col">Ref.</th>
                                    <th class="col-1" scope="col">Qtd.</th>
                                    <th class="col-2" scope="col">Val. Unit.</th>
                                    <th class="col-2" scope="col">Val. Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="" class="fake-lines row pl-2 pr-2">
                                    <th class="col-12 p-0"><span class="mt-2 mb-2" style="width: 100%; height: 12px; background: #DBDBDB; display: block;"></span></th>
                                    <th class="col-12 p-0"><span class="mt-2 mb-2" style="width: 100%; height: 12px; background: #DBDBDB; display: block;"></span></th>
                                    <th class="col-12 p-0"><span class="mt-2 mb-2" style="width: 100%; height: 12px; background: #DBDBDB; display: block;"></span></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /End #order_products  -->

                    <div id="order_services" class="">
                        <table class="table">
                            <thead>
                                <tr class="row">
                                    <th class="col-5" scope="col">Serviço(s)</th>
                                    <th class="col-2" scope="col">Ref.</th>
                                    <th class="col-1" scope="col">Qtd.</th>
                                    <th class="col-2" scope="col">Val. Unit.</th>
                                    <th class="col-2" scope="col">Val. Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="fake-lines row pl-2 pr-2">
                                    <th class="col-12 p-0"><span class="mt-2 mb-2" style="width: 100%; height: 12px; background: #DBDBDB; display: block;"></span></th>
                                    <th class="col-12 p-0"><span class="mt-2 mb-2" style="width: 100%; height: 12px; background: #DBDBDB; display: block;"></span></th>
                                    <th class="col-12 p-0"><span class="mt-2 mb-2" style="width: 100%; height: 12px; background: #DBDBDB; display: block;"></span></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /End #order_services -->
                </div>
                <!-- /End #new_order_data  -->

                <div class="col-12 mt-4 mb-4 p-0 d-flex justify-content-end">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#customerModal"><i class="fas fa-user-plus"></i> Adicionar cliente ao pedido</button>
                    <button class="btn btn-primary mr-2"><i class="fas fa-shipping-fast"></i> Adicionar frete</button>
                    <button class="btn btn-primary"><i class="fas fa-percent"></i> Aplicar desconto</button>
                </div>

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

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalFinalizarCompra">Finalizar compra</button>
            </div>
            <!-- /End new order -->

            <!-- Modal Insert Customer -->
            <div id="customerModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="customerModalLabel">Buscar cliente</h5>

                            <button type="button" class="btn btn-primary mr-4 float-right" data-toggle="modal" data-target="#registerCustomer"><i class="fas fa-user-plus"></i> Cadastrar cliente</button>
                            <button type="button" class="close position-absolute" style="top: 12px; right: 12px;" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="border-bottom">
                                <form id="add_customer_to_order" action="">
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <label for="customer_name" class="form-check-label mb-2">Digite o nome ou código do cliente</label>
                                            <input type="text" id="customer_name" name="customer_name" class="form-control col-12" onkeyup="looking_for_customer(jQuery(this).val())" placeholder="Digite o nome ou CPF">
                                        </div>
                                    </div>
                                </form>
                                <!-- /End #add_customer_to_order -->
                            </div>
                            <!-- /End #form_search_customer -->

                            <div id="customer-results" class="">
                            
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /End #modal_insert_customer -->

            <!-- Modal finalizar compra/imprimir nota -->
            <div id="modalFinalizarCompra" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalFinalizarCompra" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center" style="width: 100%;">Pedido <?php echo "#" . $order_id; ?> finalizado com sucesso!</h4>
                        <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close" style="right: 16px;"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center">
                        <h2><i class="fas fa-check-circle"></i></h2>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-primary mr-2"><i class="fas fa-print"></i> Imprimir pedido</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reload_page_for_new_order()"><i class="fas fa-cart-plus"></i> Adicionar novo</button>
                    </div>
                </div>
            </div>
            <!-- /End #modalFinalizarCompra -->
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

    function looking_for_customer(value)
    {
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                action: 'search_customer',
                term: value
            },
            success: function(data)
            {
                // console.log(data);
                jQuery('#customer-results').html(data);
            }
        });
    }

    // Ajax for add to cart
    function add_product_on_order(order_id, product_id, product_name, product_ref, product_qty, product_unt_val, product_type)
    {
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                action: 'add_product_and_update_order',
                order_id: order_id,
                item_id: product_id,
                item_qtd: product_qty,
            },
            success: function(data){
                // console.log(data);
                insert_product_on_list_order(product_id, product_name, product_ref, product_qty, product_unt_val, product_type);
                show_subtotal_curr_order(order_id);
            }
        });
    }

    // Ajax add customer on order
    function add_customer_on_order(order_id, customer_id, customer_name, customer_email)
    {
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                action: 'add_customer_and_update_order',
                order: order_id,
                customer: customer_id 
            },
            success: function(data)
            {
                // var success_alert = '<div class="alert alert-success fixed-bottom" role="alert">Adicionado com sucesso!</div>';
                // jQuery('#customerModal').append(success_alert);
                jQuery('#user_info .user_name').text(customer_name);
                jQuery('#user_info .user_email').text(customer_email);
                jQuery('#customerModal').modal('hide');
                // console.log(customer_name);
                // console.log(customer_email);
            }
        });
    }

    /** 
    * Fires function/action for sum subtotal of current order
    * Dispara uma função/ação para somar o subtotal do pedido atual
    *
    * @param {*} order_id
    */
    function show_subtotal_curr_order(order_id)
    {
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                action: 'calculate_subtotal_curr_order',
                order_id: order_id
            },
            success: function(data)
            {
                jQuery('#total_cart').text('R$' + data);
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
    function insert_product_on_list_order(id, name, ref,  qty, valUnt, itemType)
    {
        var valTotal = calc_total_item(qty, valUnt);

        let itemToAdd = '<tr class="d-flex" style="font-size: 11px;"><td class="col-5" style="font-weight: 700;">'+ name +'</td><td class="col-2">'+ ref +'</td><td class="col-1">'+ qty +'</td><td class="col-2">'+ valUnt +'</td><td class="amount_item col-2">'+ valTotal +'</td></tr>';

        if (itemType == 'servico')
        {
            var tableToAdd = jQuery('#order_services > table > tbody'),
                fakeLines = jQuery('#order_services > table > tbody > .fake-lines');

            tableToAdd.prepend(itemToAdd);
            fakeLines.children().last().remove();
            total_services_on_order();
        }
        else
        {
            var tableToAdd = jQuery('#order_products > table > tbody'),
                fakeLines = jQuery('#order_products > table > tbody > .fake-lines');

            tableToAdd.prepend(itemToAdd);
            fakeLines.children().last().remove();
            sum_total_products_added();
        }
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
        valUnt = valUnt.replace(',', '.');
        
        return qty * parseFloat(valUnt);
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
        * Function total_services_on_order()
        * 
        * Soma do total de serviços adicionados na nota
        * 
        * @since beta_1.0.0
        */
    function total_services_on_order()
    {
        var totalOnTable = jQuery('#order_services tbody tr td.amount_item');

        var sum = 0.0;
        totalOnTable.each(function(){

            sum = parseFloat(jQuery(this).text()) + sum;
            
        });
        jQuery('#total_services').text('R$' + sum).maskMoney();
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
        var valuesOnTable = jQuery('#order_products tbody tr td.amount_item');

        var sumTotal = 0.0;
        valuesOnTable.each(function(){

            sumTotal = parseFloat(jQuery(this).text()) + sumTotal;
        });
        jQuery('#total_products').maskMoney().text('R$' + sumTotal);
    }

    /**
        * Function reload_page_for_new_order
        *
        * Força o reload da página para gerar um novo pedido
        *
        * @since beta_1.0.0
        */
    function reload_page_for_new_order()
    {
        document.location.reload(true);
    }

</script>