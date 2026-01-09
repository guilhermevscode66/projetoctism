<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de controle de horários</title>
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    /* Destaque visual para botões de mostrar/ocultar senha quando pressionados */
    .mostrarsenha[aria-pressed="true"] {
        outline: 2px solid #198754; /* cor success (verde) */
        background-color: #e6f4ea; /* leve fundo verde claro */
        color: #145c2a;
        box-shadow: 0 0 0 0.2rem rgba(25,135,84,0.12);
    }
    </style>

    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <a href="home.php" class="btn btn-primary">Home</a>
    

<?php
session_start();
    ?>
    <main>