<?php
/**
 * Configuracion de Base de Datos
 * Inventio Max v9.0
 * 
 * INSTRUCCIONES:
 * 1. Modifica los valores de acuerdo a tu servidor de base de datos
 * 2. Asegurate de crear la base de datos con el nombre especificado
 * 3. Importa el archivo SQL de estructura desde /sql/inventiomax.sql
 */

return [
    // Servidor de base de datos
    'host' => 'localhost',
    
    // Puerto MySQL (default: 3306)
    'port' => 3306,
    
    // Usuario de la base de datos
    'user' => 'root',
    
    // Contrasena del usuario
    'pass' => '',
    
    // Nombre de la base de datos
    'database' => 'inventiomax',
    
    // Charset (recomendado: utf8mb4)
    'charset' => 'utf8mb4',
];
?>
