<?php
/**
 * Plugin Name: VETTRYX WP Compliance
 * Plugin URI:  https://github.com/vettryx/vettryx-wp-core
 * Description: Submódulo do VETTRYX WP Core para gestão de privacidade, LGPD e notificação de incidentes de segurança.
 * Version:     1.0.0
 * Author:      VETTRYX Tech
 * Author URI:  https://vettryx.com.br
 * License:     Proprietária (Uso Comercial Exclusivo)
 * Vettryx Icon: dashicons-shield
 */

// Segurança: Impede o acesso direto ao arquivo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ==============================================================================
 * 1. REGISTRO DE ROTAS E MENU
 * ==============================================================================
 */

add_action('admin_menu', 'vettryx_compliance_add_submenu', 99);
function vettryx_compliance_add_submenu() {
    add_submenu_page(
        'vettryx-core-modules',
        'Compliance Manager - VETTRYX Tech',
        'Compliance Manager', // Mantendo o padrão de nomenclatura limpa da sidebar
        'manage_options',
        'vettryx-wp-compliance',
        'vettryx_compliance_dashboard_html'
    );
}

/**
 * ==============================================================================
 * 2. INTERFACE E DISPARO DE INCIDENTES
 * ==============================================================================
 */

function vettryx_compliance_dashboard_html() {
    if (!current_user_can('manage_options')) return;

    // Lógica de disparo do e-mail de notificação de incidente
    if (isset($_POST['vettryx_incident_action']) && check_admin_referer('vettryx_incident_nonce')) {
        $client_email = sanitize_email($_POST['client_email']);
        $incident_type = sanitize_text_field($_POST['incident_type']);
        $incident_desc = sanitize_textarea_field($_POST['incident_desc']);
        
        $site_name = get_bloginfo('name');
        
        $subject = "[URGENTE] Notificação de Incidente de Segurança - VETTRYX Tech";
        $message = "Olá,\n\n";
        $message .= "Em cumprimento às diretrizes de segurança e LGPD, a VETTRYX Tech notifica formalmente um incidente detectado no ambiente do site $site_name.\n\n";
        $message .= "TIPO DE INCIDENTE: $incident_type\n";
        $message .= "DESCRIÇÃO / STATUS TÉCNICO:\n$incident_desc\n\n";
        $message .= "Nossa equipe já está atuando na contenção e análise técnica do ocorrido. Manteremos contato com as atualizações.\n\n";
        $message .= "Atenciosamente,\nEquipe Técnica - VETTRYX Tech\nvettryx.com.br";
        
        $headers = array('Content-Type: text/plain; charset=UTF-8', 'From: VETTRYX Tech <suporte@vettryx.com.br>');
        
        if (wp_mail($client_email, $subject, $message, $headers)) {
            echo '<div class="notice notice-success is-dismissible"><p>Notificação de incidente enviada com sucesso para o cliente.</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Erro ao disparar o e-mail. Verifique as configurações de SMTP (Módulo WP Mail).</p></div>';
        }
    }
    ?>
    <div class="wrap">
        <h1 style="display:flex; align-items:center; gap:10px; margin-bottom: 20px;">
            <span class="dashicons dashicons-shield" style="font-size: 28px; width: 28px; height: 28px;"></span> 
            Compliance Manager (LGPD)
        </h1>
        <p>Central de adequação à Lei Geral de Proteção de Dados e resposta rápida a incidentes.</p>

        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 300px; background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <h2 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 10px;">Direitos dos Titulares</h2>
                <p>Ferramentas oficiais do WordPress para gerenciar dados de usuários cadastrados, clientes ou comentaristas.</p>
                
                <div style="margin-top: 20px;">
                    <a href="<?php echo admin_url('export-personal-data.php'); ?>" class="button button-secondary" style="display:block; text-align:center; margin-bottom: 10px; padding: 5px 10px; height: auto;">
                        <span class="dashicons dashicons-download" style="margin-top: 3px;"></span> Exportar Dados Pessoais
                    </a>
                    <a href="<?php echo admin_url('erase-personal-data.php'); ?>" class="button button-secondary" style="display:block; text-align:center; margin-bottom: 10px; padding: 5px 10px; height: auto; color: #dc3232; border-color: #dc3232;">
                        <span class="dashicons dashicons-trash" style="margin-top: 3px;"></span> Apagar Dados Pessoais
                    </a>
                    <p class="description" style="font-size: 12px;">Útil quando um cliente solicita a exclusão definitiva ou a cópia de seus dados em conformidade com o Art. 18 da LGPD.</p>
                </div>
            </div>

            <div style="flex: 2; min-width: 400px; background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <h2 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 10px; color: #dc3232;">
                    <span class="dashicons dashicons-warning"></span> Alerta de Incidente (SLA 24h)
                </h2>
                <p>Utilize este formulário para notificar o Controlador (cliente) imediatamente após a ciência de um vazamento ou instabilidade crítica de segurança.</p>
                
                <form method="post" action="">
                    <?php wp_nonce_field('vettryx_incident_nonce'); ?>
                    <input type="hidden" name="vettryx_incident_action" value="send">
                    
                    <table class="form-table" style="margin-top: 0;">
                        <tr>
                            <th scope="row" style="padding: 10px 10px 10px 0;"><label for="client_email">E-mail do Cliente</label></th>
                            <td style="padding: 10px 0;"><input type="email" name="client_email" id="client_email" class="regular-text" placeholder="Ex: financeiro@antebellum.com.br" required></td>
                        </tr>
                        <tr>
                            <th scope="row" style="padding: 10px 10px 10px 0;"><label for="incident_type">Tipo de Incidente</label></th>
                            <td style="padding: 10px 0;">
                                <select name="incident_type" id="incident_type" style="width: 100%;">
                                    <option value="Instabilidade/Queda Crítica (Downtime)">Instabilidade/Queda Crítica (Downtime)</option>
                                    <option value="Injeção de Malware / Redirecionamento">Injeção de Malware / Redirecionamento</option>
                                    <option value="Suspeita de Acesso Não Autorizado">Suspeita de Acesso Não Autorizado</option>
                                    <option value="Vazamento Confirmado de Banco de Dados">Vazamento Confirmado de Banco de Dados</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" style="padding: 10px 10px 10px 0;"><label for="incident_desc">Status da Atuação</label></th>
                            <td style="padding: 10px 0;">
                                <textarea name="incident_desc" id="incident_desc" rows="4" style="width:100%;" required placeholder="Ex: O site sofreu instabilidade devido a ataque DDoS. O ambiente já foi isolado e o firewall otimizado..."></textarea>
                            </td>
                        </tr>
                    </table>
                    <p class="submit" style="padding-bottom: 0; margin-bottom: 0;">
                        <button type="submit" class="button button-primary" onclick="return confirm('ATENÇÃO: Isso enviará um e-mail de alerta oficial para o cliente. Deseja continuar?');">
                            Disparar Notificação Oficial
                        </button>
                    </p>
                </form>
            </div>

        </div>
    </div>
    <?php
}
