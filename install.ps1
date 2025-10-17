# Script de Instalação Rápida - CiberTech E-commerce
# Execute no PowerShell como Administrador

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  CiberTech E-commerce - Instalação Rápida" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Verificar se PHP está instalado
Write-Host "Verificando PHP..." -ForegroundColor Yellow
try {
    $phpVersion = php -v 2>$null
    if ($phpVersion) {
        Write-Host "✓ PHP encontrado!" -ForegroundColor Green
        Write-Host $phpVersion[0] -ForegroundColor Gray
    }
} catch {
    Write-Host "✗ PHP não encontrado!" -ForegroundColor Red
    Write-Host "Instale PHP em: https://windows.php.net/download/" -ForegroundColor Yellow
    exit 1
}

# Verificar se MySQL está rodando
Write-Host "`nVerificando MySQL..." -ForegroundColor Yellow
$mysqlService = Get-Service -Name MySQL* -ErrorAction SilentlyContinue
if ($mysqlService -and $mysqlService.Status -eq 'Running') {
    Write-Host "✓ MySQL está rodando!" -ForegroundColor Green
} else {
    Write-Host "⚠ MySQL não está rodando!" -ForegroundColor Yellow
    Write-Host "Inicie o MySQL antes de continuar." -ForegroundColor Yellow
    $continue = Read-Host "Deseja continuar mesmo assim? (s/N)"
    if ($continue -ne 's' -and $continue -ne 'S') {
        exit 1
    }
}

# Configuração do banco de dados
Write-Host "`n================================================" -ForegroundColor Cyan
Write-Host "  Configuração do Banco de Dados" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

$dbHost = Read-Host "Host do MySQL (padrão: 127.0.0.1)"
if ([string]::IsNullOrWhiteSpace($dbHost)) { $dbHost = "127.0.0.1" }

$dbPort = Read-Host "Porta do MySQL (padrão: 3306)"
if ([string]::IsNullOrWhiteSpace($dbPort)) { $dbPort = "3306" }

$dbName = Read-Host "Nome do banco (padrão: loja_hardware)"
if ([string]::IsNullOrWhiteSpace($dbName)) { $dbName = "loja_hardware" }

$dbUser = Read-Host "Usuário do MySQL (padrão: root)"
if ([string]::IsNullOrWhiteSpace($dbUser)) { $dbUser = "root" }

$dbPass = Read-Host "Senha do MySQL (deixe vazio se não tiver)"

# Configurar variáveis de ambiente
$env:DB_HOST = $dbHost
$env:DB_PORT = $dbPort
$env:DB_NAME = $dbName
$env:DB_USER = $dbUser
$env:DB_PASS = $dbPass

Write-Host "`n✓ Variáveis de ambiente configuradas!" -ForegroundColor Green

# Criar banco de dados
Write-Host "`n================================================" -ForegroundColor Cyan
Write-Host "  Criando Banco de Dados" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

$createDb = Read-Host "Deseja criar/recriar o banco de dados? (s/N)"
if ($createDb -eq 's' -or $createDb -eq 'S') {
    Write-Host "Executando banco1.sql..." -ForegroundColor Yellow
    
    if ($dbPass) {
        mysql -h $dbHost -P $dbPort -u $dbUser -p"$dbPass" < banco1.sql 2>&1
    } else {
        mysql -h $dbHost -P $dbPort -u $dbUser < banco1.sql 2>&1
    }
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✓ Schema criado com sucesso!" -ForegroundColor Green
    } else {
        Write-Host "✗ Erro ao criar schema!" -ForegroundColor Red
        Write-Host "Execute manualmente: mysql -u $dbUser -p < banco1.sql" -ForegroundColor Yellow
    }
    
    $populateDb = Read-Host "`nDeseja popular com dados de exemplo? (s/N)"
    if ($populateDb -eq 's' -or $populateDb -eq 'S') {
        Write-Host "Executando populate_db.sql..." -ForegroundColor Yellow
        
        if ($dbPass) {
            mysql -h $dbHost -P $dbPort -u $dbUser -p"$dbPass" $dbName < populate_db.sql 2>&1
        } else {
            mysql -h $dbHost -P $dbPort -u $dbUser $dbName < populate_db.sql 2>&1
        }
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✓ Dados de exemplo inseridos!" -ForegroundColor Green
            Write-Host "`nUsuário de teste criado:" -ForegroundColor Cyan
            Write-Host "  Email: joao@teste.com" -ForegroundColor White
            Write-Host "  Senha: teste123" -ForegroundColor White
        } else {
            Write-Host "✗ Erro ao inserir dados!" -ForegroundColor Red
        }
    }
}

# Verificar porta 8000
Write-Host "`n================================================" -ForegroundColor Cyan
Write-Host "  Iniciando Servidor PHP" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

$port = 8000
$portInUse = Get-NetTCPConnection -LocalPort $port -ErrorAction SilentlyContinue
if ($portInUse) {
    Write-Host "⚠ Porta $port já está em uso!" -ForegroundColor Yellow
    $newPort = Read-Host "Digite uma porta diferente"
    $port = [int]$newPort
}

Write-Host "`nIniciando servidor PHP em http://localhost:$port" -ForegroundColor Green
Write-Host "Pressione Ctrl+C para parar o servidor`n" -ForegroundColor Yellow

# Abrir navegador após 2 segundos
Start-Job -ScriptBlock {
    param($url)
    Start-Sleep -Seconds 2
    Start-Process $url
} -ArgumentList "http://localhost:$port" | Out-Null

# Iniciar servidor
php -S localhost:$port
