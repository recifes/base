-- Seeds (Dados Iniciais) MVP HSN
-- Versão: 1.0.0

-- Usuário Administrador Padrão
-- Email: admin@hsn.com.br
-- Senha: 123456
INSERT INTO users (name, email, password, role, active) VALUES
('Administrador HSN', 'admin@hsn.com.br', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1),
('Usuário Teste', 'user@hsn.com.br', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1);

-- Configurações Padrão
INSERT INTO settings (setting_key, setting_value, description) VALUES
('site_name', 'MVP HSN', 'Nome do site'),
('site_description', 'Projeto base do ecossistema HSN', 'Descrição do site'),
('base_cdn_url', 'https://base.hsn.com.br', 'URL da CDN Base HSN'),
('argon_version', 'v1.0.0', 'Versão do Argon Dashboard em uso'),
('zimbros_integration', '1', 'Integração com Formi Zimbros ativa'),
('maintenance_mode', '0', 'Modo de manutenção');

-- Projeto de Exemplo
INSERT INTO projects (name, description, user_id, status) VALUES
('Projeto Inicial', 'Projeto de exemplo criado na instalação', 1, 'active');

-- Log de Instalação
INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES
(1, 'system_install', 'Sistema instalado com sucesso', '127.0.0.1');
