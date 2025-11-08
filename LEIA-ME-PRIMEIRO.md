# 🔧 CORREÇÃO RÁPIDA - Filtro de Categorias

## ❌ PROBLEMA
Quando você clica em "Placas de Vídeo", não aparecem todos os produtos dessa categoria.

## ✅ CAUSA
Os IDs das categorias no banco de dados não correspondem aos IDs usados no código.

## 🚀 SOLUÇÃO RÁPIDA

### **PASSO 1: Executar Script de Correção**

Abra o PowerShell nesta pasta e execute:

```powershell
.\fix_categorias.ps1
```

**OU** se preferir fazer manualmente via MySQL:

```bash
# Se você tiver MySQL instalado e no PATH:
mysql -u root -p loja_hardware < fix_categories.sql

# Digite sua senha quando solicitado
```

### **PASSO 2: Testar**

1. Inicie o servidor PHP:
   ```powershell
   php -S localhost:8000
   ```

2. Acesse: http://localhost:8000/index.html

3. Clique em "Placas de Vídeo" no menu

4. ✅ Agora deve mostrar TODOS os produtos de Placas de Vídeo!

---

## 📊 O QUE O SCRIPT FAZ

1. Deleta as categorias antigas (produtos não são afetados)
2. Recria as categorias com IDs corretos:
   - **5** = Placas de Vídeo
   - **6** = Processadores  
   - **7** = Placas-mãe
   - **8** = Memória RAM
   - **13** = SSD
   - **14** = HD
   - **3** = Monitores
   - **2** = Periféricos

---

## 🔍 VERIFICAÇÃO

Após executar, teste cada categoria:
- ✅ Placas de Vídeo → 3 produtos (RTX 4090, RX 7900 XTX, RTX 4070 Ti)
- ✅ Processadores → 3 produtos (i9-14900K, Ryzen 9 7950X, i5-14600K)
- ✅ Memória RAM → 2 produtos (Corsair, Kingston)
- ✅ Placas-mãe → 2 produtos (ASUS, MSI)

---

## ⚠️ IMPORTANTE

**NÃO PERCA DADOS:**
- O script NÃO apaga seus produtos
- O script NÃO apaga seus clientes
- O script NÃO apaga seus pedidos
- Apenas recria as categorias com IDs corretos

---

## 🆘 PROBLEMAS?

### Se aparecer "mysql não é reconhecido":
**Opção 1:** Use o MySQL Workbench
1. Abra o arquivo `fix_categories.sql`
2. Execute todo o conteúdo (Ctrl+Shift+Enter)

**Opção 2:** Reinstale tudo
```powershell
.\install.ps1
```
Escolha "s" para recriar banco e popular com dados

---

## 📞 RESUMO

**Problema:** Filtro não mostra todos os produtos  
**Solução:** Execute `.\fix_categorias.ps1`  
**Tempo:** Menos de 1 minuto  
**Resultado:** Filtros funcionando 100% ✅  

---

**Documentação completa em:** [FIX_CATEGORIAS.md](FIX_CATEGORIAS.md)
