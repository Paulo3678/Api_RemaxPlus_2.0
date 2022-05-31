<?php

namespace App\Factory;

use Illuminate\Support\Facades\DB;

class ImagemFactory
{

    public function getImagem(int $idNovaImagemCapa, int $usuarioId)
    {
        try {
            $resultado = DB::select("SELECT * FROM Imagem WHERE Id_Imagem={$idNovaImagemCapa} AND Usuario_Id={$usuarioId}");
            return $resultado;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getImovelImagem(int $idNovaImagemCapa, int $usuarioId, int $idImovel)
    {
        try {
            $resultado = DB::select("SELECT * FROM Imagem WHERE Id_Imagem={$idNovaImagemCapa} AND Usuario_Id={$usuarioId} AND Id_Imovel={$idImovel};");
            return $resultado;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getImovelImagens(int $usuarioId, int $idImovel)
    {
        try {
            $resultado = DB::select("SELECT * FROM Imagem WHERE Usuario_Id={$usuarioId} AND Id_Imovel={$idImovel};");
            return $resultado;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function updateImagemCapa(int $idImovel, string $caminhoNovaImagem)
    {
        try {
            $resultado = DB::update("UPDATE Imovel SET Imagem_Capa='{$caminhoNovaImagem}' WHERE Imovel.Id={$idImovel}");
            return $resultado;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function addImagens(array $imagens)
    {
        try {
            DB::beginTransaction();
            foreach ($imagens as $imagem) {
                $resultado = DB::insert("INSERT INTO Imagem (Id_Imovel, Usuario_Id, Caminho_Imagem)
                Values ({$imagem['Id_Imovel']}, {$imagem['Usuario_Id']}, '{$imagem['Caminho_Imagem']}');
            ");
            }

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function removeImagens(int $idImovel, int $idImagem, int $idUsuario)
    {
        try {
            DB::delete("DELETE FROM Imagem WHERE Id_Imagem={$idImagem} AND Id_Imovel={$idImovel} AND Usuario_Id={$idUsuario};");
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

}
