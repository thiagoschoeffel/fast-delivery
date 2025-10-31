<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	https://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */
$route['default_controller'] = 'app';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'app/login';
$route['login/processar']['POST'] = 'app/processar_login';
$route['logout']['POST'] = 'app/logout';
$route['inicio'] = 'app/dashboard';
$route['inicio/grafico'] = 'app/dashboard_grafico';

$route['inicio/producao'] = 'app/producao';
$route['inicio/producao/filtrar']['POST'] = 'app/producao_filtrar';
$route['inicio/embalagem'] = 'app/embalagem';
$route['inicio/embalagem/filtrar']['POST'] = 'app/embalagem_filtrar';
$route['inicio/entrega'] = 'app/entrega';
$route['inicio/entrega/imprimir/(:num)'] = 'app/entrega_imprimir/$1';

$route['entregadores'] = 'entregador/index';
$route['entregadores/(:num)'] = 'entregador/index';
$route['entregadores/filtrar'] = 'entregador/filtrar';
$route['entregadores/cadastrar'] = 'entregador/cadastrar';
$route['entregadores/editar/(:num)'] = 'entregador/editar/$1';
$route['entregadores/cadastrar/salvar']['POST'] = 'entregador/salvar';
$route['entregadores/editar/salvar']['POST'] = 'entregador/atualizar';
$route['entregadores/deletar']['POST'] = 'entregador/deletar';

$route['veiculos'] = 'veiculo/index';
$route['veiculos/(:num)'] = 'veiculo/index';
$route['veiculos/filtrar'] = 'veiculo/filtrar';
$route['veiculos/cadastrar'] = 'veiculo/cadastrar';
$route['veiculos/editar/(:num)'] = 'veiculo/editar/$1';
$route['veiculos/cadastrar/salvar']['POST'] = 'veiculo/salvar';
$route['veiculos/editar/salvar']['POST'] = 'veiculo/atualizar';
$route['veiculos/deletar']['POST'] = 'veiculo/deletar';

$route['formaspagamento'] = 'formapagamento/index';
$route['formaspagamento/(:num)'] = 'formapagamento/index';
$route['formaspagamento/filtrar'] = 'formapagamento/filtrar';
$route['formaspagamento/cadastrar'] = 'formapagamento/cadastrar';
$route['formaspagamento/editar/(:num)'] = 'formapagamento/editar/$1';
$route['formaspagamento/cadastrar/salvar']['POST'] = 'formapagamento/salvar';
$route['formaspagamento/editar/salvar']['POST'] = 'formapagamento/atualizar';
$route['formaspagamento/deletar']['POST'] = 'formapagamento/deletar';

$route['clientes'] = 'cliente/index';
$route['clientes/(:num)'] = 'cliente/index';
$route['clientes/filtrar'] = 'cliente/filtrar';
$route['clientes/cadastrar'] = 'cliente/cadastrar';
$route['clientes/editar/(:num)'] = 'cliente/editar/$1';
$route['clientes/cadastrar/salvar']['POST'] = 'cliente/salvar';
$route['clientes/editar/salvar']['POST'] = 'cliente/atualizar';
$route['clientes/deletar']['POST'] = 'cliente/deletar';

$route['clientesenderecos/cadastrar/salvar']['POST'] = 'clienteendereco/salvar';
$route['clientesenderecos/editar_status/salvar']['POST'] = 'clienteendereco/atualizar_status';
$route['clientesenderecos/deletar']['POST'] = 'clienteendereco/deletar';

$route['produtos'] = 'produto/index';
$route['produtos/(:num)'] = 'produto/index';
$route['produtos/filtrar'] = 'produto/filtrar';
$route['produtos/cadastrar'] = 'produto/cadastrar';
$route['produtos/editar/(:num)'] = 'produto/editar/$1';
$route['produtos/cadastrar/salvar']['POST'] = 'produto/salvar';
$route['produtos/editar/salvar']['POST'] = 'produto/atualizar';
$route['produtos/deletar']['POST'] = 'produto/deletar';

$route['produtosestruturas/cadastrar/salvar']['POST'] = 'produtoestrutura/salvar';
$route['produtosestruturas/deletar']['POST'] = 'produtoestrutura/deletar';

$route['pedidos'] = 'pedido/index';
$route['pedidos/(:num)'] = 'pedido/index';
$route['pedidos/filtrar'] = 'pedido/filtrar';
$route['pedidos/cadastrar'] = 'pedido/cadastrar';
$route['pedidos/editar/(:num)'] = 'pedido/editar/$1';
$route['pedidos/cadastrar/salvar']['POST'] = 'pedido/salvar';
$route['pedidos/editar/salvar']['POST'] = 'pedido/atualizar';
$route['pedidos/editar/atualizar_status']['POST'] = 'pedido/atualizar_status';
$route['pedidos/editar/atualizar_sequencia_entrega']['POST'] = 'pedido/atualizar_sequencia_entrega';
$route['pedidos/deletar']['POST'] = 'pedido/deletar';
$route['pedidos/duplicar']['POST'] = 'pedido/duplicar';

$route['pedidositens/cadastrar/salvar']['POST'] = 'pedidoitem/salvar';
$route['pedidositens/editar_status/salvar']['POST'] = 'pedidoitem/atualizar_status';
$route['pedidositens/deletar']['POST'] = 'pedidoitem/deletar';
