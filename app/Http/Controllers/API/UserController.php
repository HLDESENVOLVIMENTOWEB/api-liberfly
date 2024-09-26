<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Operações de usuário"
 * )
 * @OA\Schema(
 *     schema="NovoUsuario",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="password", type="string")
 * )
 * @OA\Schema(
 *     schema="AtualizarUsuario",
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="password", type="string")
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth"
 * )
 */
class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Obter todos os usuários",
     *     tags={"Users"},
     *     @OA\Response(
     *         response="200", 
     *         description="Lista de usuários",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/NovoUsuario"))
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function index()
    {
        return response()->json($this->userService->getAllUsers(), 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Obter um usuário específico",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Detalhes do usuário",
     *         @OA\JsonContent(ref="#/components/schemas/NovoUsuario")
     *     ),
     *     @OA\Response(response="404", description="Usuário não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function show($id)
    {
        return response()->json($this->userService->getUserById($id), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Criar um novo usuário",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/NovoUsuario")
     *     ),
     *     @OA\Response(
     *         response="201", 
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/NovoUsuario")
     *     ),
     *     @OA\Response(response="400", description="Requisição inválida"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        try {
            $data = $request->all();
            return response()->json($this->userService->createUser($data), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Atualizar um usuário",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/AtualizarUsuario")
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Usuário atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/NovoUsuario")
     *     ),
     *     @OA\Response(response="400", description="Requisição inválida"),
     *     @OA\Response(response="404", description="Usuário não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $id,
            'password' => 'string|min:6',
        ]);

        try {
            $data = $request->all();
            return response()->json($this->userService->updateUser($id, $data), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Excluir um usuário",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Usuário excluído com sucesso"),
     *     @OA\Response(response="404", description="Usuário não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        return response()->json(null, 204);
    }
}