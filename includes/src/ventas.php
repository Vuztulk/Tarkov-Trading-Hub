<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\clases\Item_mercado;
use es\ucm\fdi\aw\clases\usuarios\FormularioEliminarVenta;
use es\ucm\fdi\aw\clases\Historial_ventas;

require_once './includes/src/clases/Historial_ventas.php';
function inicia_venta()
{
    $id_item = isset($_GET['id_item']) ? $_GET['id_item'] : null;
    $formVenta = new \es\ucm\fdi\aw\clases\usuarios\FormularioVenta($id_item);
    $formVenta = $formVenta->gestiona();
    echo $formVenta;
}

function mis_ventas()
{
    $listaItems = Item_mercado::misVentas($_SESSION['idUsuario']);
    if (empty($listaItems)) {
        return '<p>No tienes ventas activas en este momento</p>';
    }

    $items = '';
    foreach ($listaItems as $venta) {
        $precio = $venta->getPrecio();
        $tipo = $venta->getTipo();
        if ($tipo == "intercambio") {
            $precio = "<span class='precio-intercambio'> <img src='./css/img/img_items/{$venta->getNombre_intercambio()}.png' alt='{$venta->getNombre_intercambio()}'/></span>";
        } else if ($tipo == "dual") {
            $precio .= "<span class='precio-intercambio'>$ & <img src='./css/img/img_items/{$venta->getNombre_intercambio()}.png' alt='{$venta->getNombre_intercambio()}'/></span>";
        }

        $formularioEliminarVenta = new FormularioEliminarVenta($venta->getId());
        $formularioEliminarVenta = $formularioEliminarVenta->gestiona();

        $items .= <<<EOS
        <div class="item-container">

            <div class="item">

                <div class="foto_item">
                    <img src='./css/img/img_items/{$venta->getNombreItem()}.png' alt='{$venta->getNombreItem()}'/>
                </div>

                <div class="nombre_item">
                    {$venta->getNombreItem()}
                </div>
                
                <div class="nombre_usuario">
                    {$venta->getNombreUsuario($venta->getId_usuario())}
                </div>

                <div class="precio_item">
                    {$precio} 
                </div>

            </div>

            <div class="comprar_item">
            {$formularioEliminarVenta}
            </div>
        </div>

        EOS;
    }

    $html = <<<EOS
    <div class="guia">
        <div>Foto</div>
        <div class = "div-opacidad">Nombre item</div>
        <div class = "div-opacidad">Nombre usuario</div>
        <div class = "div-opacidad">Precio</div>
        <div class = "div-opacidad">Comprar</div>
    </div>
    <div class="lista_items">
        {$items}
    </div>
    EOS;

    return $html;
}

function historial_ventas()
{
    $listaVentas = Historial_ventas::ventasUsuario($_SESSION['idUsuario']);
    $items = '';

    foreach ($listaVentas as $venta) {

        $items .= <<<EOS
        <div class="item">

            <div class="foto_item">
                <img src='./css/img/img_items/{$venta->getNombreItem()}.png' alt='{$venta->getNombreItem()}'/>
            </div>

            <div class="nombre_item">
                {$venta->getNombreItem()}
            </div>
            
            <div class="nombre_comprador">
                {$venta->getNombreComprador()}
            </div>

            <div class="precio_venta">
                {$venta->getPrecio()} 
            </div>

            <div class="fecha">
                {$venta->getFecha()}
            </div>

        </div>

        EOS;
    }
    $html = <<<EOS
        <div class="guia">
            <div>Foto</div>
            <div class = "div-opacidad">Nombre item</div>
            <div class = "div-opacidad">Nombre comprador</div>
            <div class = "div-opacidad">Precio de venta</div>
            <div class = "div-opacidad">Fecha</div>
        </div>
        <div class="lista_items">
            {$items}
        </div>
    EOS;
    return $html;
}


?>
