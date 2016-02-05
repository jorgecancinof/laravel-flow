<?php

/*
 * Flow API
 *
 * Version: 1.2
 * Date:    2015-05-25
 * Author:  flow.cl
 */

namespace CokeCancino\LaravelFlow;

use Exception;

class Flow {

    protected $order = array();

    //Método constructor de compatibilidad
    function flowAPI() {;
        $this->__construct();
    }

    //Constructor de la clase
    function __construct() {
        //global $flow_medioPago;
        $this->order["OrdenNumero"] = "";
        $this->order["Concepto"] = "";
        $this->order["Monto"] = "";
        $this->order["MedioPago"] = config('flow.medioPago');
        $this->order["FlowNumero"] = "";
        $this->order["Pagador"] = "";
        $this->order["Status"] = "";
        $this->order["Error"] = "";
    }

    // Metodos SET

    /**
     * Set el número de Orden del comercio
     *
     * @param string $orderNumer El número de la Orden del Comercio
     *
     * @return bool (true/false)
     */
    public function setOrderNumber($orderNumber) {
        if(!empty($orderNumber)) {
            $this->order["OrdenNumero"] = $orderNumber;
        }
        $this->flow_log("Asigna Orden N°: ". $this->order["OrdenNumero"], '');
        return !empty($orderNumber);
    }

    /**
     * Set el concepto de pago
     *
     * @param string $concepto El concepto del pago
     *
     * @return bool (true/false)
     */
    public function setConcept($concepto) {
        if(!empty($concepto)) {
            $this->order["Concepto"] = $concepto;
        }
        return !empty($concepto);
    }

    /**
     * Set el monto del pago
     *
     * @param string $monto El monto del pago
     *
     * @return bool (true/false)
     */
    public function setAmount($monto) {
        if(!empty($monto)) {
            $this->order["Monto"] = $monto;
        }
        return !empty($monto);
    }

    /**
     * Set Medio de Pago, por default el Medio de Pago será el configurada en config.php
     *
     * @param string $medio El Medio de Pago de esta orden
     *
     * @return bool (true/false)
     */
    public function setMedio($medio) {
        if(!empty($medio)) {
            $this->order["MedioPago"] = $medio;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Set pagador, el email del pagador de esta orden
     *
     * @param string $email El email del pagador de la orden
     *
     * @return bool (true/false)
     */
    public function setPagador($email) {
        if(!empty($email)) {
            $this->order["Pagador"] = $email;
            return TRUE;
        } else {
            return FALSE;
        }
    }


    // Metodos GET

    /**
     * Get el número de Orden del Comercio
     *
     * @return string el número de Orden del comercio
     */
    public function getOrderNumber() {
        return $this->order["OrdenNumero"];
    }

    /**
     * Get el concepto de Orden del Comercio
     *
     * @return string el concepto de Orden del comercio
     */
    public function getConcept() {
        return $this->order["Concepto"];
    }

    /**
     * Get el monto de Orden del Comercio
     *
     * @return string el monto de la Orden del comercio
     */
    public function getAmount() {
        return $this->order["Monto"];
    }

    /**
     * Get el Medio de Pago para de Orden del Comercio
     *
     * @return string el Medio de pago de esta Orden del comercio
     */
    public function getMedio() {
        return $this->order["MedioPago"];
    }

    /**
     * Get el estado de la Orden del Comercio
     *
     * @return string el estado de la Orden del comercio
     */
    public function getStatus() {
        return $this->order["Status"];
    }

    /**
     * Get el número de Orden de Flow
     *
     * @return string el número de la Orden de Flow
     */
    public function getFlowNumber() {
        return $this->order["FlowNumero"];
    }

    /**
     * Get el email del pagador de la Orden
     *
     * @return string el email del pagador de la Orden de Flow
     */
    public function getPayer() {
        return $this->order["Pagador"];
    }


    /**
     * Crea una nueva Orden para ser enviada a Flow
     *
     * @param string $orden_compra El número de Orden de Compra del Comercio
     * @param string $monto El monto de Orden de Compra del Comercio
     * @param string $concepto El concepto de Orden de Compra del Comercio
     * @param string $email_pagador El email del pagador de Orden de Compra del Comercio
     * @param mixed $medioPago El Medio de Pago (1,2,9)
     *
     * @return string flow_pack Paquete de datos firmados listos para ser enviados a Flow
     */
    public function new_order($orden_compra, $monto,  $concepto, $email_pagador, $medioPago = "Non") {
        //global $flow_medioPago;
        $this->flow_log("Iniciando nueva Orden", "new_order");
        if(!isset($orden_compra,$monto,$concepto)) {
            $this->flow_log("Error: No se pasaron todos los parámetros obligatorios","new_order");
        }
        if($medioPago == "Non") {
            $medioPago = config('flow.medioPago');
        }
        if(!is_numeric($monto)) {
            $this->flow_log("Error: El parámetro monto de la orden debe ser numérico","new_order");
            throw new Exception("El monto de la orden debe ser numérico");
        }
        $this->order["OrdenNumero"] = $orden_compra;
        $this->order["Concepto"] = $concepto;
        $this->order["Monto"] = $monto;
        $this->order["MedioPago"] = $medioPago;
        $this->order["Pagador"] = $email_pagador;
        return $this->flow_pack();
    }

    /**
     * Lee los datos enviados desde Flow a la página de confirmación del comercio
     *
     */
    public function read_confirm() {
        if(!isset($_POST['response'])) {
            $this->flow_log("Respuesta Inválida", "read_confirm");
            throw new Exception('Invalid response');
        }
        $data = $_POST['response'];
        $params = array();
        parse_str($data, $params);
        if(!isset($params['status'])) {
            $this->flow_log("Respuesta sin status", "read_confirm");
            throw new Exception('Invalid response status');
        }
        $this->order['Status'] = $params['status'];
        $this->flow_log("Lee Status: " . $params['status'], "read_confirm");
        if (!isset($params['s'])) {
            $this->flow_log("Mensaje no tiene firma", "read_confirm");
            throw new Exception('Invalid response (no signature)');
        }
        if(!$this->flow_sign_validate($params['s'], $data)) {
            $this->flow_log("firma invalida", "read_confirm");
            throw new Exception('Invalid signature from Flow');
        }
        $this->flow_log("Firma verificada", "read_confirm");
        if($params['status'] == "ERROR") {
            $this->flow_log("Error: " .$params['kpf_error'], "read_confirm");
            $this->order["Error"] = $params['kpf_error'];
            return;
        }
        if(!isset($params['kpf_orden'])) {
            throw new Exception('Invalid response Orden number');
        }
        $this->order['OrdenNumero'] = $params['kpf_orden'];
        $this->flow_log("Lee Numero Orden: " . $params['kpf_orden'], "read_confirm");
        if(!isset($params['kpf_monto'])) {
            throw new Exception('Invalid response Amount');
        }
        $this->order['Monto'] = $params['kpf_monto'];
        $this->flow_log("Lee Monto: " . $params['kpf_monto'], "read_confirm");
        if(isset($params['kpf_flow_order'])) {
            $this->order['FlowNumero'] = $params['kpf_flow_order'];
            $this->flow_log("Lee Orden Flow: " . $params['kpf_flow_order'], "read_confirm");
        }
        if(isset($params['kpf_pagador'])) {
            $this->order['Pagador'] = $params['kpf_pagador'];
        }

    }

    /**
     * Método para responder a Flow el resultado de la confirmación del comercio
     *
     * @param bool $result (true: Acepta el pago, false rechaza el pago)
     *
     * @return string paquete firmado para enviar la respuesta del comercio
     */
    public function build_response($result){
        //global $flow_comercio;
        $r = ($result) ? "ACEPTADO" : "RECHAZADO";
        $data = array();
        $data["status"] = $r;
        $data["c"] = config('flow.comercio');
        $q = http_build_query($data);
        $s = $this->flow_sign($q);
        $this->flow_log("Orden N°: ".$this->order["OrdenNumero"]. " - Status: $r","flow_build_response");
        return $q."&s=".$s;
    }

    /**
     * Método para recuperar los datos  en la página de Exito o Fracaso del Comercio
     *
     */
    public function read_result() {
        if(!isset($_POST['response'])) {
            $this->flow_log("Respuesta Inválida", "read_result");
            throw new Exception('Invalid response');
        }
        $data = $_POST['response'];
        $params = array();
        parse_str($data, $params);
        if (!isset($params['s'])) {
            $this->flow_log("Mensaje no tiene firma", "read_result");
            throw new Exception('Invalid response (no signature)');
        }
        if(!$this->flow_sign_validate($params['s'], $data)) {
            $this->flow_log("firma invalida", "read_result");
            throw new Exception('Invalid signature from Flow');
        }
        $this->order["Comision"] = config('flow.tasa_default');
        $this->order["Status"] = "";
        $this->order["Error"] = "";
        $this->order['OrdenNumero'] = $params['kpf_orden'];
        $this->order['Concepto'] = $params['kpf_concepto'];
        $this->order['Monto'] = $params['kpf_monto'];
        $this->order["FlowNumero"] = $params["kpf_flow_order"];
        $this->order["Pagador"] = $params["kpf_pagador"];
        $this->flow_log("Datos recuperados Orden de Compra N°: " .$params['kpf_orden'], "read_result");
    }

    /**
     * Registra en el Log de Flow
     *
     * @param string $message El mensaje a ser escrito en el log
     * @param string $type Identificador del mensaje
     *
     */
    public function flow_log($message, $type) {
        //global $flow_logPath;
        $file = fopen(config('flow.logPath') . "/flowLog_" . date("Y-m-d") .".txt" , "a+");
        fwrite($file, "[".date("Y-m-d H:i:s.u")." ".getenv('REMOTE_ADDR')." ".getenv('HTTP_X_FORWARDED_FOR')." - $type ] ".$message . PHP_EOL);
        fclose($file);
    }


    // Funciones Privadas
    private function flow_get_public_key_id() {
        //global $flow_keys;
        try {
            $fp = fopen(__DIR__."/keys/flow.pubkey", "r");
            $pub_key = fread($fp, 8192);
            fclose($fp);
            return openssl_get_publickey($pub_key);
        } catch (Exception $e) {
            $this->flow_log("Error al intentar obtener la llave pública - Error-> " .$e->getMessage(), "flow_get_public_key_id");
            throw new Exception($e->getMessage());
        }
    }

    private function flow_get_private_key_id() {
        //global $flow_keys;
        try {
            $fp = fopen(config('flow.keys')."/comercio.pem", "r");
            $priv_key = fread($fp, 8192);
            fclose($fp);
            return openssl_get_privatekey($priv_key);
        } catch (Exception $e) {
            $this->flow_log("Error al intentar obtener la llave privada - Error-> " .$e->getMessage(), "flow_get_private_key_id");
            throw new Exception($e->getMessage());
        }
    }

    private function flow_sign($data) {
        $priv_key_id = $this->flow_get_private_key_id();
        if(! openssl_sign($data, $signature, $priv_key_id)) {
            $this->flow_log("No se pudo firmar", "flow_sign");
            throw new Exception('It can not sign');
        };
        return base64_encode($signature);
    }

    private function flow_sign_validate($signature, $data) {

        $signature = base64_decode($signature);
        $response = explode("&s=", $data, 2);
        $response = $response[0];

        $pub_key_id = $this->flow_get_public_key_id();
        return (openssl_verify($response, $signature, $pub_key_id) == 1);
    }

    private function flow_pack() {
        //global $flow_comercio, $flow_url_exito, $flow_url_fracaso, $flow_url_confirmacion, $flow_tipo_integracion;
        $tipo_integracion = urlencode(config('flow.tipo_integracion'));
        $comercio = urlencode(config('flow.comercio'));
        $orden_compra = urlencode($this->order["OrdenNumero"]);
        $monto = urlencode($this->order["Monto"]);
        $medioPago = urlencode($this->order["MedioPago"]);
        $email = urlencode($this->order["Pagador"]);

        $hConcepto = htmlentities($this->order["Concepto"]);
        if (!$hConcepto) $hConcepto = htmlentities($concepto, ENT_COMPAT | ENT_HTML401, 'UTF-8');
        if (!$hConcepto) $hConcepto = htmlentities($concepto, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
        if (!$hConcepto) $hConcepto = "Orden de Compra $orden_compra";

        $concepto = urlencode($hConcepto);

        $url_exito = urlencode($this->generarUrl(config('flow.url_exito')));
        $url_fracaso = urlencode($this->generarUrl(config('flow.url_fracaso')));
        $url_confirmacion = urlencode($this->generarUrl(config('flow.url_confirmacion')));

        $p = "c=$comercio&oc=$orden_compra&mp=$medioPago&m=$monto&o=$concepto&ue=$url_exito&uf=$url_fracaso&uc=$url_confirmacion&ti=$tipo_integracion&e=$email&v=kit_1_2";

        $signature = $this->flow_sign($p);
        $this->flow_log("Orden N°: ".$this->order["OrdenNumero"]. " -empaquetado correcto","flow_pack");
        return $p."&s=$signature";
    }

    /**
     * Genera una URL de acuerdo al método de Laravel
     *
     * @param  array  $url
     * @return string
     */
    private function generarUrl($url)
    {
        if (array_key_exists('url', $url)) {
            return url($url['url']);
        }

        if (array_key_exists('route', $url)) {
            return route($url['route']);
        }

        if (array_key_exists('action', $url)) {
            return action($url['action']);
        }

        return '';
    }
}
