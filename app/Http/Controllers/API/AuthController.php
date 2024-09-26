<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\UserService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\QueryException;

/**
 * @OA\Info(title="API de Autenticação", version="1.0")
 */
class AuthController extends Controller
{
    use ValidatesRequests;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login do usuário",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Login bem-sucedido"),
     *     @OA\Response(response="401", description="Credenciais inválidas")
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        return response()->json(compact('token'));
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registro de usuário",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Usuário registrado com sucesso"),
     *     @OA\Response(response="400", description="Requisição inválida ou e-mail já existe"),
     *     @OA\Response(response="500", description="Falha no registro")
     * )
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        try {
            if ($this->userService->getUserByEmail($request['email'])) {
                throw new \Exception('E-mail já existe');
            }
            $user = $this->userService->createUser($request->all());
            $token = JWTAuth::fromUser($user);
            return response()->json(compact('user', 'token'), 201);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['error' => 'E-mail já existe'], 400);
            }
            return response()->json(['error' => 'Falha no registro'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}