<?php
//require_once '../../../models/Pedido.php';
//require_once '../../../models/DetallePedido.php';
require_once __DIR__ . '/../../models/PedidoModel.php';
require_once __DIR__ . '/../../models/DetallePedidoModel.php';

class PedidoApiController
{
    public function crear($data)
    {
        $pedidoData = $data["pedido"] ?? null;
        $detallesData = $data["detalles"] ?? [];

        if (!$pedidoData || count($detallesData) === 0) {
            return [
                "status" => 400,
                "error" => "Debe incluir datos del pedido y al menos un detalle."
            ];
        }

        $pedidoModel = new Pedido();
        $detalleModel = new DetallePedido();

        try {
            $pdo = $pedidoModel->getConnection();
            $pdo->beginTransaction();

            $pedido_id = $pedidoModel->crear($pedidoData, $pdo);

            foreach ($detallesData as $detalle) {
                $detalle['t_pedido_id'] = $pedido_id;
                $detalleModel->crear($detalle, $pdo);
            }

            $pdo->commit();
            return [
                "status" => 200,
                "success" => true,
                "pedido_id" => $pedido_id
            ];

        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return [
                "status" => 500,
                "error" => "Error al guardar el pedido: " . $e->getMessage()
            ];
        }
    }
}
