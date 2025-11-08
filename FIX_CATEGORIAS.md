# 🔧 Correção - Filtro de Categorias

## 🐛 Problema Identificado

O filtro de categorias não estava funcionando corretamente porque havia uma **inconsistência entre os IDs das categorias no banco de dados e os IDs usados no código HTML/JavaScript**.

### **Situação Anterior:**

**Banco de Dados** (`populate_db.sql` antigo):
- Categorias eram inseridas com `AUTO_INCREMENT`
- Placas de Vídeo recebia ID sequencial (provavelmente 5)
- MAS isso dependia da ordem de inserção

**Frontend** (HTML):
- Código esperava IDs específicos:
  - `data-category="5"` para Placas de Vídeo
  - `data-category="6"` para Processadores
  - etc.

### **Resultado:**
Ao clicar em "Placas de Vídeo" (categoria=5), o backend buscava produtos com `categoria_id=5`, mas no banco essa categoria poderia ter outro ID.

---

## ✅ Solução Implementada

### **1. Correção do `populate_db.sql`**
Agora as categorias são inseridas com **IDs fixos e específicos**:

```sql
INSERT INTO categoria (categoria_id, nome, categoria_pai_id) VALUES
(5, 'Placas de Vídeo', 1),
(6, 'Processadores', 1),
(7, 'Placas-mãe', 1),
(8, 'Memória RAM', 1),
(10, 'Teclados', 2),
(11, 'Mouses', 2),
(12, 'Headsets', 2),
(13, 'SSD', 4),
(14, 'HD', 4),
(3, 'Monitores', NULL),
(2, 'Periféricos', NULL);
```

### **2. Script de Correção Criado**
Para quem já tem banco configurado: `fix_categories.sql`

### **3. Script PowerShell Criado**
Execução automatizada: `fix_categorias.ps1`

---

## 🎯 Mapeamento Correto de IDs

| ID | Categoria | Categoria Pai |
|----|-----------|---------------|
| 1 | Componentes | - |
| 2 | Periféricos | - |
| 3 | Monitores | - |
| 4 | Armazenamento | - |
| 5 | Placas de Vídeo | Componentes (1) |
| 6 | Processadores | Componentes (1) |
| 7 | Placas-mãe | Componentes (1) |
| 8 | Memória RAM | Componentes (1) |
| 10 | Teclados | Periféricos (2) |
| 11 | Mouses | Periféricos (2) |
| 12 | Headsets | Periféricos (2) |
| 13 | SSD | Armazenamento (4) |
| 14 | HD | Armazenamento (4) |

---

## 🚀 Como Aplicar a Correção

### **Opção 1: Script PowerShell (RECOMENDADO)**

```powershell
cd f:\weeeeeeeeeb\Web
.\fix_categorias.ps1
```

**O script irá:**
1. Pedir suas credenciais do MySQL
2. Executar `fix_categories.sql`
3. Recriar as categorias com IDs corretos
4. Mostrar confirmação

### **Opção 2: Manualmente via MySQL**

```powershell
# Via linha de comando
mysql -u root -p loja_hardware < fix_categories.sql

# OU via MySQL Workbench
# 1. Abra fix_categories.sql
# 2. Execute todo o script
```

### **Opção 3: Recriar Banco Completo**

```powershell
# Se preferir começar do zero
cd f:\weeeeeeeeeb\Web
.\install.ps1

# Escolha "s" para criar/recriar banco
# Escolha "s" para popular com dados de exemplo
```

---

## 🧪 Como Testar Após Correção

### **Teste 1: Via Navegador**

1. Inicie o servidor PHP:
   ```powershell
   php -S localhost:8000
   ```

2. Acesse: `http://localhost:8000/index.html`

3. Clique em **"Placas de Vídeo"** no menu

4. ✅ Deve redirecionar para: `busca.html?categoria=5`

5. ✅ Deve mostrar **apenas placas de vídeo** (RTX 4090, RX 7900 XTX, etc.)

### **Teste 2: Via API Direta**

Acesse no navegador:
```
http://localhost:8000/php/products.php?categoria=5
```

**Resposta esperada:**
```json
{
  "success": true,
  "products": [
    {
      "produto_id": 1,
      "nome": "NVIDIA RTX 4090 24GB",
      "categoria_nome": "Placas de Vídeo",
      ...
    },
    {
      "produto_id": 2,
      "nome": "AMD Radeon RX 7900 XTX",
      "categoria_nome": "Placas de Vídeo",
      ...
    }
  ],
  "pagination": {
    "total": 3,
    ...
  }
}
```

### **Teste 3: Verificar Banco de Dados**

```sql
-- Ver todas as categorias
SELECT categoria_id, nome FROM categoria ORDER BY categoria_id;

-- Ver produtos por categoria
SELECT 
    c.categoria_id,
    c.nome as categoria,
    COUNT(p.produto_id) as total_produtos
FROM categoria c
LEFT JOIN produto p ON c.categoria_id = p.categoria_id
GROUP BY c.categoria_id, c.nome
ORDER BY c.categoria_id;
```

**Resultado esperado:**
```
categoria_id | categoria        | total_produtos
-------------|------------------|---------------
5            | Placas de Vídeo  | 3
6            | Processadores    | 3
7            | Placas-mãe       | 2
8            | Memória RAM      | 2
...
```

---

## 📋 Checklist de Verificação

Após executar a correção, verifique:

- [ ] Script `fix_categorias.ps1` executou sem erros
- [ ] Categorias foram recriadas no banco
- [ ] Produtos mantiveram seus `categoria_id` corretos
- [ ] Filtro por "Placas de Vídeo" retorna apenas placas de vídeo
- [ ] Filtro por "Processadores" retorna apenas processadores
- [ ] Filtro por "Memória RAM" retorna apenas memórias RAM
- [ ] Todos os outros filtros funcionam corretamente

---

## 🔍 Arquivos Alterados

### **Modificados:**
✅ `populate_db.sql` - IDs fixos nas categorias

### **Novos:**
✅ `fix_categories.sql` - Script SQL de correção  
✅ `fix_categorias.ps1` - Script PowerShell automatizado  
✅ `FIX_CATEGORIAS.md` - Esta documentação  

---

## 🆘 Troubleshooting

### **Erro: "mysql não é reconhecido"**
**Solução:**
- Adicione MySQL ao PATH do Windows
- OU use MySQL Workbench para executar o script
- OU reinstale o banco via `install.ps1`

### **Erro: "Foreign key constraint fails"**
**Solução:**
O script `fix_categories.sql` já desabilita verificação de FK:
```sql
SET FOREIGN_KEY_CHECKS = 0;
-- ... operações ...
SET FOREIGN_KEY_CHECKS = 1;
```

### **Filtro ainda não funciona após correção**
**Verifique:**
1. Cache do navegador (Ctrl+F5 para hard reload)
2. Console do navegador (F12) para erros JavaScript
3. Se o servidor PHP está rodando
4. Se executou o script de correção corretamente

---

## ✅ Confirmação Final

Após executar a correção, você deve conseguir:

✅ Clicar em qualquer categoria no menu  
✅ Ver apenas produtos daquela categoria  
✅ Usar filtros de preço e ordenação  
✅ Adicionar produtos ao carrinho  
✅ Navegar entre páginas de resultados  

---

**Problema Resolvido!** 🎉

Se tiver dúvidas, consulte:
- [BUSCA.md](BUSCA.md) - Documentação completa do sistema de busca
- [INTEGRACAO.md](INTEGRACAO.md) - Guia rápido de integração
- [README.md](README.md) - Documentação geral do projeto
