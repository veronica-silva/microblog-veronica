<?php
namespace MicroBlog;
use PDO, Exception;
final class Noticia{
    private int $id;
    private $termo;
    private string	$data;	
    private string $titulo;	
    private string $texto;	
    private string $resumo;	
    private string $imagem;	
    private string $destaque;		
    private int $categoria_id;
    public Usuario $usuario;
    private PDO $conexao;

    public function __construct(){ //método que funciona na criação do objeto
        
        $this->usuario =  new Usuario;
        $this->conexao = $this->usuario->getConexao();
    }

    public function inserir():void{
        $sql = "INSERT INTO  noticias(titulo, texto, resumo, imagem, destaque, usuario_id, categoria_id) VALUES (:titulo, :texto, :resumo, :imagem, :destaque, :usuario_id, :categoria_id) "; //named param
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":titulo", $this->titulo, PDO::PARAM_STR);
            $consulta->bindParam(":texto", $this->texto, PDO::PARAM_STR);
            $consulta->bindParam(":resumo", $this->resumo, PDO::PARAM_STR);
            $consulta->bindParam(":imagem", $this->imagem, PDO::PARAM_STR);
            $consulta->bindParam(":destaque", $this->destaque, PDO::PARAM_STR);
            $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
            $consulta->bindParam(":categoria_id", $this->categoria_id, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
    }

    public function upload(array $arquivo){
        $tiposvalidos = ["image/png", "image/jpeg", "image/gif", "image/svg+xml"];
        if (!in_array($arquivo['type'], $tiposvalidos)) {
            die("<script>alert('Formato inválido!'); history.back()</script>");
        } 
        $nome = $arquivo['name'];
        $temporario = $arquivo['tmp_name'];
        $destino = "../imagem/".$nome;
        move_uploaded_file($temporario, $destino);
    }

    
    public function listar():array{
        if ($_SESSION['tipo'] == 'admin') {
            $sql = "SELECT noticias.id, noticias.data, noticias.titulo, noticias.destaque, usuarios.nome AS autor FROM noticias LEFT JOIN usuarios ON noticias.usuario_id = usuarios.id ORDER BY data DESC";
        } else {
            $sql = "SELECT id, data, titulo, destaque FROM noticias WHERE usuario_id = :usuario_id ORDER BY data DESC";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            if ($this->usuario->getTipo() !== 'admin') {
                $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
            }
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
        return $resultado;  
        
    }

    public function listarUm():array{
        if ($this->usuario->getTipo() === 'admin') {
            $sql = "SELECT titulo, texto, resumo, imagem, destaque, usuario_id, categoria_id FROM noticias WHERE id = :id"; 
        } else {
            $sql = "SELECT titulo, texto, resumo, imagem, destaque, usuario_id, categoria_id FROM noticias WHERE id = :id AND usuario_id = :usuario_id"; 
        }
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
                if ($this->usuario->getTipo() !== 'admin') {
                    $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
                }
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
        return $resultado;
    }

    public function atualizar():void{
        if ($this->usuario->getTipo() === 'admin') {
            $sql = "UPDATE  noticias SET titulo = :titulo, texto = :texto, resumo = :resumo, imagem = :imagem, destaque = :destaque, categoria_id = :categoria_id WHERE id = :id"; 
        } else {
            $sql = "UPDATE  noticias SET titulo = :titulo, texto = :texto, resumo = :resumo, imagem = :imagem, destaque = :destaque, categoria_id = :categoria_id WHERE id = :id AND usuario_id = :usuario_id"; 
        }
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":titulo", $this->titulo, PDO::PARAM_STR);
            $consulta->bindParam(":texto", $this->texto, PDO::PARAM_STR);
            $consulta->bindParam(":resumo", $this->resumo, PDO::PARAM_STR);
            $consulta->bindParam(":imagem", $this->imagem, PDO::PARAM_STR);
            $consulta->bindParam(":destaque", $this->destaque, PDO::PARAM_STR);
            $consulta->bindParam(":categoria_id", $this->categoria_id, PDO::PARAM_INT);
            $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
                if ($this->usuario->getTipo() !== 'admin') {
                    $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
                }
            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
    }

    public function excluir():void{
        if ($this->usuario->getTipo() === 'admin') {
            $sql = "DELETE FROM noticias WHERE id = :id"; 
        } else {
            $sql = "DELETE FROM  noticias  WHERE id = :id AND usuario_id = :usuario_id"; //named param
        }
            try {
                $consulta = $this->conexao->prepare($sql);
                $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
                if ($this->usuario->getTipo() !== 'admin') {
                    $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
                }
                $consulta->execute();
            } catch (Exception $erro) {
                die("Erro: ".$erro->getMessage());
            }

        
    }

    // Métodos para a área pública

    public function listarDestaques():array{
        
        $sql = "SELECT id, imagem, titulo, resumo  FROM noticias WHERE destaque = :destaque ORDER BY data DESC";
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":destaque", $this->destaque, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
        return $resultado;  
    }
    public function listarTodas():array{
        
        $sql = "SELECT id, data, titulo, resumo  FROM noticias ORDER BY data DESC";
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
        return $resultado;  
    }
    public function listarDetalhes():array{
        $sql = "SELECT noticias.id, noticias.data, noticias.imagem, noticias.titulo, noticias.texto, usuarios.nome AS autor FROM noticias LEFT JOIN usuarios ON noticias.usuario_id = usuarios.id WHERE noticias.id = :id";
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

    public function listarBusca():array{
        $sql = "SELECT noticias.id, noticias.data, noticias.imagem, noticias.titulo, noticias.texto, usuarios.nome AS autor, categorias.nome AS categoria FROM noticias LEFT JOIN usuarios ON noticias.usuario_id = usuarios.id WHERE noticias.id = :id";
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

    public function listarPorCategoria():array{
        $sql= "SELECT noticias.id AS nid, noticias.data, noticias.titulo, noticias.resumo, noticias.usuario_id, noticias.categoria_id, usuarios.nome AS autor, categorias.id, categorias.nome FROM noticias INNER JOIN usuarios ON noticias.usuario_id = usuarios.id INNER JOIN categorias ON noticias.categoria_id = categorias.id WHERE categorias.id = :categoria_id";
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":categoria_id", $this->categoria_id, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
        return $resultado;  
    }
    public function busca():array{
        $sql= "SELECT titulo, data, resumo, id FROM noticias WHERE titulo LIKE :termo OR texto LIKE :termo OR resumo LIKE :termo ORDER BY data DESC";
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":termo", '%'.$this->termo.'%', PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro: ".$erro->getMessage());
        }
        return $resultado;  
    }
    



    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id) 
    {
        $this->id = $id;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }
    public function setData($data) 
    {
        $this->data = $data;

        return $this;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }
    public function setTitulo(string $titulo) 
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getTexto(): string
    {
        return $this->texto;
    }
    public function setTexto(string $texto) 
    {
        $this->texto = $texto;

        return $this;
    }

    public function getResumo(): string
    {
        return $this->resumo;
    }
    public function setResumo(string $resumo) 
    {
        $this->resumo = $resumo;

        return $this;
    }


    public function getImagem(): string
    {
        return $this->imagem;
    }
    public function setImagem(string $imagem)
    {
        $this->imagem = $imagem;

        return $this;
    }


    public function getDestaque(): string
    {
        return $this->destaque;
    }
    public function setDestaque(string $destaque)
    {
        $this->destaque = $destaque;

        return $this;
    }




    public function getCategoriaId(): int
    {
        return $this->categoria_id;
    }
    public function setCategoriaId(int $categoria_id) 
    {
        $this->categoria_id = $categoria_id;

        return $this;
    }


    



 
    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }


    public function getTermo()
    {
        return $this->termo;
    }
    public function setTermo($termo): self
    {
        $this->termo = filter_var($termo, FILTER_SANITIZE_SPECIAL_CHARS);

        return $this;
    }
}