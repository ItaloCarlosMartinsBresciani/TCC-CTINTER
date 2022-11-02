/* Instituição de Ensino */

CREATE TABLE instituicao_de_ensino (
    id SERIAL PRIMARY KEY NOT NULL, 
    cpnj CHAR(15) UNIQUE NOT NULL,
    inscricao_estadual CHAR(10) NOT NULL,
    razao_social VARCHAR(100) NOT NULL,
    representante_legal VARCHAR(100) NOT NULL,
    cargo VARCHAR(50) NOT NULL,
    ramo_atividade VARCHAR(50) NOT NULL,
    endereco VARCHAR(100) NOT NULL,
    bairro VARCHAR(50) NOT NULL,
    cep CHAR(10) NOT NULL,
    caixa_postal CHAR(10) NOT NULL,
    cidade VARCHAR(30) NOT NULL,
    estado CHAR(4) NOT NULL, /* Sigla */
    telefone CHAR(15) NOT NULL, 

    /* Fax Null */
    
    home_page VARCHAR(50) NOT NULL, /* O que é isso? */
    email VARCHAR(50) NOT NULL,
    data_assinatura DATE NOT NULL
); 

/* Proposta plano de estágio => Olhar novamente */

/* Empresa */

CREATE TABLE empresa (
    id SERIAL PRIMARY KEY NOT NULL, 
    cpnj CHAR(15) UNIQUE NOT NULL, 
    nome VARCHAR(50) NOT NULL,
    endereco VARCHAR(100) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    bairro VARCHAR(50) NOT NULL,
    cidade VARCHAR(30) NOT NULL,
    estado CHAR(4) NOT NULL, /* Sigla */
    cep CHAR(6) NOT NULL,
    telefone CHAR(15) NOT NULL,
    contato VARCHAR(20) NOT NULL,
    ramal VARCHAR(30) NOT NULL
); 

/* Supervisor */

CREATE TABLE supervisor (
    id SERIAL PRIMARY KEY NOT NULL, 
    cpf CHAR(12) UNIQUE NOT NULL,
    nome VARCHAR(40) NOT NULL, 
    email VARCHAR(50) NOT NULL,
    telefone CHAR(15) NOT NULL, 
    cargo VARCHAR(30) NOT NULL, 
    rg CHAR(15) NOT NULL, 
    cic CHAR(25) NOT NULL,

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY (fk_empresa) REFERENCES empresa (id)
); 

/* Orientador */

CREATE TABLE orientador (
    id SERIAL PRIMARY KEY NOT NULL, 
    cpf CHAR(12) UNIQUE NOT NULL,
    rg CHAR(15) UNIQUE NOT NULL,  
    nome VARCHAR(40) NOT NULL,
    cic CHAR(25) NOT NULL, 
    setor VARCHAR(20) NOT NULL, 
    telefone CHAR(15) NOT NULL, 
    email VARCHAR(50) NOT NULL
); 

/* Estagiário */

CREATE TABLE estagiario (
    id SERIAL PRIMARY KEY NOT NULL, 
    ra VARCHAR(20) UNIQUE NOT NULL,
    rg CHAR(15) UNIQUE NOT NULL, 
    cpf CHAR(12) UNIQUE NOT NULL, 
    nome VARCHAR(40) NOT NULL,
    telefone CHAR(15) NOT NULL, 
    email VARCHAR(50) NOT NULL,
    cargo VARCHAR(40) NOT NULL, 
    curso VARCHAR(30) NOT NULL,

    data_inicio DATE NOT NULL,
    data_termino DATE NOT NULL, 
    setor_atuacao VARCHAR(50) NOT NULL,
    
    fk_orientador BIGINT NOT NULL,
    FOREIGN KEY (fk_orientador) REFERENCES orientador (id),

    fk_supervisor BIGINT NOT NULL,
    FOREIGN KEY (fk_supervisor) REFERENCES supervisor (id)
); 

/* Dados Estágio */

CREATE TABLE dados_estagio (
    area VARCHAR(15) NOT NULL,
    hora_semanais INT NOT NULL,
    data_inicio DATE NOT NULL,
    data_termino DATE NOT NULL, 
    total_horas INT NOT NULL,
    bolsa BOOLEAN DEFAULT FALSE,
    valor_bolsa INT DEFAULT 0,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (id)
);

/* Descrição Estágio */

CREATE TABLE descricao_estagio (
    descricao VARCHAR(300) NOT NULL,
    data_assinatura date NOT NULL,
    natureza_estagio BOOLEAN DEFAULT FALSE, 
    assinatura_supervisor BOOLEAN DEFAULT FALSE,
    assinatura_estagiario BOOLEAN DEFAULT FALSE,

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (id)
);

/* Informações Acadêmicas */

CREATE TABLE informacoes_academicas (
    codigo_curso INT NOT NULL,
    total_horas INT NOT NULL, 
    horas_integralizadas INT NOT NULL,
    sobre_total INT NOT NULL, /* total_horas + horas_integralizadas*/
    ano_ingresso INT NOT NULL, 
    obs_semestre INT NOT NULL,
    obs_ano INT NOT NULL,
    data_assinatura date NOT NULL,

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (id)
);

/* =-=-= Documentos =-=-= */

CREATE TABLE convenio (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,

    /* Chave estrangeira => Informações empresa */

    data_assinatura DATE NOT NULL,
    
    assinatura_vice_diretor BOOLEAN DEFAULT FALSE, 
    assinatura_unidade_docente BOOLEAN DEFAULT FALSE, 

    assinatura_testemunha1 BOOLEAN DEFAULT FALSE, 
    rg_testemunha1 CHAR(15) NOT NULL, 
    cpf_testemunha1 CHAR(12) NOT NULL,

    assinatura_testemunha2 BOOLEAN DEFAULT FALSE, 
    rg_testemunha2 CHAR(15) NOT NULL, 
    cpf_testemunha2 CHAR(12) NOT NULL,
    
    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY (fk_empresa) REFERENCES empresa (id)
);

/* ------------------------------------------------- */

CREATE TABLE relatorio_acompanhamento (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,

    /* Chave estrangeira => Nome estagiário*/
    /* Chave estrangeira => natureza estagio */
    /* Chave estrangeira => Data Início */
    /* Chave estrangeira => Data Término */
    /* Chave estrangeira => Nome Empresa */
    /* Chave estrangeira => Setor Atuação */
    /* Chave estrangeira => Supervisor de estágio (Nome) */
    /* Chave estrangeira => Orientador de estágio */
    /* Chave estrangeira => Nome do supervisor da empresa*/

    horas_trabalhadas INT NOT NULL,
    relato_atividade VARCHAR(300),
    data_assinatura DATE,
    
    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (id)
);

/* ------------------------------------------------- */

CREATE TABLE recisao_contrato (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,

    /* Chave estrangeira => Nome Empresa */
    /* Chave estrangeira => Endereço empresa */
    /* Chave estrangeira => estado Empresa */
    /* Chave estrangeira => Nome estagiário */
    /* Chave estrangeira => RA estagiário */
    /* Chave estrangeira => RG estagiário */
    /* Chave estrangeira => RG Orientador */
    /* Chave estrangeira => CIC Orientador */
    /* Chave estrangeira => RG Supervisor */
    /* Chave estrangeira => CIC Supervisor */
    
    data_recisao DATE NOT NULL,
    cidade VARCHAR(30) NOT NULL,
    data_assinatura DATE NOT NULL,
    assinatura_empresa BOOLEAN DEFAULT FALSE, 
    assinatura_faculdade BOOLEAN DEFAULT FALSE, 
    assinatura_estagiario BOOLEAN DEFAULT FALSE, 
    assinatura_orientador BOOLEAN DEFAULT FALSE, 
    assinatura_supervisor BOOLEAN DEFAULT FALSE,

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY (fk_empresa) REFERENCES empresa (id)
);

/* ------------------------------------------------- */

CREATE TABLE prorrogacao_estagio (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,

    /* Chave estrangeira => Nome Empresa */
    /* Chave estrangeira => Endereço empresa */
    /* Chave estrangeira => estado Empresa */
    /* Chave estrangeira => Nome estagiário */
    /* Chave estrangeira => RA estagiário */
    /* Chave estrangeira => RG estagiário */
    /* Chave estrangeira => RG Orientador */
    /* Chave estrangeira => CIC Orientador */
    /* Chave estrangeira => RG Supervisor */
    /* Chave estrangeira => CIC Supervisor */
    
    prorrogacao_inicio DATE NOT NULL,
    prorrogacao_fim DATE NOT NULL,
    cidade VARCHAR(30) NOT NULL,
    data_assinatura DATE NOT NULL,
    assinatura_empresa BOOLEAN DEFAULT FALSE, 
    assinatura_faculdade BOOLEAN DEFAULT FALSE, 
    assinatura_estagiario BOOLEAN DEFAULT FALSE, 
    assinatura_orientador BOOLEAN DEFAULT FALSE, 
    assinatura_supervisor BOOLEAN DEFAULT FALSE, 

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY (fk_empresa) REFERENCES empresa (id)
);

/* ------------------------------------------------- */

CREATE TABLE termo_obrigatorio (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,

    /* Chave estrangeira => Nome Empresa */
    /* Chave estrangeira => Endereço empresa */
    /* Chave estrangeira => Cidade empresa */
    /* Chave estrangeira => estado Empresa */
    /* Chave estrangeira => Nome estagiário */
    /* Chave estrangeira => Ano matrícula estagiário */
    /* Chave estrangeira => Curso estagiário (Info acadêmicas) */
    /* Chave estrangeira => data assinatura convenio */
    /* Chave estrangeira => Data Inicio estagio */ 
    /* Chave estrangeira => Data Fim estagio */ 
    /* Chave estrangeira => Horas semanais (dados estágio) */
    /* Chave estrangeira => Hora Incio */
    /* Chave estrangeira => Hora Fim */
    
    data_assinatura DATE,
    assinatura_empresa BOOLEAN DEFAULT FALSE,
    assinatura_estagiario BOOLEAN DEFAULT FALSE,
    assinatura_interveniente BOOLEAN DEFAULT FALSE,

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY (fk_empresa) REFERENCES empresa (id)
);

/* ------------------------------------------------- */

CREATE TABLE termo_nao_obrigatorio (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,

    /* Chave estrangeira => Nome Empresa */
    /* Chave estrangeira => Endereço empresa */
    /* Chave estrangeira => Cidade empresa */
    /* Chave estrangeira => estado Empresa */
    /* Chave estrangeira => Nome estagiário */
    /* Chave estrangeira => Ano matrícula estagiário */
    /* Chave estrangeira => Curso estagiário (Info acadêmicas) */
    /* Chave estrangeira => data assinatura convenio */
    /* Chave estrangeira => Data Inicio estagio */ 
    /* Chave estrangeira => Data Fim estagio */ 
    /* Chave estrangeira => Horas semanais (dados estágio) */
    /* Chave estrangeira => Hora Incio */
    /* Chave estrangeira => Hora Fim */
    /* Chave estrangeira => RG Orientador */
    /* Chave estrangeira => CIC Orientador */
    /* Chave estrangeira => RG Supervisor */
    /* Chave estrangeira => CIC Supervisor */
    
    data_assinatura DATE,
    assinatura_empresa BOOLEAN DEFAULT FALSE,
    assinatura_estagiario BOOLEAN DEFAULT FALSE,
    assinatura_interveniente BOOLEAN DEFAULT FALSE,
    assinatura_orientador BOOLEAN DEFAULT FALSE, 
    assinatura_supervisor BOOLEAN DEFAULT FALSE,
    
    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY (fk_empresa) REFERENCES empresa (id)
);

/* ------------------------------------------------- */

CREATE TABLE mudanca_modalidade (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,

    /* Chave estrangeira => Nome estagiário */ 
    /* Chave estrangeira => RA */
    /* Chave estrangeira => Curso Atual */
    /* Chave estrangeira => Nome empresa */
    /* Chave estrangeira => data inicio */
    /* Chave estrangeira => data fim */
    /* Chave estrangeira => nome orientador */
    /* Chave estrangeira => Natureza estágio atual */
    /* Chave estrangeira => Nome aluno */
    /* Chave estrangeira => RG aluno */
    /* Chave estrangeira => Email Aluno */
    /* Chave estrangeira => Telefone Aluno */
    /* Chave estrangeira => Telefone empresa */
    
    curso_novo VARCHAR(30) NOT NULL,
    natureza_estagio_novo BOOLEAN DEFAULT FALSE, 
    data_validade DATE NOT NULL,
    razao_troca VARCHAR(140) NOT NULL,
    data_assinatura DATE NOT NULL,
    assinatura_aluno BOOLEAN DEFAULT FALSE,
    assinatura_comissao_estagio BOOLEAN DEFAULT FALSE,
    carimbo_responsavel BOOLEAN DEFAULT FALSE,

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY (fk_empresa) REFERENCES empresa (id)
);

/* ------------------------------------------------- */

CREATE TABLE solicitacao_convalidacao (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,

    /* Chave estrangeira => Nome estagiário */ 
    /* Chave estrangeira => RA */
    /* Chave estrangeira => Curso */
    /* Chave estrangeira => Nome empresa */
    /* Chave estrangeira => data incio */
    /* Chave estrangeira => data fim */
    /* Chave estrangeira => nome orientador */
    /* Chave estrangeira => Nome aluno */
    /* Chave estrangeira => RG aluno */
    /* Chave estrangeira => Email Aluno */
    /* Chave estrangeira => Telefone Aluno */
    /* Chave estrangeira => Telefone empresa */

    
    motivo_convalidacao VARCHAR(140) NOT NULL,
    nota_estagio INT NOT NULL,
    ano_atribuicao INT NOT NULL,
    semestre_atribuicao BOOLEAN DEFAULT FALSE,
    data_assinatura DATE NOT NULL,
    assinatura_aluno BOOLEAN DEFAULT FALSE,
    assinatura_comissao_estagio BOOLEAN DEFAULT FALSE,
    carimbo_responsavel BOOLEAN DEFAULT FALSE,

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY(fk_empresa) REFERENCES empresa(id)
);

/* ------------------------------------------------- */

CREATE TABLE solicitacao_experiencia (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,

    /* Chave estrangeira => Nome estagiário */ 
    /* Chave estrangeira => RA */
    /* Chave estrangeira => Curso */
    /* Chave estrangeira => Nome empresa */
    /* Chave estrangeira => data incio */
    /* Chave estrangeira => Nome aluno */
    /* Chave estrangeira => RG aluno */
    /* Chave estrangeira => Email Aluno */
    /* Chave estrangeira => Telefone Aluno */
    /* Chave estrangeira => Telefone empresa */
    
    motivo VARCHAR(140) NOT NULL,
    descricao_atividade VARCHAR(300) NOT NULL, 
    nota_estagio INT NOT NULL,
    ano_atribuicao INT NOT NULL,
    semestre_atribuicao BOOLEAN DEFAULT FALSE,
    assinatura_aluno BOOLEAN DEFAULT FALSE,

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY(fk_empresa) REFERENCES empresa(id)
);

/* ------------------------------------------------- */

CREATE TABLE desligamento_estagio (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,

    /* Chave estrangeira => Nome empresa */
    /* Chave estrangeira => data assinatura termo de compromisso */
    /* Chave estrangeira => RG aluno */

    clausula VARCHAR(30) NOT NULL,
    data_assinatura DATE NOT NULL, 
    assinatura_aluno BOOLEAN DEFAULT FALSE,
    assinatura_empresa BOOLEAN DEFAULT FALSE,
    assinatura_feb BOOLEAN DEFAULT FALSE,

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY(fk_empresa) REFERENCES empresa(id)
);

/* ------------------------------------------------- */

CREATE TABLE horas_realizadas (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,

    /* Chave estrangeira => Nome estagiário */ 
    /* Chave estrangeira => RA */
    /* Chave estrangeira => Curso */
     /* Chave estrangeira => Nome empresa */
    /* Chave estrangeira => data incio */
    /* Chave estrangeira => data fim */
    /* Chave estrangeira => total horas */
    
    data_assinatura DATE NOT NULL,
    assinatura_empresa BOOLEAN DEFAULT FALSE,

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY(fk_estagiario) REFERENCES estagiario(id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY(fk_empresa) REFERENCES empresa(id)
);

/* ------------------------------------------------- */

CREATE TABLE relatorio_final (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,

    /* Chave estrangeira => Nome estagiário */ 
    /* Chave estrangeira => RA */
    /* Chave estrangeira => Natureza estágio */
    /* Chave estrangeira => Início estágio */
    /* Chave estrangeira => Término estágio */
    /* Chave estrangeira => Total horas trabalhadas */
    /* Chave estrangeira => Nome Empresa */
    /* Chave estrangeira => Setor estagiário */
    /* Chave estrangeira => Nome supervisor empresa */
    /* Chave estrangeira => Nome orientador estágio */
    /* TABELA GRANDE (PENSAR) ################# */
    /* Chave estrangeira => Nome supervisor */
    /* Chave estrangeira => Cargo Supervisor */
    /* Chave estrangeira => Email supervisor */
    /* Chave estrangeira => Telefone supervisor */
    /* TABELA GRANDE 2 (PENSAR) ################# */
    /* Chave estrangeira curso */
    /* Chave estrangeira razão social */

    relato_atividade VARCHAR(300) NOT NULL,
    data_assinatura_estagiario DATE NOT NULL,
    assinatura_estagiario BOOLEAN DEFAULT FALSE,
    sugestao VARCHAR(300) NOT NULL,
    nome_avaliador VARCHAR(40) NOT NULL,
    local_assinatura VARCHAR(30) NOT NULL, 
    
    data_assinatura_orientador DATE NOT NULL,
    assinatura_orientador BOOLEAN DEFAULT FALSE,
    carimbo_empresa BOOLEAN DEFAULT FALSE,


    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY(fk_estagiario) REFERENCES estagiario(id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY(fk_empresa) REFERENCES empresa(id),

    fk_razao_social BIGINT NOT NULL,
    FOREIGN KEY(fk_razao_social) REFERENCES instituicao_de_ensino(id)
);

/* ------------------------------------------------- */

CREATE TABLE relatorio_nota (
    data_ultima_alteracao DATE NOT NULL,
    pessoa_ultima_alteracao BIGINT NOT NULL,
    
    /* Chave estrangeira => Nome estagiário */ 
    /* Chave estrangeira => RA */
    /* Chave estrangeira => Natureza estágio */
    /* Chave estrangeira => Início estágio */
    /* Chave estrangeira => Término estágio */
    /* Chave estrangeira => Total horas trabalhadas */ 
    /* Chave estrangeira => Nome Empresa */
    /* Chave estrangeira => Setor estagiário */
    /* Chave estrangeira => Nome supervisor empresa */
    /* Chave estrangeira => Nome orientador estágio */
    /* TABELA GRANDE (PENSAR) ################# */
    /* Chave estrangeira => Nome supervisor */
    /* Chave estrangeira => Cargo Supervisor */
    /* Chave estrangeira => Email supervisor */
    /* Chave estrangeira => Telefone supervisor */
    /* TABELA GRANDE 2 (PENSAR) ################# */
    /* Chave estrangeira curso */
    /* Chave estrangeira razão social */

    relato_atividade VARCHAR(300) NOT NULL,
    data_assinatura_estagiario DATE NOT NULL,
    assinatura_estagiario BOOLEAN DEFAULT FALSE,
    sugestao VARCHAR(300) NOT NULL,
    nome_avaliador VARCHAR(40) NOT NULL,
    local_assinatura VARCHAR(30) NOT NULL, 
    data_assinatura_orientador DATE NOT NULL,
    assinatura_orientador  BOOLEAN DEFAULT FALSE,
    carimbo_empresa BOOLEAN DEFAULT FALSE,

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY(fk_estagiario) REFERENCES estagiario(id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY(fk_empresa) REFERENCES empresa(id),
    
    fk_razao_social BIGINT NOT NULL,
    FOREIGN KEY(fk_razao_social) REFERENCES instituicao_de_ensino(id)
    
    );
    
    CREATE TABLE atividades_propostas(
        /* Chave estrangeira => estagiario */
        
        atividade INT NOT NULL,
        conteudo_curso VARCHAR(100) NOT NULL,
        conhecimentos_estagio VARCHAR(100) NOT NULL,
        melhoria_curso VARCHAR(100) NOT NULL,

        fk_estagiario BIGINT NOT NULL,
        FOREIGN key (fk_estagiario) REFERENCES estagiario(id)
    );

    CREATE TABLE avaliacao(
        /* Chave estrangeira => estagiario */

        conhecimentos_necessrios_nota DECIMAL NOT NULL,
        porcentagem_atividades_cumpridas DECIMAL NOT NULL,
        qualidade_trabalho DECIMAL NOT NULL,
        senso_responsabilidade DECIMAL NOT NULL,
        disposicao_aprender DECIMAL NOT NULL,
        cooperacao DECIMAL NOT NULL,
        iniciativa DECIMAL NOT NULL,
        sociabilidade DECIMAL NOT NULL,
        pontualidade DECIMAL NOT NULL,
        disciplina DECIMAL NOT NULL,

        fk_estagiario BIGINT NOT NULL,
        FOREIGN KEY (fk_estagiario) REFERENCES estagiario(id)
    );