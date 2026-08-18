<?php
declare(strict_types=1);

abstract class transacao //molde para as transacoes, nao pode ser instanciada
{
    //protect para as classes filhas herdarem
    protected ?int $id = null;
    protected float $valor;
    protected string $descricao;
    protected string $data;

    public function __construct( //Define os dados obrigatorios para criar uma transacao
        float $valor,
        string $descricao,
        string $data,
        ?int $id = null
        )
    {
        $this->valor = $valor;
        $this->descricao = $descricao;
        $this->data = $data;
        $this->id = $id;
    }

    public function getId(): ?int //retorna o id da transacao
    {
        return $this->id;
    }

    public function setId(int $id): void //define o id da transacao
    {
        $this->id = $id;
    }

    public function getValor(): float //retorna o valor da transacao
    {
        return $this->valor;
    }

    public function getDescricao(): string //retorna a descricao da transacao
    {
        return $this->descricao;
    }

    public function getData(): string //retorna a data da transacao
    {
        return $this->data;
    }

    abstract public function getTipo(): string; //metodo abstrato que obriga as classes filhas terem metodo getTipo
}