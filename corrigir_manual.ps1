# Script Alternativo - Execucao Manual do SQL
# Use este se o fix_categorias.ps1 nao funcionar

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  Instrucoes para Correcao Manual" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "OPCAO 1 - MySQL Workbench (RECOMENDADO)" -ForegroundColor Yellow
Write-Host "  1. Abra o MySQL Workbench" -ForegroundColor White
Write-Host "  2. Conecte ao servidor MySQL" -ForegroundColor White
Write-Host "  3. Abra o arquivo: fix_categories.sql" -ForegroundColor White
Write-Host "  4. Execute todo o script (Ctrl+Shift+Enter)" -ForegroundColor White
Write-Host ""

Write-Host "OPCAO 2 - Linha de Comando MySQL" -ForegroundColor Yellow
Write-Host "  1. Abra o terminal/prompt do MySQL" -ForegroundColor White
Write-Host "  2. Execute:" -ForegroundColor White
Write-Host "     mysql -u root -p" -ForegroundColor Cyan
Write-Host "  3. Digite sua senha" -ForegroundColor White
Write-Host "  4. Execute:" -ForegroundColor White
Write-Host "     USE loja_hardware;" -ForegroundColor Cyan
Write-Host "     SOURCE fix_categories.sql;" -ForegroundColor Cyan
Write-Host ""

Write-Host "OPCAO 3 - Copiar e Colar SQL" -ForegroundColor Yellow
Write-Host "  1. Abra o arquivo fix_categories.sql em um editor" -ForegroundColor White
Write-Host "  2. Copie todo o conteudo" -ForegroundColor White
Write-Host "  3. Cole e execute no MySQL Workbench ou phpMyAdmin" -ForegroundColor White
Write-Host ""

$openFile = Read-Host "Deseja abrir o arquivo fix_categories.sql agora? (s/N)"
if ($openFile -eq 's' -or $openFile -eq 'S') {
    if (Test-Path "fix_categories.sql") {
        notepad fix_categories.sql
    } else {
        Write-Host "[ERRO] Arquivo fix_categories.sql nao encontrado!" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "Pressione Enter para sair..." -NoNewline
Read-Host
