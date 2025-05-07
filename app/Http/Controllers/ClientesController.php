<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     title="Simple API - CRUD de Clientes",
 *     version="1.0.0"
 * )
 */


class ClientesController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/clientes",
     *     summary="Obtener todos los clientes",
     *     description="Devuelve una lista de todos los clientes registrados en el sistema.",
     *     operationId="obtenerClientes",
     *     tags={"Clientes"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de clientes",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Clientes encontrados"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Juan"),
     *                     @OA\Property(property="apellido", type="string", example="Pérez"),
     *                     @OA\Property(property="email", type="string", example="juan.perez@email.com"),
     *                     @OA\Property(property="telefono", type="integer", example=1122334455),
     *                     @OA\Property(property="dni", type="integer", example=40123456),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-07T14:48:00.000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-07T15:00:00.000Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function clientes()
    {
        $clientes = Cliente::all();

        if ($clientes->isEmpty()) {
            return response()->json(['status' => true, 'message' => 'No se encontraron clientes'], 200);
        }

        return response()->json(['status' => true, 'message' => 'Clientes encontrados', 'data' => $clientes], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/cliente",
     *     summary="Registrar un nuevo cliente",
     *     description="Este endpoint permite crear un nuevo cliente en el sistema. Se validan campos únicos como email, teléfono y DNI. Si alguno de estos ya existe, se devolverá un error de validación.",
     *     operationId="crearCliente",
     *     tags={"Clientes"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del cliente a registrar",
     *         @OA\JsonContent(
     *             required={"nombre", "apellido", "email", "telefono", "dni"},
     *             @OA\Property(property="nombre", type="string", example="Juan"),
     *             @OA\Property(property="apellido", type="string", example="Pérez"),
     *             @OA\Property(property="email", type="string", format="email", example="juan.perez@email.com"),
     *             @OA\Property(property="telefono", type="integer", example=123456789),
     *             @OA\Property(property="dni", type="integer", example=40123456)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cliente creado con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cliente creado con éxito."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Juan"),
     *                 @OA\Property(property="apellido", type="string", example="Pérez"),
     *                 @OA\Property(property="email", type="string", example="juan.perez@email.com"),
     *                 @OA\Property(property="telefono", type="integer", example=123456789),
     *                 @OA\Property(property="dni", type="integer", example=40123456),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-07T14:48:00.000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-07T14:48:00.000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="El email ya ha sido registrado"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No se pudo crear el cliente. Vuelva a intentar mas tarde.")
     *         )
     *     )
     * )
     */


    public function crearCliente(Request $request)
    {
        $validacion = Validator($request->all(), [
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email|unique:clientes_tables',
            'telefono' => 'required|integer|unique:clientes_tables',
            'dni' => 'required|integer|unique:clientes_tables',
        ]);

        if ($validacion->fails()) {
            return response()->json(['status' => false, 'message' => $validacion->errors()], 200);
        }

        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'dni' => $request->dni
        ]);

        if (!$cliente) {
            return response()->json(
                [
                    'status' => false,
                    'message' => "No se pudo crear el cliente. Vuelva a intentar mas tarde."
                ],
                500
            );
        }

        return response()->json(['status' => false, 'message' => 'Cliente creado con éxito.', 'data' => $cliente], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/cliente",
     *     summary="Obtener un cliente por DNI",
     *     description="Este endpoint permite buscar un cliente existente a través de su número de DNI. Es obligatorio enviar el parámetro 'dni' como query param.",
     *     operationId="obtenerClientePorDni",
     *     tags={"Clientes"},
     *     @OA\Parameter(
     *         name="dni",
     *         in="query",
     *         required=true,
     *         description="DNI del cliente a buscar",
     *         @OA\Schema(type="integer", example=40123456)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cliente encontrado"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Juan"),
     *                 @OA\Property(property="apellido", type="string", example="Pérez"),
     *                 @OA\Property(property="email", type="string", example="juan.perez@email.com"),
     *                 @OA\Property(property="telefono", type="integer", example=123456789),
     *                 @OA\Property(property="dni", type="integer", example=40123456),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-07T14:48:00.000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-07T14:48:00.000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación: DNI faltante o inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Debe ingresar el dni para buscar un cliente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cliente no encontrado")
     *         )
     *     )
     * )
     */


    public function obtenerCliente(Request $request)
    {

        // Validar que el campo 'dni' esté presente
        $validacion = Validator($request->all(), [
            'dni' => 'required|integer',
        ]);

        if ($validacion->fails()) {
            return response()->json(['status' => false, 'message' => 'Debe ingresar el dni para buscar un cliente'], 400);
        }

        //$cliente = Cliente::find($request->dni);
        $cliente = Cliente::where('dni', $request->dni)->first();

        if (!$cliente) {
            return response()->json(['status' => false, 'message' => 'Cliente no encontrado'], 200);
        }
        return response()->json(['status' => true, 'message' => 'Cliente encontrado', 'data' => $cliente], 200);
    }
    /**
     * @OA\Put(
     *     path="/api/cliente",
     *     summary="Actualizar los datos de un cliente",
     *     description="Este endpoint permite actualizar los datos de un cliente identificado por su DNI actual. Se puede actualizar nombre, apellido, email, teléfono y cambiar el DNI.",
     *     operationId="actualizarCliente",
     *     tags={"Clientes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"dni"},
     *             @OA\Property(property="dni", type="integer", example=40123456, description="DNI actual del cliente"),
     *             @OA\Property(property="new_dni", type="integer", example=40987654, description="Nuevo DNI (si desea modificarlo)"),
     *             @OA\Property(property="nombre", type="string", example="Juan"),
     *             @OA\Property(property="apellido", type="string", example="Pérez"),
     *             @OA\Property(property="email", type="string", format="email", example="juan.perez@email.com"),
     *             @OA\Property(property="telefono", type="integer", example=1122334455)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente actualizado con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cliente actualizado con éxito"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Juan"),
     *                 @OA\Property(property="apellido", type="string", example="Pérez"),
     *                 @OA\Property(property="email", type="string", example="juan.perez@email.com"),
     *                 @OA\Property(property="telefono", type="integer", example=1122334455),
     *                 @OA\Property(property="dni", type="integer", example=40987654),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-07T14:48:00.000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-07T15:00:00.000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación en los datos enviados",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Debe ingresar el dni para buscar un cliente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cliente no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el cliente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No se pudo actualizar el cliente. Intente nuevamente.")
     *         )
     *     )
     * )
     */


    public function actualizarCliente(Request $request)
    {
        // Validar que el campo 'dni' esté presente
        $validacion = Validator($request->all(), [
            'dni' => 'required|integer',
            'new_dni' => 'integer|unique:clientes_tables',
            'nombre' => 'max:255',
            'apellido' => 'max:255',
            'email' => 'email|unique:clientes_tables',
            'telefono' => 'integer|unique:clientes_tables',
        ]);

        if ($validacion->fails()) {
            return response()->json(['status' => false, 'message' => 'Debe ingresar el dni para buscar un cliente'], 400);
        }



        // Buscar el cliente por el campo 'dni'
        $cliente = Cliente::where('dni', $request->dni)->first();


        if (!$cliente) {
            return response()->json(['status' => false, 'message' => 'Cliente no encontrado'], 200);
        }

        // Actualizar los campos del cliente
        if ($request->has('nombre')) {
            $cliente->nombre = $request->nombre;
        }
        if ($request->has('apellido')) {
            $cliente->apellido = $request->apellido;
        }
        if ($request->has('email')) {
            $cliente->email = $request->email;
        }
        if ($request->has('telefono')) {
            $cliente->telefono = $request->telefono;
        }
        if ($request->has('new_dni')) {
            $cliente->dni = $request->new_dni;
        }



        // Guardar los cambios
        if ($cliente->save()) {
            return response()->json(['status' => true, 'message' => 'Cliente actualizado con éxito', 'data' => $cliente], 200);
        }
        echo "PASA";
        exit;

        // Si no se pudo actualizar
        return response()->json(['status' => false, 'message' => 'No se pudo actualizar el cliente. Intente nuevamente.'], 500);
    }
    /**
     * @OA\Delete(
     *     path="/api/cliente",
     *     summary="Eliminar un cliente por DNI",
     *     description="Elimina un cliente existente utilizando su DNI como identificador.",
     *     operationId="eliminarCliente",
     *     tags={"Clientes"},
     *     @OA\Parameter(
     *         name="dni",
     *         in="query",
     *         required=true,
     *         description="DNI del cliente a eliminar",
     *         @OA\Schema(type="integer", example=40123456)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente eliminado con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cliente eliminado con éxito"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Juan"),
     *                 @OA\Property(property="apellido", type="string", example="Pérez"),
     *                 @OA\Property(property="email", type="string", example="juan.perez@email.com"),
     *                 @OA\Property(property="telefono", type="integer", example=1122334455),
     *                 @OA\Property(property="dni", type="integer", example=40123456),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-07T14:48:00.000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-07T15:00:00.000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación en los datos enviados",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Debe ingresar el dni para buscar un cliente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cliente no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el cliente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No se pudo eliminar el cliente. Intente nuevamente.")
     *         )
     *     )
     * )
     */


    public function eliminarClientes(Request $request)
    {
        $validacion = Validator($request->all(), [
            'dni' => 'required|integer',
        ]);

        if ($validacion->fails()) {
            return response()->json(['status' => false, 'message' => 'Debe ingresar el dni para buscar un cliente'], 400);
        }

        // Buscar el cliente por el campo 'dni'
        $cliente = Cliente::where('dni', $request->dni)->first();

        if (!$cliente) {
            return response()->json(['status' => false, 'message' => 'Cliente no encontrado'], 404);
        }

        // Intentar eliminar el cliente
        if ($cliente->delete()) {
            return response()->json(['status' => true, 'message' => 'Cliente eliminado con éxito', 'data' => $cliente], 200);
        }

        // Si no se pudo eliminar
        return response()->json(['status' => false, 'message' => 'No se pudo eliminar el cliente. Intente nuevamente.'], 500);
    }
}
