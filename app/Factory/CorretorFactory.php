<?php

namespace App\Factory;

use App\Models\CorretorModel;
use Illuminate\Support\Facades\DB;

class CorretorFactory
{
    public function createCorretor(CorretorModel $corretor)
    {
        $usuarioInserido = DB::insert("INSERT INTO Corretor (Id_Usuario, Nome_Corretor, Email_Corretor, Creci, Whatsapp, Foto_Corretor)
            VALUES ('{$corretor->getIdUsuario()}', '{$corretor->getNome()}', '{$corretor->getEmail()}', '{$corretor->getCreci()}', '{$corretor->getWhatsapp()}', '{$corretor->getFoto()}');
        ");

        return $usuarioInserido;
    }

    public function getCorretores(int $usuarioId)
    {
        $corretores = DB::select("SELECT * FROM Corretor WHERE Id_Usuario={$usuarioId};");
        return $corretores;
    }

    public function getCorretor(int $corretorId)
    {
        $dadosCorretor = DB::select("SELECT * FROM Corretor WHERE Id={$corretorId};");
        $corretor = new CorretorModel();

        if(!$dadosCorretor){
            return false;
        }

        $corretor->setNome($dadosCorretor[0]->Nome_Corretor)
            ->setIdUsuario($dadosCorretor[0]->Id_Usuario)
            ->setId($dadosCorretor[0]->Id)
            ->setEmail($dadosCorretor[0]->Email_Corretor)
            ->setCreci($dadosCorretor[0]->Creci)
            ->setWhatsapp($dadosCorretor[0]->Whatsapp);
        return $corretor;
    }

    public function removeCorretor(int $corretorId)
    {
        $corretorRemovido = DB::delete("DELETE FROM Corretor WHERE Id={$corretorId}");
        return $corretorRemovido;
    }

    public function updateCorretor(CorretorModel $corretorAtualizado)
    {
        $resultadoAtualizacao = DB::update("UPDATE Corretor 
        SET Nome_Corretor='{$corretorAtualizado->getNome()}', Email_Corretor='{$corretorAtualizado->getEmail()}', 
        Creci='{$corretorAtualizado->getCreci()}', Whatsapp='{$corretorAtualizado->getWhatsapp()}', Foto_Corretor='{$corretorAtualizado->getFoto()}';");

        return $resultadoAtualizacao;
    }


}
