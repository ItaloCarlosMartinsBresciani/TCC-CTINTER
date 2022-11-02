/* Instituição de Ensino */

CREATE TABLE instituicao_de_ensino (
    id SERIAL PRIMARY KEY NOT NULL, 
    cpnj CHAR(15) UNIQUE NOT NULL,
    nome VARCHAR(50) NOT NULL,
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
    
    home_page VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    data_assinatura DATE NOT NULL
); 

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

/* Pessoas */

CREATE TABLE pessoas (
    id SERIAL PRIMARY KEY NOT NULL,
    cpf CHAR(12) UNIQUE NOT NULL,
    nome VARCHAR(40) NOT NULL,
    email VARCHAR(50) NOT NULL,
    telefone CHAR(15) NOT NULL, 
    rg CHAR(15) NOT NULL,

    sub VARCHAR(25) NOT NULL, /* GAPI */
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

/* Supervisor */

CREATE TABLE supervisor (
    fk_id BIGINT NOT NULL UNIQUE, 

    cargo VARCHAR(30) NOT NULL, 
    cic CHAR(25) NOT NULL,

    FOREIGN KEY (fk_id) REFERENCES pessoas(id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY (fk_empresa) REFERENCES empresa (id)
); 

/* Funcionário setor de estágio (Empresa e universidade) */

/* Funcionário Empresa*/

CREATE TABLE funcionario_empresa (
    fk_id BIGINT NOT NULL UNIQUE,

    cargo VARCHAR(30) NOT NULL, 
    cic CHAR(25) NOT NULL,
    
    FOREIGN KEY (fk_id) REFERENCES pessoas(id),

    fk_empresa BIGINT NOT NULL,
    FOREIGN KEY (fk_empresa) REFERENCES empresa (id)
);

/* Funcionário Universidade*/

CREATE TABLE funcionario_universidade (
    fk_id BIGINT NOT NULL UNIQUE,

    cargo VARCHAR(30) NOT NULL,
    cic CHAR(25) NOT NULL,
    
    FOREIGN KEY (fk_id) REFERENCES pessoas(id),

    fk_instituicao BIGINT NOT NULL,
    FOREIGN KEY (fk_instituicao) REFERENCES instituicao_de_ensino(id)
);

/* Orientador */

CREATE TABLE orientador (
    fk_id BIGINT NOT NULL UNIQUE,

    cic CHAR(25) NOT NULL, 
    setor VARCHAR(20) NOT NULL, 

    FOREIGN KEY (fk_id) REFERENCES pessoas(id)
); 

/* Estagiário */

CREATE TABLE estagiario (
    fk_id BIGINT NOT NULL UNIQUE,
    ra VARCHAR(20) UNIQUE NOT NULL,
    cargo VARCHAR(40) NOT NULL, 
    curso VARCHAR(30) NOT NULL,

    setor_atuacao VARCHAR(50) NOT NULL,
    
    FOREIGN KEY (fk_id) REFERENCES pessoas(id),

    fk_orientador BIGINT NOT NULL,
    FOREIGN KEY (fk_orientador) REFERENCES orientador (fk_id),

    fk_supervisor BIGINT NOT NULL,
    FOREIGN KEY (fk_supervisor) REFERENCES supervisor (fk_id)
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
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (fk_id)
);

/* Descrição Estágio */

CREATE TABLE descricao_estagio (
    descricao VARCHAR(300) NOT NULL,
    data_assinatura date NOT NULL,
    natureza_estagio BOOLEAN DEFAULT FALSE, 
    assinatura_supervisor BOOLEAN DEFAULT FALSE,
    assinatura_estagiario BOOLEAN DEFAULT FALSE,

    fk_estagiario BIGINT NOT NULL,
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (fk_id)
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
    FOREIGN KEY (fk_estagiario) REFERENCES estagiario (fk_id)
);

/* DROP TABLE instituicao_de_ensino, informacoes_academicas, descricao_estagio, empresa, pessoas,  supervisor, dados_estagio, estagiario,
funcionario_empresa, funcionario_universidade,  orientador; */
