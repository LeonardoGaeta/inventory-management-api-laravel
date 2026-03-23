# Inventory Management API (Laravel)

Backend de um sistema de gerenciamento de estoque desenvolvido com Laravel, responsável pelo controle de produtos, clientes e pedidos.

## 📌 Visão Geral

API REST construída com foco em organização, boas práticas e escalabilidade, permitindo:

* Gerenciamento de produtos e estoque
* Cadastro e controle de clientes
* Criação e acompanhamento de pedidos
* Registro de auditoria de ações
* Estrutura padronizada seguindo boas práticas do Laravel

## ⚙️ Tecnologias

* Laravel 11
* PHP 8+
* SQLite

## 🚀 Funcionalidades

* CRUD de produtos, clientes e pedidos
* Controle automático de estoque
* Relacionamento entre entidades
* Cálculo de valores em pedidos
* Sistema de auditoria (criação, edição e exclusão)

## 👥 Colaboração

Projeto desenvolvido em conjunto com outro desenvolvedor.

## ▶️ Como rodar o projeto

```bash
git clone <seu-repositorio>
cd inventory-management-api

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate

php artisan serve
```

## 📄 Observações

Este projeto foi desenvolvido com foco em prática de desenvolvimento backend, arquitetura REST e organização de código.
