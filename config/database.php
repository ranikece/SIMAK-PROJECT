<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'simak_terpadu');
define('DB_USER', 'root');
define('DB_PASS', '');
define('APP_NAME', 'SIMAK');
function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function rupiah($value): string
{
    return 'Rp' . number_format((float)$value, 0, ',', '.');
}
function queryAll(string $sql, array $params = []): array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
function queryOne(string $sql, array $params = []): ?array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row ?: null;
}
function scalar(string $sql, array $params = [])
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}
function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = [
    'type' => $type,
    'message' => $message,
    ];
}
function takeFlash(): array
{
    $items = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $items;
}
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}
function isLoggedIn(): bool
{
    return isset($_SESSION['user']) && is_array($_SESSION['user']);
}
function currentRole(): ?string
{
    return $_SESSION['user']['role'] ?? null;
}
function roleLabel(?string $role): string
{
    return match ($role) {
        'admin' => 'Admin',
        'resepsionis' => 'Resepsionis',
        'user' => 'Pasien',
        default => 'Guest',
    }
    ;
}
function redirectPage(string $page = 'dashboard'): void
{
    header('Location: index.php?page=' . urlencode($page));
    exit;
}
function redirectAction(string $page = 'dashboard'): void
{
    header('Location: ../index.php?page=' . urlencode($page));
    exit;
}
function redirectTo(string $role, string $page): void
{
    redirectAction($page);
}
function requireLogin(): void
{
    if (!isLoggedIn()) {
        flash('error', 'Silakan login terlebih dahulu.');
        header('Location: ../index.php?page=login');
        exit;
    }
}
function requireRole(array|string $roles): void
{
    requireLogin();
    $allowed = is_array($roles) ? $roles : [$roles];
    if (!in_array(currentRole(), $allowed, true)) {
        flash('error', 'Akses ditolak. Akun kamu tidak punya izin untuk membuka fitur tersebut.');
        redirectAction('dashboard');
    }
}
function normalizeText($value): string
{
    $value = strtolower(trim((string)$value));
    return preg_replace('/\s+/', ' ', $value);
}
function normalizePhone($value): string
{
    return preg_replace('/\D+/', '', (string)$value);
}
function passwordMatches(string $plainPassword, string $storedPassword): bool
{
    if (password_get_info($storedPassword)['algo'] !== 0) {
        return password_verify($plainPassword, $storedPassword);
    }
    return hash_equals($storedPassword, $plainPassword);
}
function runTransactionWithDeadlockRetry(callable $callback, int $maxRetry = 3)
{
    $attempt = 0;
    beginning:
    $attempt++;
    $pdo = db();
    try {
        $pdo->beginTransaction();
        $result = $callback($pdo, $attempt);
        $pdo->commit();
        return $result;
    }
catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $mysqlCode = $e->errorInfo[1] ?? null;
    $sqlState = $e->errorInfo[0] ?? null;
    $isDeadlock = ($mysqlCode == 1213 || $mysqlCode == 1205 || $sqlState === '40001');
    if ($isDeadlock && $attempt < $maxRetry) {
        usleep(200000 * $attempt);
        goto beginning;
    }
    throw $e;
}
catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    throw $e;
}
}
