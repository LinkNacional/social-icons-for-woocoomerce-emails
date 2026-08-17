# AGENTS.md — Diretrizes Absolutas

Regras imutáveis. Qualquer desvio deve ser justificado no código via comentário `// REASON:`.

---

## 1. Arquitetura

### Estado atual
- Plugin procedural, single-entry: `social-icons-for-woocoomerce-emails.php` (bootstrap) + `inc/`.
  - `inc/plugin_helpers.php` — utilitários (lista de ícones, URI das imagens).
  - `inc/plugin_functions.php` — renderização do rodapé + campos de settings.
  - `inc/plugin_hooks.php` — registro dos hooks/filtros.
- Sem classes, sem namespace, sem dependências de runtime.

### SOLID
- **S — Single Responsibility**: manter helpers/functions/hooks separados como hoje. Ao crescer, separar em `Admin/`, `Public/` e `Includes/` (padrão Link Nacional).
- **O — Open/Closed**: extensão via filtros, nunca via edição de código existente.
  - Filtros públicos existentes: `siwce_social_links`, `siwce_icon_image_uri`, `siwce_icon_default_size`, `siwce_footer_style`.
  - Novos pontos: `apply_filters('siwce_*', $value, $context)`.
- **D — Dependency Inversion**: config via `get_option()`, nunca hardcoded.

### PSR-4 (quando houver refactor para OO)
```
Lkn\Siwce\Includes\  → inc/
Lkn\Siwce\Admin\     → Admin/
Lkn\Siwce\PublicView\ → Public/
```
- 1 classe por arquivo. Nome do arquivo = nome da classe.

---

## 2. Segurança

### Superglobais — sanitizar SEMPRE
```php
// Proibido
$id = $_GET['id'];

// Obrigatório
$id = isset($_GET['id']) ? absint($_GET['id']) : 0;
$name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
```

### Nonces — toda requisição state-changing
```php
if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'siwce_action')) {
    wp_die('Security check failed.');
}
```

### Output escaping
```php
echo esc_html($value);       // HTML context
echo esc_attr($value);       // Attribute context
echo esc_url($url);          // URL context
```

### SQL — prepared statements
```php
// Proibido
$wpdb->query("SELECT * FROM $wpdb->postmeta WHERE meta_key = '$key'");

// Obrigatório
$wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = %s", $key);
```

---

## 3. Padrões WordPress / WooCommerce

### Naming
- Funções: `siwce_*`
- Hooks/filtros: `siwce_*`
- Options: `siwce_*` (ex.: `siwce_img_width`, `siwce_text_before_icons`, `siwce_url_{id}`)
- Constantes: `SIWCE_*`

### Internacionalização
- Toda string visível ao usuário: `__()`, `_e()`, `_n()`
- Text domain: `social-icons-for-woocoomerce-emails` (deve coincidir com o slug; exigência do WordPress.org Plugin Check)

### Datas e fuso horário
- Sempre usar fuso brasileiro (`America/Sao_Paulo`, UTC-3) para calcular datas de release/changelog.
- `CHANGELOG.md` → pt-BR, data no padrão BR `dd/mm/aaaa` (ex.: `16/08/2026`).
- `readme.txt` → inglês, data no padrão `aaaa-mm-dd` (ex.: `2026-08-16`).

### Slug (WordPress.org) — NÃO ALTERAR
- `social-icons-for-woocoomerce-emails` (com typo "woocoomerce" — é o slug real no WP.org).
- NÃO renomear a pasta nem o arquivo principal `social-icons-for-woocoomerce-emails.php`.
- O título/nome exibido é "Social Icons for **WooCommerce** Emails" (grafia correta), mas o slug/arquivo/pasta mantêm o typo histórico.

### Comportamento
- Rodapé do email: filtro `woocommerce_email_footer_text` → `siwce_email_social_icons`.
- Settings: filtro `woocommerce_get_settings_email` → `siwce_social_icons_settings`.
- Estilo: filtro `woocommerce_email_styles` → `siwce_text_before_icons_style`.

### Assets
- Imagens em `static/images/` (referenciadas via `SIWCE_ASSETS_URL`).
- `wp_enqueue_script()` / `wp_enqueue_style()` com versionamento quando aplicável.

---

## 4. Tratamento de Erros
- Nunca expor stack traces para o frontend.
- Fallback quando WooCommerce não está disponível.

---

## 5. Build & Qualidade

```bash
composer install   # setup (phan)
vendor/bin/phan    # análise estática
```

- Sem build de JS/CSS.

---

## 6. Comunicação (Caveman Mode + RTK)

### Caveman Mode — ATIVO
- Zero saudações. Zero "claro!", "ótimo!", "vamos lá!".
- Zero resumos pós-entrega.
- Frases curtas. Sem períodos compostos.
- Código > prosa. Sempre.

### RTK (Rust Token Killer) — ATIVO
- Logs de terminal são comprimidos pelo RTK antes de chegar ao LLM.
- Nunca solicitar output verboso se snippet RTK estruturado já foi fornecido.
- Confiar no pré-parsing do RTK.

### Formato de resposta esperado
```
Tipo: [fix|feat|refactor|security]
Arquivo: path/to/file.php:123
Problema: descrição ≤1 linha
Solução: descrição ≤1 linha
---
[código/diff]
```
