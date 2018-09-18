<?php

/**
 * Función Genérica para el envío de email.
 *
 * Se recibe remitente, destinatario, asunto y cuerpo del mensaje por parámetros.
 * Se crea objeto YiiMailMessage para crear el mensaje del correo.
 * Se asignan al objeto los parámetros.
 * Se envía el mensaje.
 * Esta función se utiliza en todas las acciones que incluyen el envío de email.
 *
 * @param string $pfrom Remitente del correo.
 * @param string $to Destinatario del correo.
 * @param string $psubject Asunto del correo.
 * @param string $pbody Cuerpo del correo.
 *
 * @return void No devuelve nada.
 */
class EnviarMail {

    public function enviarEmail($pfrom, $to, $psubject, $pbody, $attachment)
    {
        try {
            Yii::import('application.extensions.yii-mail.YiiMailMessage');
            $message = new YiiMailMessage;
            $message->setBody($pbody, 'text/html');
            $message->setSubject($psubject);
            $toM = explode(',', $to);
            foreach ($toM as $to) {
                if (trim($to) != '') {
                    $message->addTo(trim($to));
                }
            }
            $message->from = $pfrom;
            Yii::app()->mail->send($message);
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

}

