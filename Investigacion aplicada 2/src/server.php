<?php
require __DIR__ . '/../vendor/autoload.php';

use React\Http\Message\Response;
use React\Http\HttpServer;
use React\Socket\SocketServer;
use Psr\Http\Message\ServerRequestInterface;

// Configuración de la base de datos
$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'empresa_x',
    'user' => 'root',
    'pass' => '' 
];

$server = new HttpServer(function (ServerRequestInterface $request) use ($dbConfig) {
    $path = $request->getUri()->getPath();
    $method = $request->getMethod();
    
    // Manejo de rutas
    switch ($path) {
        case '/':
            return new Response(
                200,
                ['Content-Type' => 'text/html'],
                file_get_contents(__DIR__ . '/../public/index.html')
            );
            
            case '/contact':
                if ($method === 'POST') {
                    $data = json_decode($request->getBody()->getContents(), true);
                    
                    $connection = new PDO(
                        "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']}",
                        $dbConfig['user'],
                        $dbConfig['pass']
                    );
                    
                    try {
                        $stmt = $connection->prepare(
                            "INSERT INTO contactos (nombre, email, mensaje) VALUES (?, ?, ?)"
                        );
                        $stmt->execute([$data['nombre'], $data['email'], $data['mensaje']]);
                        $id = $connection->lastInsertId();
                        
                        return new Response(
                            201,
                            ['Content-Type' => 'application/json'],
                            json_encode([
                                'success' => true,
                                'id' => $id,
                                'message' => 'Contacto guardado exitosamente'
                            ])
                        );
                    } catch (PDOException $e) {
                        return new Response(
                            500,
                            ['Content-Type' => 'application/json'],
                            json_encode([
                                'success' => false,
                                'error' => 'Error al guardar el contacto: ' . $e->getMessage()
                            ])
                        );
                    }
                }
                
                return new Response(
                    200,
                    ['Content-Type' => 'text/html'],
                    file_get_contents(__DIR__ . '/../public/contact.html')
                );

                case '/crud':
                    return new Response(
                        200,
                        ['Content-Type' => 'text/html'],
                        file_get_contents(__DIR__ . '/../public/crud.html')
                    );
                
                case '/data':
                    if ($method === 'GET') {
                        $acceptHeader = $request->getHeaderLine('Accept');
                
                        if (strpos($acceptHeader, 'text/html') !== false) {
                            // Si el navegador espera HTML, devolvemos el CRUD
                            return new Response(
                                200,
                                ['Content-Type' => 'text/html'],
                                file_get_contents(__DIR__ . '/../public/crud.html')
                            );
                        }
                
                        // Si es una petición tipo fetch, devolver JSON
                        $connection = new PDO(
                            "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']}",
                            $dbConfig['user'],
                            $dbConfig['pass']
                        );
                
                        $stmt = $connection->query("SELECT * FROM contactos");
                        $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                        return new Response(
                            200,
                            ['Content-Type' => 'application/json'],
                            json_encode($contacts)
                        );
                    }
                
            
            // Operaciones CRUD adicionales (simplificadas)
            if ($method === 'POST') {
                $data = $request->getParsedBody();
                $action = $data['action'] ?? '';
                
                $connection = new PDO(
                    "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']}",
                    $dbConfig['user'],
                    $dbConfig['pass']
                );
                
                switch ($action) {
                    case 'create':
                        $stmt = $connection->prepare(
                            "INSERT INTO contactos (nombre, email, mensaje) VALUES (?, ?, ?)"
                        );
                        $stmt->execute([$data['nombre'], $data['email'], $data['mensaje']]);
                        return new Response(200, ['Content-Type' => 'application/json'], 
                            json_encode(['success' => true]));
                        
                    case 'update':
                        $stmt = $connection->prepare(
                            "UPDATE contactos SET nombre = ?, email = ?, mensaje = ? WHERE id = ?"
                        );
                        $stmt->execute([$data['nombre'], $data['email'], $data['mensaje'], $data['id']]);
                        return new Response(200, ['Content-Type' => 'application/json'], 
                            json_encode(['success' => true]));
                        
                    case 'delete':
                        $stmt = $connection->prepare("DELETE FROM contactos WHERE id = ?");
                        $stmt->execute([$data['id']]);
                        return new Response(200, ['Content-Type' => 'application/json'], 
                            json_encode(['success' => true]));
                }
            }
            
            return new Response(400, ['Content-Type' => 'application/json'], 
                json_encode(['error' => 'Acción no válida']));
            
        case '/style.css':
            return new Response(
                200,
                ['Content-Type' => 'text/css'],
                file_get_contents(__DIR__ . '/../public/style.css')
            );
            
        default:
            return new Response(
                404,
                ['Content-Type' => 'text/html'],
                '<h1>404 Not Found</h1>'
            );
    }
});

$socket = new SocketServer('0.0.0.0:8080');
$server->listen($socket);

echo "Server running at http://0.0.0.0:8080\n";