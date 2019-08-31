<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
class CAuthentication {
    public function __invoke($request, $response, $next) {
        if ($this->validateUserKey("user", "pass") === false) {
            return $response->withStatus(401);
        }
        return $next($request, $response);
    }
    function validateUserKey($uid, $key) {
        if(file_exists('admin')){
            return true;
        } else {
            return false;
        }
    }
}

// デバイス一覧表示
$app->get('/devices', function (Request $request, Response $response) {
    try {
        $pdo = new PDO('sqlite:manage_device.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
    $sql = 'SELECT * FROM mdt';
    $stmt = $pdo->query($sql);
    $devices = [];
    while($row = $stmt->fetch()) {
        $devices[] = $row;
    }
    $data = ['devices' => $devices];
    if(file_exists('admin')){
        return $this->renderer->render($response, 'tasks/admin.phtml', $data);
    } else {
        return $this->renderer->render($response, 'tasks/index.phtml', $data);
    }
});

// デバイス新規登録用フォームの表示
$app->get('/devices/create', function (Request $request, Response $response) {
    return $this->renderer->render($response, 'tasks/create.phtml');
})->add(new CAuthentication());

// 新規登録
$app->post('/devices', function (Request $request, Response $response) {
    $device_name = $request->getParsedBodyParam('device_name');
    try {
        $pdo = new PDO('sqlite:manage_device.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
    $sql = 'INSERT INTO mdt(device_name,device_status,user_name,last_modify_date) values (:device_name,"","-","")';
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([':device_name' => $device_name]);
    if (!$result) {
        throw new \Exception('could not save the ticket');
    }
    return $response->withRedirect("/devices");
});

// 貸出返却ログ表示
$app->get('/devices/{id}', function (Request $request, Response $response, array $args) {
    try {
        $pdo = new PDO('sqlite:manage_device.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
    $sql = 'SELECT device_name FROM mdt WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $args['id']]);
    $device = $stmt->fetch();
    if (!$device) {
        return $response->withStatus(404)->write('not found');
    }
    $path= './log/' . $device["device_name"] . '.csv';
    if (file_exists($path)) {
        $fp = fopen($path, 'r');
        $data = array();
        $i=0;
        while (($line = fgetcsv($fp)) !== FALSE) {
            $data[$i]['when']=$line[0];
            $data[$i]['who']=$line[1];
            $data[$i]['what']=$line[2];
            ++$i;
        }
        fclose($fp);
    } else {
        return $response->withStatus(404)->write('not found');
    }
    return $this->renderer->render($response, 'tasks/show.phtml', $data);
});

// 編集用フォームの表示
$app->get('/devices/{id}/edit', function (Request $request, Response $response, array $args) {
    try {
        $pdo = new PDO('sqlite:manage_device.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
    $sql = 'SELECT * FROM mdt WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $args['id']]);
    $device = $stmt->fetch();
    if (!$device) {
        return $response->withStatus(404)->write('not found');
    }
    $data = ['device' => $device];
    return $this->renderer->render($response, 'tasks/edit.phtml', $data);
})->add(new CAuthentication());

// デバイス名更新
$app->put('/devices/{id}', function (Request $request, Response $response, array $args) {
    $device_name = $request->getParsedBodyParam('device_name');
    try {
        $pdo = new PDO('sqlite:manage_device.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
    $sql = 'UPDATE mdt SET device_name = :device_name WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([ ':device_name' => $device_name, ':id' => $args['id'] ]);
    return $response->withRedirect("/devices");
});

// デバイス削除フォーム
$app->get('/devices/{id}/delete', function (Request $request, Response $response, array $args) {
    try {
        $pdo = new PDO('sqlite:manage_device.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
    $sql = 'SELECT * FROM mdt WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $args['id']]);
    $device = $stmt->fetch();
    if (!$device) {
        return $response->withStatus(404)->write('not found');
    }
    $data = ['device' => $device];
    return $this->renderer->render($response, 'tasks/delete.phtml', $data);
})->add(new CAuthentication());


// デバイス削除
$app->delete('/devices/{id}', function (Request $request, Response $response, array $args) {
    try {
        $pdo = new PDO('sqlite:manage_device.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
    $sql = 'SELECT * FROM mdt WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $args['id']]);
    $device = $stmt->fetch();
    if (!$device) {
        return $response->withStatus(404)->write('not found');
    }
    $stmt = $pdo->prepare('DELETE FROM mdt WHERE id = :id');
    $stmt->execute(['id' => $device['id']]);
    return $response->withRedirect("/devices");
});

// ユーザー一覧
$app->get('/users', function (Request $request, Response $response) {
    try {
        $pdo = new PDO('sqlite:user.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
    $sql = 'SELECT * FROM ut';
    $stmt = $pdo->query($sql);
    $users = [];
    while($row = $stmt->fetch()) {
        $users[] = $row;
    }
    $data = ['users' => $users];
    return $this->renderer->render($response, 'tasks/users.phtml', $data);
})->add(new CAuthentication());

// ユーザー新規作成用フォームの表示
$app->get('/users/create', function (Request $request, Response $response) {
    return $this->renderer->render($response, 'tasks/user_create.phtml');
})->add(new CAuthentication());

// ユーザー新規作成
$app->post('/users', function (Request $request, Response $response) {
    $user_id = $request->getParsedBodyParam('user_id');
    $name = $request->getParsedBodyParam('name');
    try {
        $pdo = new PDO('sqlite:user.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
    $sql = 'INSERT INTO ut(user_id, name) values (:user_id,:name)';
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([ ':user_id' => $user_id, ':name' => $name ]);
    if (!$result) {
        throw new \Exception('could not save the ticket');
    }
    return $response->withRedirect("/users");
});

// ユーザー削除
$app->delete('/users/{id}', function (Request $request, Response $response, array $args) {
    try {
        $pdo = new PDO('sqlite:user.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
    $sql = 'SELECT * FROM ut WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $args['id']]);
    $user = $stmt->fetch();
    if (!$user) {
        return $response->withStatus(404)->write('not found');
    }
    $stmt = $pdo->prepare('DELETE FROM ut WHERE id = :id');
    $stmt->execute(['id' => $user['id']]);
    return $response->withRedirect("/users");
});

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
