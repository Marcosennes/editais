<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\userCreateRequest;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Illuminate\Support\Facades\Auth;

use Exception;

class UsersController extends Controller
{
    private $repository;
    private $validator;

    public function __construct(UserRepository $repository, UserValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    public function login(){
        return view('login.login');
    }

    public function registrar(UserCreateRequest $request)
    {
        session_start();

        $aux_data = 
        [
            'cpf'       => $request->get('cpf'),
            'name'      => $request->get('name'),
            'email'     => $request->get('email'),
            'password'  => password_hash($request->get('password'), PASSWORD_DEFAULT),
            'permission' => $request->get('permission'),
        ];
        if(empty($aux_data['cpf'])){
            
            $_SESSION['registrar_usuario']['validacao']  = false;
            $_SESSION['registrar_usuario']['mensagem']   = "CPF não pode ser vazio";
            
            return redirect()->route('edital.cadastrar');   
        }
        if(empty($aux_data['name'])){
            
            $_SESSION['registrar_usuario']['validacao']  = false;
            $_SESSION['registrar_usuario']['mensagem']   = "Nome não pode ser vazio";
            
            return redirect()->route('edital.cadastrar');   
        }
        if(empty($aux_data['email'])){
            
            $_SESSION['registrar_usuario']['validacao']  = false;
            $_SESSION['registrar_usuario']['mensagem']   = "Email não pode ser vazio";
            
            return redirect()->route('edital.cadastrar');   
        }
        if(empty($aux_data['password'])){
            
            $_SESSION['registrar_usuario']['validacao']  = false;
            $_SESSION['registrar_usuario']['mensagem']   = "Senha não pode ser vazia";
            
            return redirect()->route('edital.cadastrar');   
        }

        // Dessa forma se a busca retornar sem nenhum usuário com o mesmo email, o resultado não é null
        // 
        // $email_verification = DB::table('users')
        //       ->where('email', '=', $data['email'])
        //       ->select('id')
        //       ->get();
        $email_verification = $this->repository->findWhere(['email' => $request->get('email')])->first();
        $cpf_verification   = $this->repository->findWhere(['cpf'   => $request->get('cpf')])->first();

        try
        {
            if($email_verification)
            {
                $_SESSION['registrar_usuario']['validacao']  = false;
                $_SESSION['registrar_usuario']['mensagem']   = "Email já cadastrado no sistema";
                return redirect()->route('edital.cadastrar');
            }
            else if($cpf_verification)
            {
                $_SESSION['registrar_usuario']['validacao']  = false;
                $_SESSION['registrar_usuario']['mensagem']   = "CPF já cadastrado no sistema";
                return redirect()->route('edital.cadastrar');
            }
            else
            {
                $this->validator->with($aux_data)->passesOrFail(ValidatorInterface::RULE_CREATE);
                $usuario = $this->repository->create($aux_data);
    
                $_SESSION['registrar_usuario']['validacao']  = true;
                $_SESSION['registrar_usuario']['mensagem']   = "Usuário cadastrado";
                $user = $this->repository->findWhere(['email' => $request->get('email')])->first();
                
                return redirect()->route('edital.cadastrar');
            }
        }
        catch(Exception $e)
        {
            switch(get_class($e))
            {    
                case QueryException::class     : return ['success' => false, 'messages' => $e->getMessage()];
                case Exception::class          : return ['success' => false, 'messages' => $e->getMessage()];
                default                        : return ['success' => false, 'messages' => get_class($e)];
            }
        }        // $usuario = $request['success'] ? $request['data'] : null; 
    }
    
    public function autenticar(Request $request)
    {
        session_start();
        
        $data= [
            'email'     => $request->get('email'),
            'password'  => $request->get('password'),
        ];

        try
        {
            $user = $this->repository->findWhere(['email' => $request->get('email')])->first();
                
            if(!$user)
            {
                $_SESSION['usuario_nao_encontrado']['mensagem']   = "Usuário não existe no sistema";
                return redirect()->route('login.loginPage');
            }
            else{
                if(password_verify($request->get('password'), $user->password))
                {
                    Auth::login($user);
                    return redirect()->route('edital.cadastrar');
                }
                else
                {
                    $_SESSION['senha_errada']['mensagem']   = "A senha não corresponde ao e-mail cadastrado no sistema";

                    return redirect()->route('login.loginPage');
                }
            }

            return redirect()->route('edital.cadastrar');
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
}