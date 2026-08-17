---
name: prepare-release
description: Prepara release do social-icons-for-woocoomerce-emails: atualiza readme.txt, CHANGELOG.md, cabeçalho PHP, constante SIWCE_PLUGIN_VERSION e DEPLOY_TAG dos workflows baseado no git log
---

# prepare-release

Atualiza **todos** os arquivos que contêm o número de versão para uma nova release do plugin.

## Parâmetros (via `arguments`)

O usuário pode passar os valores diretamente: `"version=2.2.0 tested_up=6.7 php=7.4 highlights=Correção de bug X"`. Se algum valor faltar, pergunte.

- **version** — nova versão (Stable tag)
- **tested_up** — versão do WP testada (Tested up to)
- **php** — versão mínima do PHP (Requires PHP)
- **wp_min** — versão mínima do WordPress (Requires at least; default 6.0)
- **highlights** — resumo da versão (opcional, usa git log se vazio)

## Fluxo de execução

### 1. Coletar valores
Se não recebidos via arguments, pergunte ao usuário um por um. Detecte a versão atual via grep no `.php` raiz:
```
grep -E "Version:|SIWCE_PLUGIN_VERSION" *.php
```

### 2. Analisar as mudanças reais (NÃO copiar os comentários dos commits)
Os bullets do changelog devem descrever o **efeito real** das mudanças, não o texto dos `git log`.
1. Liste os arquivos alterados: `git diff --stat ${LAST_TAG}..HEAD`
2. Leia o diff dos arquivos de código: `git diff ${LAST_TAG}..HEAD -- inc/ *.php`
3. Escreva cada bullet como "o que mudou para o usuário", ex.: "ícones sem URL não são mais renderizados" em vez de "correção na verificação dos campos".

Só se não for possível ler o diff, use os comentários dos commits como pista — nunca como texto final.

### 3. Atualizar TODOS os arquivos com versão

A versão aparece em **7 locais** espalhados por **7 arquivos**. Atualize todos:

#### 3a. `readme.txt`
- `Stable tag:` → nova versão
- `Tested up to:` e `Requires PHP:` se alterados
- `Requires at least:` se alterado (versão mínima do WP)
- Adicionar entrada no topo da seção `== Changelog ==`, **em inglês**, preservando o formato atual (`= VERSION =` + bullets + linha em branco antes da versão anterior):
  ```
  = 2.2.0 =
  * Item baseado nos commits

  = 2.1.1 =
  ```
- Se `highlights` foi fornecido, avalie adicionar na `== Description ==` (NUNCA apague conteúdo existente)

#### 3b. `CHANGELOG.md`
- Adicionar entrada no topo do arquivo, **em português**, no formato atual (`# VERSION` + bullets + linha em branco):
  ```
  # 2.2.0
  * Item baseado nos commits

  # 2.1.1
  ```

#### 3c. `social-icons-for-woocoomerce-emails.php`
- `* Version: NOVA_VERSION` (cabeçalho do plugin)
- `* Requires at least:` e `* Requires PHP:` — manter em sincronia com o `readme.txt`
- `define( 'SIWCE_PLUGIN_VERSION', 'NOVA_VERSION' );` (constante)

#### 3d. `.github/workflows/main.yml`
- `DEPLOY_TAG: "NOVA_VERSION"`

#### 3e. `.github/workflows/dev-release.yml`
- `DEPLOY_TAG: "NOVA_VERSION"`

#### 3f. `.github/workflows/wordpressRelease.yml`
- `DEPLOY_TAG: "NOVA_VERSION"`

### 4. Validação final
Rodar grep com a versão **antiga** para confirmar que não restou nenhuma ocorrência fora do esperado:
```
grep -r "VERSAO_ANTIGA" --include="*.php" --include="*.md" --include="*.txt" --include="*.yml" .
```
O esperado: `readme.txt` e `CHANGELOG.md` ainda contêm a versão antiga **apenas** nas entradas antigas do Changelog (isso é correto). Qualquer outro arquivo retornando a versão antiga é **erro** e deve ser corrigido.

Depois, grep com a versão **nova** para confirmar que aparece em todos os **7 locais**:
```
grep -rn "NOVA_VERSAO" --include="*.php" --include="*.md" --include="*.txt" --include="*.yml" .
```

## Observações específicas deste plugin
- **Slug/pasta/arquivo com typo**: `social-icons-for-woocoomerce-emails` (o "woocoomerce" é intencional — é o slug real no WP.org). NÃO renomear pasta nem o `.php` principal. O título exibido é "Social Icons for **WooCommerce** Emails".
- **`SIWCE_PLUGIN_VERSION`**: está em `2.0.4` enquanto o cabeçalho e o readme estão em `2.1.1` (constante desatualizada). Na próxima release, atualizar AMBOS para o mesmo valor.
- `README.md` **não** tem campo de versão explícito (badges dinâmicas) — não precisa editar.
- `composer.json` **não** tem campo `version` — não precisa editar.
- Não há `release-candidate.yml`; o pré-release usa `dev-release.yml`.
- Assets do WP.org ficam em `wp-assets/` (não `.wp-org`).
- O changelog do `readme.txt` usa formato `= VERSION =` (WordPress clássico), **diferente** do `CHANGELOG.md` que usa `# VERSION`.
- **Requires at least / Requires PHP**: devem existir **tanto** no cabeçalho PHP quanto no `readme.txt`, com valores idênticos (WP mínimo `6.0`, PHP mínimo `8.2`).
- **Datas de release**: fuso `America/Sao_Paulo`. `readme.txt` → `aaaa-mm-dd`; `CHANGELOG.md` → `dd/mm/aaaa`.
