/* CREATE DATABASE docs; */

/* Pessoas */

CREATE TABLE person (
    id_person SERIAL PRIMARY KEY NOT NULL,
    cpf_person CHAR(14) UNIQUE,
    name_person VARCHAR(40),
    email_person VARCHAR(50) NOT NULL UNIQUE, 
    telephone_person CHAR(15), 
    birthday_person DATE,
    rg_person VARCHAR(12) UNIQUE,
    deleted BOOLEAN DEFAULT FALSE,
    deleted_date DATE DEFAULT CURRENT_DATE, 
    treatment_person VARCHAR(35) NOT NULL,   
    access_level INT NOT NULL,
    last_edition_date DATE DEFAULT CURRENT_DATE,
    who_edited VARCHAR(40) NOT NULL,
    who_invited VARCHAR(40),

    /*
    Aluno = 1
    Supervisor = 2
    Orientador = 3
    funcionario de empresa = 4
    funcionario de universidade = 5
    Diretor = 10
    */

    register_date DATE DEFAULT CURRENT_DATE,
    valid BOOLEAN DEFAULT FALSE

    /*fk_university BIGINT NOT NULL UNIQUE,
    FOREIGN KEY (fk_university) REFERENCES university (id_university)*/
);

/* Instituição de Ensino */

CREATE TABLE university (
    id_university SERIAL PRIMARY KEY NOT NULL, 
    cnpj_university CHAR(18) UNIQUE NOT NULL,
    name_university VARCHAR(100) NOT NULL,
    state_registration_university CHAR(13) NOT NULL,
    corporate_name_university VARCHAR(100) NOT NULL,
    legal_representative_university VARCHAR(100) NOT NULL,
    activity_branch_university VARCHAR(50) NOT NULL,
    address_university VARCHAR(100) NOT NULL,
    district_university VARCHAR(50) NOT NULL,
    cep_university CHAR(10) NOT NULL,
    mailbox_university CHAR(10),
    city_university VARCHAR(30) NOT NULL,
    state_university CHAR(4) NOT NULL, /* Sigla */
    telephone_university CHAR(15) NOT NULL, 
    deleted_date DATE DEFAULT CURRENT_DATE,
    valid BOOLEAN DEFAULT FALSE,
    last_edition_date DATE DEFAULT CURRENT_DATE,
    who_edited VARCHAR(40) NOT NULL,
    number_address_university CHAR(5) NOT NULL,
    home_page_university VARCHAR(50) NOT NULL,
    email_university VARCHAR(50) NOT NULL, 
    registration_date_university DATE DEFAULT CURRENT_DATE,

    fk_principal BIGINT NOT NULL UNIQUE,
    FOREIGN KEY (fk_principal) REFERENCES person (id_person)
); 

/* Empresa */

CREATE TABLE company (
    id_company SERIAL PRIMARY KEY NOT NULL, --
    cnpj_company CHAR(18) UNIQUE NOT NULL, 
    email_company VARCHAR(50) UNIQUE NOT NULL, --
    name_company VARCHAR(50) NOT NULL, --
    address_company VARCHAR(100) NOT NULL, --
    number_company VARCHAR(10) NOT NULL, --
    district_company VARCHAR(50) NOT NULL, --
    city_company VARCHAR(30) NOT NULL, --
    state_company CHAR(4) NOT NULL, /* Sigla */ --
    state_registration_company CHAR(15) NOT NULL, 
    cep_company CHAR(9) NOT NULL, --
    telephone_company CHAR(15) NOT NULL, --
    telephone2_company CHAR(15) NOT NULL, --
    /*contact_company VARCHAR(40) NOT NULL,*/
    section_company VARCHAR(50),
    function_company VARCHAR(30),
    branch_line_company VARCHAR(30) NOT NULL,  /*ramal*/
    legal_representative_company VARCHAR(100) NOT NULL,
    /*role_legal_representative_company VARCHAR(30) NOT NULL,*/
    activity_branch_company VARCHAR(50) NOT NULL,
    corporate_name_company VARCHAR(100) NOT NULL, /*razão social*/
    home_page_company VARCHAR(50) NOT NULL,
    mailbox_company CHAR(10) NOT NULL,
   -- fax_company 
    last_edition_date DATE DEFAULT CURRENT_DATE,
    who_edited VARCHAR(40) NOT NULL, /*isso aqui não pode ser uma chave estrangeira que chama a uma pessoa dentro da própria tabela pessoa?*/
    validated_adm BOOLEAN DEFAULT FALSE,
    deleted BOOLEAN DEFAULT FALSE,
    deleted_date DATE DEFAULT CURRENT_DATE,
    valid BOOLEAN DEFAULT FALSE
); 

/*
CREATE TABLE administrator (
    fk_id BIGINT NOT NULL UNIQUE,
    
    role_administrator VARCHAR(30) NOT NULL,
    cic_administrator CHAR(25) NOT NULL,
    deleted BOOLEAN DEFAULT FALSE,
    valid BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT FALSE,
    last_action VARCHAR(60),

    FOREIGN KEY (fk_id) REFERENCES person (id_person)
);
*/

CREATE TABLE action_history (
    id_action_history SERIAL PRIMARY KEY NOT NULL,
    fk_adm BIGINT NOT NULL UNIQUE, /*id do administrador*/
    fk_person BIGINT NOT NULL UNIQUE, /*id da pessoa que foi afetada*/
    action_num INT NOT NULL, /*1- adicionou 2- deletou 3- alterou*/ 

    FOREIGN KEY (fk_adm) REFERENCES person(id_person),
    FOREIGN KEY (fk_person) REFERENCES person(id_person)
);


/* Supervisor */
/* o bolo de queijo é forte com esse cara ae*/

CREATE TABLE supervisor (
    fk_id BIGINT NOT NULL UNIQUE, 
    role_supervisor VARCHAR(30) NOT NULL, 
    cic_supervisor CHAR(25) NOT NULL UNIQUE,
    deleted BOOLEAN DEFAULT FALSE,
    valid BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT FALSE,

    FOREIGN KEY (fk_id) REFERENCES person(id_person),

    fk_company BIGINT NOT NULL,
    FOREIGN KEY (fk_company) REFERENCES company (id_company)
); 

/* Funcionário setor de estágio (Empresa e universidade) */

/* Funcionário Empresa*/

CREATE TABLE company_employee (
    fk_id BIGINT NOT NULL UNIQUE,
    function_company_employee VARCHAR(30) NOT NULL, 
    role_company_employee VARCHAR(30) NOT NULL, 
    cic_company_employee CHAR(25) NOT NULL UNIQUE,
    deleted BOOLEAN DEFAULT FALSE, 
    valid BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (fk_id) REFERENCES person(id_person),

    fk_company BIGINT NOT NULL,
    FOREIGN KEY (fk_company) REFERENCES company (id_company)
);

/* Funcionário Universidade*/

CREATE TABLE university_employee (
    fk_id BIGINT NOT NULL UNIQUE,
    role_university_employee VARCHAR(30) NOT NULL,
    cic_university_employee CHAR(25) NOT NULL UNIQUE,
    business_sector_professor VARCHAR(50),
    active BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (fk_id) REFERENCES person(id_person),

    fk_university BIGINT NOT NULL,
    FOREIGN KEY (fk_university) REFERENCES university(id_university)
);

/* Orientador */

CREATE TABLE advisor (
    fk_id BIGINT NOT NULL UNIQUE,
    cic_advisor CHAR(25) NOT NULL UNIQUE, 
    department_advisor VARCHAR(50) NOT NULL, 
    deleted BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT FALSE,

    FOREIGN KEY (fk_id) REFERENCES person(id_person),

    fk_university BIGINT NOT NULL,
    FOREIGN KEY (fk_university) REFERENCES university(id_university)
);

/* Estagiário */

CREATE TABLE student (
    fk_id BIGINT NOT NULL UNIQUE,
    ra_student VARCHAR(20) UNIQUE NOT NULL,
    business_sector_student VARCHAR(50),
    period_student VARCHAR(15),
    deleted BOOLEAN DEFAULT FALSE,
    address_student VARCHAR(100) NOT NULL, --
    number_student VARCHAR(10) NOT NULL, 
    district_student VARCHAR(50) NOT NULL, --
    city_student VARCHAR(30) NOT NULL, --
    cep_student CHAR(9) NOT NULL, --


    /* Informações Acadêmicas */

    course_code_student INT NOT NULL,
    total_hours_student INT, 
    paid_hours_student INT,
    over_total_student NUMERIC(6,2), /* (horas_integralizadas / total_horas) * 100 */
    year_entry_student INT NOT NULL, 
    semester_observations_student INT,
    year_observations_student INT,
    /*data_assinatura date NOT NULL,*/
    
    FOREIGN KEY (fk_id) REFERENCES person(id_person),

    fk_university BIGINT NOT NULL,
    FOREIGN KEY (fk_university) REFERENCES university(id_university),

    fk_professor BIGINT NOT NULL,
    FOREIGN KEY (fk_professor) REFERENCES university_employee(fk_id),

    complement_student VARCHAR(100),

    monday TIME,
    tuesday TIME,
    wednesday TIME,
    thursday TIME,
    friday TIME,
    saturday TIME,
    end_monday TIME,
    end_tuesday TIME,
    end_wednesday TIME,
    end_thursday TIME,
    end_friday TIME,
    end_saturday TIME
); 

CREATE TABLE tokens (
    id SERIAL PRIMARY KEY,
    token TEXT NOT NULL,
    valid_date BIGINT DEFAULT 0
);

/* Dados Estágio */

CREATE TABLE internship_data (
    id_internship_data SERIAL PRIMARY KEY NOT NULL, 
    name_internship_data VARCHAR(140),
    role_internship_data VARCHAR(40), 
    course_internship_data VARCHAR(30) NOT NULL,
    area_internship_data VARCHAR(50),
    week_hours_internship_data INT,
    daily_hours INT,
    lunch_time TIME,
    start_date_internship_data DATE, -- DEFAULT CURRENT_DATE,
    end_date_internship_data DATE, -- DEFAULT CURRENT_DATE, 
    total_hours_internship_data INT,
    scholarship_internship_data BOOLEAN DEFAULT FALSE,
    scholarship_value_internship_data INT DEFAULT 0,
    /*transportation_assistance_internship_data INT DEFAULT 0,*/
    -- start_time_internship_data TIME,
    -- end_time_internship_data TIME,
    finished BOOLEAN DEFAULT FALSE,
    finished_date DATE,
    
    /* Descrição Estágio */

    description_internship_data VARCHAR(600),
    signature_date_internship_data DATE, -- DEFAULT CURRENT_DATE,
    nature_internship_data BOOLEAN DEFAULT FALSE, -- false = não obrigatório
    supervisor_signature_internship_data BOOLEAN DEFAULT FALSE,
    intern_signature_internship_data BOOLEAN DEFAULT FALSE,
    validated_company BOOLEAN DEFAULT FALSE,
    validated_advisor BOOLEAN DEFAULT FALSE,
    validated_coordinator BOOLEAN DEFAULT FALSE,

    fk_student BIGINT NOT NULL,
    FOREIGN KEY (fk_student) REFERENCES student (fk_id),

    fk_advisor BIGINT NOT NULL,
    FOREIGN KEY (fk_advisor) REFERENCES advisor (fk_id),

    fk_supervisor BIGINT,
    FOREIGN KEY (fk_supervisor) REFERENCES company_employee (fk_id),
    
    fk_company BIGINT NOT NULL,
    FOREIGN KEY (fk_company) REFERENCES company (id_company),

    valid BOOLEAN DEFAULT NULL
);

/*Relatórios*/

CREATE TABLE internship_reports(
    id_internship_reports SERIAL PRIMARY KEY NOT NULL,
    link_internship_report VARCHAR(100) NOT NULL,  
    date_internship_report DATE NOT NULL DEFAULT CURRENT_DATE,
    version_internship_report INT NOT NULL,
    type_internship_report VARCHAR(200) NOT NULL,
    denied_internship_report BOOLEAN DEFAULT FALSE,
    supervisor_signature_internship_report DATE, 
    --------------------------- se não foi assinado, o valor é nulo -----------------------------
    advisor_signature_internship_report DATE,
    coordinator_signature_internship_report DATE,

    fk_internship_data BIGINT NOT NULL,
    FOREIGN KEY (fk_internship_data) REFERENCES internship_data (id_internship_data) 
);

/*Proposta de Plano de Estágio*/

CREATE TABLE internship_plan(
    id_internship_plan SERIAL PRIMARY KEY NOT NULL,
    coordinator_opinion_internship_plan VARCHAR(400),
    supervisor_approval_internship_plan DATE, 
    advisor_approval_internship_plan DATE,
    coordinator_approval_internship_plan DATE,
    date_internship_plan DATE NOT NULL DEFAULT CURRENT_DATE,

    denied_internship_plan BOOLEAN DEFAULT FALSE,

    valid BOOLEAN,

    fk_internship_data BIGINT NOT NULL,
    FOREIGN KEY (fk_internship_data) REFERENCES internship_data (id_internship_data) 
);

 /* Alteração */

 CREATE TABLE change_data(
    allowed BOOLEAN DEFAULT FALSE NOT NULL,   
    pending_allowance BOOLEAN DEFAULT TRUE NOT NULL,  
    last_edition_date DATE DEFAULT CURRENT_DATE,   
    edited BOOLEAN DEFAULT FALSE NOT NULL, /* tornar o allowed FALSE e o eddited TRUE depois da edição já feita */
    blocked_edition BOOLEAN DEFAULT FALSE NOT NULL,

    fk_id INT NOT NULL,
    FOREIGN KEY(fk_id) REFERENCES person (id_person)
);

 CREATE TABLE change_data_universities(
    allowed BOOLEAN DEFAULT FALSE NOT NULL,   
    pending_allowance BOOLEAN DEFAULT TRUE NOT NULL,  
    last_edition_date DATE DEFAULT CURRENT_DATE,   
    edited BOOLEAN DEFAULT FALSE NOT NULL, /* tornar o allowed FALSE e o eddited TRUE depois da edição já feita */
    blocked_edition BOOLEAN DEFAULT FALSE NOT NULL,

    fk_id INT NOT NULL,
    FOREIGN KEY(fk_id) REFERENCES university (id_university)
);

CREATE TABLE change_data_companies(
    allowed BOOLEAN DEFAULT FALSE NOT NULL,   
    pending_allowance BOOLEAN DEFAULT TRUE NOT NULL,  
    last_edition_date DATE DEFAULT CURRENT_DATE,   
    edited BOOLEAN DEFAULT FALSE NOT NULL, /* tornar o allowed FALSE e o eddited TRUE depois da edição já feita */
    blocked_edition BOOLEAN DEFAULT FALSE NOT NULL,

    fk_id INT NOT NULL,
    FOREIGN KEY(fk_id) REFERENCES company (id_company)
);

 CREATE TABLE change_data_internship(
    allowed BOOLEAN DEFAULT FALSE NOT NULL,   
    pending_allowance BOOLEAN DEFAULT TRUE NOT NULL,  
    last_edition_date DATE DEFAULT CURRENT_DATE,   
    edited BOOLEAN DEFAULT FALSE NOT NULL, /* tornar o allowed FALSE e o eddited TRUE depois da edição já feita */
    blocked_edition BOOLEAN DEFAULT FALSE NOT NULL,

    fk_id INT NOT NULL,
    FOREIGN KEY(fk_id) REFERENCES internship_data (id_internship_data)
);

 /*
 DROP TABLE tokens, dados_estagio, aluno, orientador, funcionario_universidade, funcionario_empresa, supervisor, administrador, empresa, instituicao_de_ensino, pessoas
*/


