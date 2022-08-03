<?php
namespace MicroBlog;
use PDO, Exception;

final class Noticia{
    private int $id;
    private string	$data;	
    private string $titulo;	
    private string $texto;	
    private string $resumo;	
    private string $imagem;	
    private string $destaque;	
    private int $usuario_id;	
    private int $categoria_id;
    private PDO $conexao;

    public function __construct(){ //método que funciona na criação do objeto
        $this->conexao = Banco::conecta();
    }

    public function listar():array{
        $sql = "SELECT id, data, titulo, texto, resumo, imagem, destaque, usuario_id, categoria_id FROM noticias ORDER BY data";
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
        return $resultado;
    }
    public function inserir():void{
        $sql = "INSERT INTO  noticias(data, titulo, texto, resumo, imagem, destaque, usuario_id, categoria_id) VALUES (:data, :titulo, :texto, :resumo, :imagem, :destaque, :usuario_id, :categoria_id) "; //named param
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":data", $this->nome, PDO::PARAM_STR);
            $consulta->bindParam(":titulo", $this->nome, PDO::PARAM_STR);
            $consulta->bindParam(":texto", $this->nome, PDO::PARAM_STR);
            $consulta->bindParam(":imagem", $this->nome, PDO::PARAM_STR);
            $consulta->bindParam(":destaque", $this->nome, PDO::PARAM_STR);
            $consulta->bindParam(":usuario_id", $this->nome, PDO::PARAM_INT);
            $consulta->bindParam(":categoria_id", $this->nome, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
    }
    public function listarUm():array{
        $sql = "SELECT * FROM noticias WHERE id = :id"; //named param
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
        return $resultado;
    }

    public function atualizar():void{
        $sql = "UPDATE  noticias SET data = :data, titulo = :titulo, texto = :texto, imagem = :imagem, destaque = :destaque, usuario_id = :usuario_id, categoria_id = :categoria_id WHERE id = :id"; //named param
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":data", $this->nome, PDO::PARAM_STR);
            $consulta->bindParam(":titulo", $this->nome, PDO::PARAM_STR);
            $consulta->bindParam(":texto", $this->nome, PDO::PARAM_STR);
            $consulta->bindParam(":imagem", $this->nome, PDO::PARAM_STR);
            $consulta->bindParam(":destaque", $this->nome, PDO::PARAM_STR);
            $consulta->bindParam(":usuario_id", $this->nome, PDO::PARAM_INT);
            $consulta->bindParam(":categoria_id", $this->nome, PDO::PARAM_INT);
            $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
    }
    public function excluir():void{
        $sql = "DELETE FROM  noticias  WHERE id = :id"; //named param
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
    }


    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }


    public function getData()
    {
        return $this->data;
    }
    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }


    public function getTitulo(): string
    {
        return $this->titulo;
    }
    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }


    public function getTexto(): string
    {
        return $this->texto;
    }
    public function setTexto(string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }


    public function getResumo(): string
    {
        return $this->resumo;
    }
    public function setResumo(string $resumo): self
    {
        $this->resumo = $resumo;

        return $this;
    }


    public function getImagem(): string
    {
        return $this->imagem;
    }
    public function setImagem(string $imagem): self
    {
        $this->imagem = $imagem;

        return $this;
    }


    public function getDestaque(): string
    {
        return $this->destaque;
    }
    public function setDestaque(string $destaque): self
    {
        $this->destaque = $destaque;

        return $this;
    }


    public function getUsuariosId(): int
    {
        return $this->usuario_id;
    }
    public function setUsuariosId(int $usuario_id): self
    {
        $this->usuario_id = $usuario_id;

        return $this;
    }


    public function getCategoriasId(): int
    {
        return $this->categoria_id;
    }
    public function setCategoriasId(int $categoria_id): self
    {
        $this->categoria_id = $categoria_id;

        return $this;
    }


    
}