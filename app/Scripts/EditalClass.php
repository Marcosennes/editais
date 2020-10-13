<?php

namespace App\Scripts;

abstract class EditalClass{


    public function formata($palavra)
    {
        $str = $palavra;
        $str = strtolower(utf8_decode($str)); $i=1;
        $str = strtr($str, utf8_decode('àáâãäåæçèéêëìíîïñòóôõöøùúûýýÿ'), 'aaaaaaaceeeeiiiinoooooouuuyyy');
        $str = preg_replace("/([^a-z0-9])/",'-',utf8_encode($str));
        while($i>0) $str = str_replace('--','-',$str,$i);
        if (substr($str, -1) == '-') $str = substr($str, 0, -1);

        return $str;
    }

    public function verificaArquivo($arquivo, $arquivo_nome)
    {
        if($arquivo == null || $arquivo == "")
        {
            return['mensagem'  => "Campo do arquivo não pode ser vazio", 'validacao' => false];
        }

        /* 
        Se quiser limitar o tamanho dos arquivo, basta colocar o tamanho máximo 
        em bytes. Zero é ilimitado
        */
        $limitar_tamanho = 0;

        // Tamanho do arquivo enviado
        $tamanho_arquivo = $arquivo->getSize();

        if($arquivo->extension() != 'pdf')
        {
            return['mensagem'  => "Só é aceito arquivo no formato PDF", 'validacao' => false];
        }

        /* 
        Se a variável $limitar_tamanho tiver valor e o tamanho do arquivo enviado for
        maior do que o tamanho limite, terminado aqui.
        */
        if ( $limitar_tamanho && $limitar_tamanho < $tamanho_arquivo )
        {
        	return['mensagem'  => "Arquivo muito grande", 'validacao' => false];
        }
        
        return['mensagem'  => "Arquivo aceito", 'validacao' => true];
    }

    public function enviaFTP($arquivo, $arquivo_nome)
    {
        // Configura o tempo limite para ilimitado
        set_time_limit(0);

        // IP do Servidor FTP
        $servidor_ftp = '127.0.0.1';

        // Usuário e senha para o servidor FTP
        $usuario_ftp = 'luizotavio';
        $senha_ftp   = 'minhasenha';

        // Caminho da pasta FTP
        $caminho = 'arquivos/';

        // O destino para qual o arquivo será enviado
        $destino = $caminho . $arquivo_nome;

        /*
         *  $arquivo_temp = arquivo['name'];
         *  ou
         *  $arquivo_temp = arquivo->name;
         */
        
        // Realiza a conexão
        $conexao_ftp = ftp_connect( $servidor_ftp );

        // Tenta fazer login
        $login_ftp = @ftp_login( $conexao_ftp, $usuario_ftp, $senha_ftp );

        // Se não conseguir fazer login, termina aqui
        if ( ! $login_ftp )
        {
        	exit('Usuário ou senha FTP incorretos.');
        }

        // Envia o arquivo
        if ( @ftp_put( $conexao_ftp, $destino, $arquivo, FTP_BINARY ) )    //Não sei exatamente o que deve ser enviado no lugar de arquivo_temp
        {
        	// Se for enviado, mostra essa mensagem
        	echo 'Arquivo enviado com sucesso!';
        } else
        {
        	// Se não for enviado, mostra essa mensagem
        	echo 'Erro ao enviar arquivo!';
        }

        // Fecha a conexão FTP
        ftp_close( $conexao_ftp );
    }
    
}