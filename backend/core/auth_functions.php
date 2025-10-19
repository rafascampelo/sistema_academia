<?php
// Arquivo: auth_functions.php

/**
 * Verifica se o usuário está logado.
 * @return bool
 */
function esta_logado() {
    return isset($_SESSION['logado']) && $_SESSION['logado'] === true;
}

/**
 * Verifica se o usuário é um Administrador (ADM).
 * @return bool
 */
function is_adm() {
    // Definimos o ADM como o 'Coordenador da Academia'
    return esta_logado() && $_SESSION['funcao'] === 'ADM'; 
}

/**
 * Verifica se o usuário é um Supervisor (ou ADM).
 * @return bool
 */
function is_supervisor() {
    // O supervisor é o Professor 
    return esta_logado() && (
        $_SESSION['funcao'] === 'Professor' ||
        is_adm()
    );
}

/**
 * Função de segurança para proteger páginas que exigem login.
 */
function proteger_pagina() {
    if (!esta_logado()) {
        header('Location: login.php');
        exit();
    }
}