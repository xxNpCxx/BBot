<?php


namespace BBot\Exchange;

/**
 * Отвечает за хранение и
 * управление информацией об аккаунте
 */
interface Account
{
    public function fetchBalance();
    public function getBalance();
    public function getName();
}