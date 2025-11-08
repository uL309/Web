# Script de Correcao Rapida - IDs das Categorias# Script de Correção Rápida - IDs das Categorias

# Execute este script se o filtro de categorias nao estiver funcionando# Execute este script se o filtro de categorias não estiver funcionando



Write-Host "================================================" -ForegroundColor CyanWrite-Host "================================================" -ForegroundColor Cyan

Write-Host "  Correcao de Categorias - CiberTech" -ForegroundColor CyanWrite-Host "  Correção de Categorias - CiberTech" -ForegroundColor Cyan

Write-Host "================================================" -ForegroundColor CyanWrite-Host "================================================" -ForegroundColor Cyan

Write-Host ""Write-Host ""



Write-Host "Este script ira corrigir os IDs das categorias no banco de dados." -ForegroundColor YellowWrite-Host "Este script irá corrigir os IDs das categorias no banco de dados." -ForegroundColor Yellow

Write-Host "ATENCAO: Isso ira recriar as categorias (produtos nao serao afetados)." -ForegroundColor YellowWrite-Host "ATENÇÃO: Isso irá recriar as categorias (produtos não serão afetados)." -ForegroundColor Yellow

Write-Host ""Write-Host ""



$continue = Read-Host "Deseja continuar? (s/N)"$continue = Read-Host "Deseja continuar? (s/N)"

if ($continue -ne 's' -and $continue -ne 'S') {if ($continue -ne 's' -and $continue -ne 'S') {

    Write-Host "Operacao cancelada." -ForegroundColor Red    Write-Host "Operação cancelada." -ForegroundColor Red

    exit 0    exit 0

}}



# Configuracao do banco# Configuração do banco

Write-Host ""Write-Host "`nConfiguração do MySQL:" -ForegroundColor Cyan

Write-Host "Configuracao do MySQL:" -ForegroundColor Cyan$dbHost = Read-Host "Host (padrão: 127.0.0.1)"

$dbHost = Read-Host "Host (padrao: 127.0.0.1)"if ([string]::IsNullOrWhiteSpace($dbHost)) { $dbHost = "127.0.0.1" }

if ([string]::IsNullOrWhiteSpace($dbHost)) { $dbHost = "127.0.0.1" }

$dbPort = Read-Host "Porta (padrão: 3306)"

$dbPort = Read-Host "Porta (padrao: 3306)"if ([string]::IsNullOrWhiteSpace($dbPort)) { $dbPort = "3306" }

if ([string]::IsNullOrWhiteSpace($dbPort)) { $dbPort = "3306" }

$dbUser = Read-Host "Usuário (padrão: root)"

$dbUser = Read-Host "Usuario (padrao: root)"if ([string]::IsNullOrWhiteSpace($dbUser)) { $dbUser = "root" }

if ([string]::IsNullOrWhiteSpace($dbUser)) { $dbUser = "root" }

$dbPass = Read-Host "Senha (deixe vazio se não tiver)" -AsSecureString

$dbPass = Read-Host "Senha (deixe vazio se nao tiver)" -AsSecureString$dbPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto(

$dbPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto(    [Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPass)

    [Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPass))

)

$dbName = "loja_hardware"

$dbName = "loja_hardware"

Write-Host "`nExecutando correção..." -ForegroundColor Yellow

Write-Host ""

Write-Host "Executando correcao..." -ForegroundColor Yellow# Executar script SQL

try {

# Executar script SQL    if ([string]::IsNullOrWhiteSpace($dbPassPlain)) {

try {        Get-Content fix_categories.sql | mysql -h $dbHost -P $dbPort -u $dbUser $dbName

    if ([string]::IsNullOrWhiteSpace($dbPassPlain)) {    } else {

        Get-Content fix_categories.sql | & mysql -h $dbHost -P $dbPort -u $dbUser $dbName        Get-Content fix_categories.sql | mysql -h $dbHost -P $dbPort -u $dbUser -p"$dbPassPlain" $dbName

    } else {    }

        Get-Content fix_categories.sql | & mysql -h $dbHost -P $dbPort -u $dbUser -p"$dbPassPlain" $dbName    

    }    if ($LASTEXITCODE -eq 0) {

            Write-Host "`n✓ Categorias corrigidas com sucesso!" -ForegroundColor Green

    if ($LASTEXITCODE -eq 0) {        Write-Host "`nMapeamento de IDs:" -ForegroundColor Cyan

        Write-Host ""        Write-Host "  5  - Placas de Vídeo" -ForegroundColor White

        Write-Host "[OK] Categorias corrigidas com sucesso!" -ForegroundColor Green        Write-Host "  6  - Processadores" -ForegroundColor White

        Write-Host ""        Write-Host "  7  - Placas-mãe" -ForegroundColor White

        Write-Host "Mapeamento de IDs:" -ForegroundColor Cyan        Write-Host "  8  - Memória RAM" -ForegroundColor White

        Write-Host "  5  - Placas de Video" -ForegroundColor White        Write-Host "  10 - Teclados" -ForegroundColor White

        Write-Host "  6  - Processadores" -ForegroundColor White        Write-Host "  11 - Mouses" -ForegroundColor White

        Write-Host "  7  - Placas-mae" -ForegroundColor White        Write-Host "  12 - Headsets" -ForegroundColor White

        Write-Host "  8  - Memoria RAM" -ForegroundColor White        Write-Host "  13 - SSD" -ForegroundColor White

        Write-Host "  10 - Teclados" -ForegroundColor White        Write-Host "  14 - HD" -ForegroundColor White

        Write-Host "  11 - Mouses" -ForegroundColor White        Write-Host "  3  - Monitores" -ForegroundColor White

        Write-Host "  12 - Headsets" -ForegroundColor White        Write-Host "  2  - Periféricos" -ForegroundColor White

        Write-Host "  13 - SSD" -ForegroundColor White        

        Write-Host "  14 - HD" -ForegroundColor White        Write-Host "`nAgora o filtro de categorias deve funcionar corretamente!" -ForegroundColor Green

        Write-Host "  3  - Monitores" -ForegroundColor White    } else {

        Write-Host "  2  - Perifericos" -ForegroundColor White        Write-Host "`n✗ Erro ao executar correção!" -ForegroundColor Red

        Write-Host ""        Write-Host "Execute manualmente:" -ForegroundColor Yellow

        Write-Host "Agora o filtro de categorias deve funcionar corretamente!" -ForegroundColor Green        Write-Host "mysql -u $dbUser -p $dbName < fix_categories.sql" -ForegroundColor Gray

    } else {    }

        Write-Host ""} catch {

        Write-Host "[ERRO] Erro ao executar correcao!" -ForegroundColor Red    Write-Host "`n✗ Erro: $_" -ForegroundColor Red

        Write-Host "Execute manualmente:" -ForegroundColor Yellow}

        Write-Host "  1. Abra MySQL Workbench ou linha de comando" -ForegroundColor Gray

        Write-Host "  2. Execute o arquivo fix_categories.sql" -ForegroundColor GrayWrite-Host "`nPressione Enter para sair..."

    }Read-Host

} catch {
    Write-Host ""
    Write-Host "[ERRO] Erro: $_" -ForegroundColor Red
    Write-Host ""
    Write-Host "Tente executar manualmente:" -ForegroundColor Yellow
    Write-Host "  mysql -u $dbUser -p" -ForegroundColor Gray
    Write-Host "  USE loja_hardware;" -ForegroundColor Gray
    Write-Host "  SOURCE fix_categories.sql;" -ForegroundColor Gray
}

Write-Host ""
Write-Host "Pressione Enter para sair..." -NoNewline
Read-Host
