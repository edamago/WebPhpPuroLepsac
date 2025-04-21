<?php

class View {
    public static function render($template, $vars = []) {
        extract($vars);

        // Construye la ruta al archivo de la vista
        $file = $_SERVER['DOCUMENT_ROOT'] . '/jabones/views/' . $template . '.php';

        if (file_exists($file)) {
            include $file;
        } else {
            echo "Vista no encontrada: $file";
        }
    }
}
