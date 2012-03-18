/*

DROP FUNCTION fn_basphysicalpersonstudent_tgr() CASCADE;
DROP FUNCTION fn_basphysicalpersonprofessor_tgr() CASCADE;
DROP FUNCTION fn_acdgroup_tgr() CASCADE;
DROP FUNCTION fn_acdenroll_tgr() CASCADE;

DROP TABLE basphysicalpersonstudent_esp;
DROP TABLE basphysicalpersonprofessor_esp;
DROP TABLE acdgroup_esp;
DROP TABLE acdenroll_esp;

*/

CREATE TABLE basphysicalpersonstudent_esp (
	id              BIGSERIAL NOT NULL,
	id_fk           INTEGER,
	operacao        CHAR(1),
	confirmado		BOOLEAN DEFAULT false
);

CREATE TABLE basphysicalpersonprofessor_esp (
	id              BIGSERIAL NOT NULL,
	id_fk           INTEGER,
	operacao        CHAR(1),
	confirmado		BOOLEAN DEFAULT false
);
 
CREATE TABLE acdgroup_esp (
	id              BIGSERIAL NOT NULL,
	id_fk           INTEGER,
	operacao        CHAR(1),
	confirmado		BOOLEAN DEFAULT false
);
 
CREATE TABLE acdenroll_esp (
	id              BIGSERIAL NOT NULL,
	id_fk           INTEGER,
	operacao        CHAR(1),
	confirmado		BOOLEAN DEFAULT false
);

-- **************************************************************************************** --

CREATE OR REPLACE FUNCTION fn_basphysicalpersonstudent_tgr() RETURNS TRIGGER AS $basphysicalpersonstudent_esp$
DECLARE
	operacao CHAR(1);
	id_fk    INTEGER;

    BEGIN
        operacao := SUBSTR(TG_OP, 1, 1);
		IF operacao = 'D' THEN
		   id_fk := OLD.personid;
		ELSE
		   id_fk := NEW.personid;
		END IF;

        -- Inserir linhas na tabela espelho para refletir alterações da tabela base
		-- Usamos nextval, pois na versao 8.2, o postgres nao busca esta id automaticamente
		INSERT INTO basphysicalpersonstudent_esp SELECT nextval('basphysicalpersonstudent_esp_id_seq'), id_fk, operacao;
        RETURN NULL; -- o resultado é ignorado uma vez que este é um gatilho AFTER
    END;
$basphysicalpersonstudent_esp$ LANGUAGE plpgsql;
 
CREATE TRIGGER basphysicalpersonstudent_tgr
	AFTER UPDATE OR DELETE OR INSERT ON basphysicalpersonstudent
		FOR EACH ROW EXECUTE PROCEDURE fn_basphysicalpersonstudent_tgr(); 

-- **************************************************************************************** --

CREATE OR REPLACE FUNCTION fn_basphysicalpersonprofessor_tgr() RETURNS TRIGGER AS $basphysicalpersonprofessor_esp$
DECLARE
	operacao CHAR(1);
	id_fk    INTEGER;

    BEGIN
        operacao := SUBSTR(TG_OP, 1, 1);
		IF operacao = 'D' THEN
		   id_fk := OLD.personid;
		ELSE
		   id_fk := NEW.personid;
		END IF;

        -- Inserir linhas na tabela espelho para refletir alterações da tabela base
		-- Usamos nextval, pois na versao 8.2, o postgres nao busca esta id automaticamente
		INSERT INTO basphysicalpersonprofessor_esp SELECT nextval('basphysicalpersonprofessor_esp_id_seq'), id_fk, operacao;
        RETURN NULL; -- o resultado é ignorado uma vez que este é um gatilho AFTER
    END;
$basphysicalpersonprofessor_esp$ LANGUAGE plpgsql;
 
CREATE TRIGGER basphysicalpersonprofessor_tgr
	AFTER UPDATE OR DELETE OR INSERT ON basphysicalpersonprofessor
		FOR EACH ROW EXECUTE PROCEDURE fn_basphysicalpersonprofessor_tgr(); 

-- **************************************************************************************** --

CREATE OR REPLACE FUNCTION fn_acdgroup_tgr() RETURNS TRIGGER AS $acdgroup_esp$
DECLARE
	operacao CHAR(1);
	id_fk    INTEGER;

    BEGIN
        operacao := SUBSTR(TG_OP, 1, 1);
		IF operacao = 'D' THEN
		   id_fk := OLD.groupid;
		ELSE
		   id_fk := NEW.groupid;
		END IF;

        -- Inserir linhas na tabela espelho para refletir alterações da tabela base
		-- Usamos nextval, pois na versao 8.2, o postgres nao busca esta id automaticamente
		INSERT INTO acdgroup_esp SELECT nextval('acdgroup_esp_id_seq'), id_fk, operacao;
        RETURN NULL; -- o resultado é ignorado uma vez que este é um gatilho AFTER
    END;
$acdgroup_esp$ LANGUAGE plpgsql;
 
CREATE TRIGGER acdgroup_tgr
	AFTER UPDATE OR DELETE OR INSERT ON acdgroup
		FOR EACH ROW EXECUTE PROCEDURE fn_acdgroup_tgr(); 

-- **************************************************************************************** --

CREATE OR REPLACE FUNCTION fn_acdenroll_tgr() RETURNS TRIGGER AS $acdenroll_esp$
DECLARE
	operacao CHAR(1);
	id_fk    INTEGER;

    BEGIN
        operacao := SUBSTR(TG_OP, 1, 1);
		IF operacao = 'D' THEN
		   id_fk := OLD.enrollid;
		ELSE
		   id_fk := NEW.enrollid;
		END IF;

        -- Inserir linhas na tabela espelho para refletir alterações da tabela base
		-- Usamos nextval, pois na versao 8.2, o postgres nao busca esta id automaticamente
		INSERT INTO acdenroll_esp SELECT nextval('acdenroll_esp_id_seq'), id_fk, operacao;
        RETURN NULL; -- o resultado é ignorado uma vez que este é um gatilho AFTER
    END;
$acdenroll_esp$ LANGUAGE plpgsql;
 
CREATE TRIGGER acdenroll_tgr
	AFTER UPDATE OR DELETE OR INSERT ON acdenroll
		FOR EACH ROW EXECUTE PROCEDURE fn_acdenroll_tgr(); 


-- trigger da atualização de senhas do sagu
CREATE OR REPLACE FUNCTION fn_miolo_user_tgr()
  RETURNS trigger AS
$BODY$
DECLARE
	operacao CHAR(1);
	id_aluno int;
	id_prof int;	

    BEGIN
        operacao := 'U';

	SELECT s.personid INTO id_aluno FROM miolo_user m inner join basphysicalpersonstudent s on lower(s.miolousername) = lower(m.login) 
		where m.iduser = NEW.iduser;

	SELECT p.personid INTO id_prof FROM miolo_user m inner join basphysicalpersonprofessor p on lower(p.miolousername) = lower(m.login) 
		where m.iduser = NEW.iduser;


	IF id_aluno is not null THEN
		INSERT INTO basphysicalpersonstudent_esp SELECT nextval('basphysicalpersonstudent_esp_id_seq'), id_aluno, operacao;
	END IF;

	IF id_prof is not null THEN
		INSERT INTO basphysicalpersonprofessor_esp SELECT nextval('basphysicalpersonstudent_esp_id_seq'), id_prof, operacao;
	END IF;


	RETURN NULL;

    END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION fn_miolo_user_tgr() OWNER TO postgres;

CREATE TRIGGER miolo_user_tgr
  AFTER UPDATE 
  ON miolo_user
  FOR EACH ROW
  EXECUTE PROCEDURE fn_miolo_user_tgr();

-- **************************************************************************************** --

GRANT ALL ON TABLE basphysicalpersonstudent_esp TO postgres;
GRANT ALL ON TABLE basphysicalpersonprofessor_esp TO postgres;
GRANT ALL ON TABLE acdgroup_esp TO postgres;
GRANT ALL ON TABLE acdenroll_esp TO postgres;

GRANT ALL ON TABLE basphysicalpersonstudent_esp_id_seq TO postgres;
GRANT ALL ON TABLE basphysicalpersonprofessor_esp_id_seq TO postgres;
GRANT ALL ON TABLE acdgroup_esp_id_seq TO postgres;
GRANT ALL ON TABLE acdenroll_esp_id_seq TO postgres;

-- **************************************************************************************** --

COMMENT ON TABLE basphysicalpersonstudent_esp IS 'Espelho da basphysicalpersonstudent para migração Miidle';
COMMENT ON TABLE basphysicalpersonprofessor_esp IS 'Espelho da basphysicalpersonprofessor para migração Miidle';
COMMENT ON TABLE acdgroup_esp IS 'Espelho da acdgroup para migração Miidle';
COMMENT ON TABLE acdenroll_esp IS 'Espelho da acdenroll para migração Miidle';

COMMENT ON COLUMN basphysicalpersonstudent_esp.id IS 'Chave única da operação';
COMMENT ON COLUMN basphysicalpersonstudent_esp.id_fk IS 'Chave do registro afetado na tabela monitorada';
COMMENT ON COLUMN basphysicalpersonstudent_esp.operacao IS 'Tipo de operação (I)nsert, (U)pdate, (D)elete';

COMMENT ON COLUMN basphysicalpersonprofessor_esp.id IS 'Chave única da operação';
COMMENT ON COLUMN basphysicalpersonprofessor_esp.id_fk IS 'Chave do registro afetado na tabela monitorada';
COMMENT ON COLUMN basphysicalpersonprofessor_esp.operacao IS 'Tipo de operação (I)nsert, (U)pdate, (D)elete';

COMMENT ON COLUMN acdgroup_esp.id IS 'Chave única da operação';
COMMENT ON COLUMN acdgroup_esp.id_fk IS 'Chave do registro afetado na tabela monitorada';
COMMENT ON COLUMN acdgroup_esp.operacao IS 'Tipo de operação (I)nsert, (U)pdate, (D)elete';

COMMENT ON COLUMN acdenroll_esp.id IS 'Chave única da operação';
COMMENT ON COLUMN acdenroll_esp.id_fk IS 'Chave do registro afetado na tabela monitorada';
COMMENT ON COLUMN acdenroll_esp.operacao IS 'Tipo de operação (I)nsert, (U)pdate, (D)elete';



CREATE ROLE isagu LOGIN PASSWORD 'isagup455w0rd';

--tabelas
--select 'GRANT ALL ON ' || table_name || ' TO isagu;' FROM information_schema.tables where table_schema = 'public'

--sequences:
--select 'GRANT ALL ON ' ||  c.relname || ' TO isagu;'  FROM pg_catalog.pg_class c, pg_catalog.pg_user u, pg_catalog.pg_namespace n WHERE c.relowner=u.usesysid AND c.relnamespace=n.oid AND c.relkind = 'S' AND n.nspname='public' 


GRANT ALL ON baslog TO isagu;
GRANT ALL ON accaccountinglimit TO isagu;
GRANT ALL ON acccoursebalance TO isagu;
GRANT ALL ON accintegration TO isagu;
GRANT ALL ON accentryintegration TO isagu;
GRANT ALL ON accincomeforecastintegration TO isagu;
GRANT ALL ON accpersonbalance TO isagu;
GRANT ALL ON acdacademiccalendarevent TO isagu;
GRANT ALL ON acdcenter TO isagu;
GRANT ALL ON acdcertified TO isagu;
GRANT ALL ON acdclass TO isagu;
GRANT ALL ON acdclasspupil TO isagu;
GRANT ALL ON acdcomplementaryactivities TO isagu;
GRANT ALL ON acdcomplementaryactivitiescategoryrules TO isagu;
GRANT ALL ON acdcomplementaryenroll TO isagu;
GRANT ALL ON acdconcept TO isagu;
GRANT ALL ON acdconceptgroup TO isagu;
GRANT ALL ON acdcondition TO isagu;
GRANT ALL ON acdcontractexaminingboard TO isagu;
GRANT ALL ON acdcourse TO isagu;
GRANT ALL ON acdcourseability TO isagu;
GRANT ALL ON acdcoursecoordinator TO isagu;
GRANT ALL ON acdcourseparent TO isagu;
GRANT ALL ON acdcourseversiontype TO isagu;
GRANT ALL ON acdcurricularcomponentgroup TO isagu;
GRANT ALL ON acdcurricularcomponenttype TO isagu;
GRANT ALL ON acdcurricularcomponentunblock TO isagu;
GRANT ALL ON acdcurriculumconcurrence TO isagu;
GRANT ALL ON acdcurriculumlink TO isagu;
GRANT ALL ON acdcurriculumtype TO isagu;
GRANT ALL ON acddegree TO isagu;
GRANT ALL ON acddegreecurricularcomponentgroup TO isagu;
GRANT ALL ON acddegreeenroll TO isagu;
GRANT ALL ON acddiploma TO isagu;
GRANT ALL ON acdeducationarea TO isagu;
GRANT ALL ON acdelection TO isagu;
GRANT ALL ON acdenadestatus TO isagu;
GRANT ALL ON acdenrollconfig TO isagu;
GRANT ALL ON acdenrollsummary TO isagu;
GRANT ALL ON acdevaluationcontrolmethod TO isagu;
GRANT ALL ON acdevaluationenroll TO isagu;
GRANT ALL ON acdevaluationtype TO isagu;
GRANT ALL ON acdeventparticipation TO isagu;
GRANT ALL ON acdexamdate TO isagu;
GRANT ALL ON acdexploitation TO isagu;
GRANT ALL ON acdfinalexaminationdirectors TO isagu;
GRANT ALL ON acdfinalexaminationexaminingboard TO isagu;
GRANT ALL ON acdfinalexaminationknowledgearea TO isagu;
GRANT ALL ON acdformationlevel TO isagu;
GRANT ALL ON acdfrequenceenroll TO isagu;
GRANT ALL ON acdgroup TO isagu;
GRANT ALL ON acdinterchange TO isagu;
GRANT ALL ON acdknowledgearea TO isagu;
GRANT ALL ON acdmessagecontractrenewal TO isagu;
GRANT ALL ON acdmoodlesubscription TO isagu;
GRANT ALL ON acdmovementcontractcomplement TO isagu;
GRANT ALL ON acdperiod TO isagu;
GRANT ALL ON acdperiodenrolldate TO isagu;
GRANT ALL ON acdprofessorcenter TO isagu;
GRANT ALL ON acdprofessorcurricularcomponent TO isagu;
GRANT ALL ON acdprofessorformation TO isagu;
GRANT ALL ON acdproject TO isagu;
GRANT ALL ON acdreasoncancellation TO isagu;
GRANT ALL ON acdregimen TO isagu;
GRANT ALL ON acdrestricteddocuments TO isagu;
GRANT ALL ON acdscheduleprofessorcontent TO isagu;
GRANT ALL ON acdstatecontractfield TO isagu;
GRANT ALL ON acdstateenrollbook TO isagu;
GRANT ALL ON acdstateenrollbookrules TO isagu;
GRANT ALL ON acdstatetransition TO isagu;
GRANT ALL ON acdtestendcoursecontract TO isagu;
GRANT ALL ON acdtestendcoursetype TO isagu;
GRANT ALL ON acdtime TO isagu;
GRANT ALL ON acdtrainingdetail TO isagu;
GRANT ALL ON acdtrainingemphasis TO isagu;
GRANT ALL ON acdviewmoodlesubscription TO isagu;
GRANT ALL ON basaccess TO isagu;
GRANT ALL ON basbadge TO isagu;
GRANT ALL ON basbadgeloan TO isagu;
GRANT ALL ON basbadgestatus TO isagu;
GRANT ALL ON bascitysquare TO isagu;
GRANT ALL ON bascompanyconf TO isagu;
GRANT ALL ON basconfig TO isagu;
GRANT ALL ON bascountry TO isagu;
GRANT ALL ON bascvslog TO isagu;
GRANT ALL ON basdocument TO isagu;
GRANT ALL ON basemail TO isagu;
GRANT ALL ON basemployee TO isagu;
GRANT ALL ON basethnicorigin TO isagu;
GRANT ALL ON baskinship TO isagu;
GRANT ALL ON baslegalperson TO isagu;
GRANT ALL ON baslegalpersontype TO isagu;
GRANT ALL ON baslocation TO isagu;
GRANT ALL ON baslocationtype TO isagu;
GRANT ALL ON basmailserver TO isagu;
GRANT ALL ON basmaritalstatus TO isagu;
GRANT ALL ON baspersonlink TO isagu;
GRANT ALL ON baspersontitle TO isagu;
GRANT ALL ON basphysicalperson TO isagu;
GRANT ALL ON basphysicalpersonemployee TO isagu;
GRANT ALL ON basphysicalpersonkinship TO isagu;
GRANT ALL ON basphysicalpersonstudent TO isagu;
GRANT ALL ON basprofessionalactivity TO isagu;
GRANT ALL ON basprofessionalactivitypeople TO isagu;
GRANT ALL ON basprofessorcommitment TO isagu;
GRANT ALL ON basreport TO isagu;
GRANT ALL ON basreportparameter TO isagu;
GRANT ALL ON basresetpassword TO isagu;
GRANT ALL ON bassectorboss TO isagu;
GRANT ALL ON basspecialnecessity TO isagu;
GRANT ALL ON basstamp TO isagu;
GRANT ALL ON bastask TO isagu;
GRANT ALL ON bastaskhistory TO isagu;
GRANT ALL ON basupdate TO isagu;
GRANT ALL ON basweekday TO isagu;
GRANT ALL ON ccpcopy TO isagu;
GRANT ALL ON ccppayrolldiscount TO isagu;
GRANT ALL ON ccpperson TO isagu;
GRANT ALL ON ccppersoncopy TO isagu;
GRANT ALL ON ccppersonperiod TO isagu;
GRANT ALL ON ccppersonprinter TO isagu;
GRANT ALL ON ccppersonsector TO isagu;
GRANT ALL ON ccpprinter TO isagu;
GRANT ALL ON ccprequest TO isagu;
GRANT ALL ON ccprequestfax TO isagu;
GRANT ALL ON ccprule TO isagu;
GRANT ALL ON ccpsectorcopy TO isagu;
GRANT ALL ON ccpsectorperiod TO isagu;
GRANT ALL ON ccpsectorprinter TO isagu;
GRANT ALL ON ccpservice TO isagu;
GRANT ALL ON finagreementcomments TO isagu;
GRANT ALL ON finbankaccount TO isagu;
GRANT ALL ON finbankinvoiceinfo TO isagu;
GRANT ALL ON fininvoicetarget TO isagu;
GRANT ALL ON finbanktarget TO isagu;
GRANT ALL ON finclosecounter TO isagu;
GRANT ALL ON fincollectiontype TO isagu;
GRANT ALL ON finconvenantperson TO isagu;
GRANT ALL ON fincounter TO isagu;
GRANT ALL ON fincountermovement TO isagu;
GRANT ALL ON findefaultoperations TO isagu;
GRANT ALL ON finemissiontype TO isagu;
GRANT ALL ON finenrollfee TO isagu;
GRANT ALL ON finentry TO isagu;
GRANT ALL ON finfile TO isagu;
GRANT ALL ON basfile TO isagu;
GRANT ALL ON fininvoicemessage TO isagu;
GRANT ALL ON finfinancialaid TO isagu;
GRANT ALL ON finincomeforecast TO isagu;
GRANT ALL ON fininvoicelog TO isagu;
GRANT ALL ON fininvoicemessagetype TO isagu;
GRANT ALL ON fininvoicespecie TO isagu;
GRANT ALL ON fininvoicetype TO isagu;
GRANT ALL ON finloan TO isagu;
GRANT ALL ON finoccurrenceoperation TO isagu;
GRANT ALL ON finopencounter TO isagu;
GRANT ALL ON finoperationgroup TO isagu;
GRANT ALL ON finpayableinvoice TO isagu;
GRANT ALL ON fininvoice TO isagu;
GRANT ALL ON finpayrolldiscounttarget TO isagu;
GRANT ALL ON finpersoninformation TO isagu;
GRANT ALL ON finphysicaltarget TO isagu;
GRANT ALL ON finpolicydiscount TO isagu;
GRANT ALL ON finpricepolicy TO isagu;
GRANT ALL ON finreceivableinvoice TO isagu;
GRANT ALL ON finreceivableinvoicecommunication TO isagu;
GRANT ALL ON finrelease TO isagu;
GRANT ALL ON finspcmovement TO isagu;
GRANT ALL ON finstudentfinancing TO isagu;
GRANT ALL ON finsupport TO isagu;
GRANT ALL ON finvouchermessages TO isagu;
GRANT ALL ON insareatype TO isagu;
GRANT ALL ON insgrouptype TO isagu;
GRANT ALL ON insmaterialstate TO isagu;
GRANT ALL ON insmaterialtype TO isagu;
GRANT ALL ON instombo TO isagu;
GRANT ALL ON instombostate TO isagu;
GRANT ALL ON miolo_access TO isagu;
GRANT ALL ON miolo_group TO isagu;
GRANT ALL ON miolo_groupuser TO isagu;
GRANT ALL ON miolo_log TO isagu;
GRANT ALL ON miolo_module TO isagu;
GRANT ALL ON miolo_sequence TO isagu;
GRANT ALL ON miolo_session TO isagu;
GRANT ALL ON miolo_transaction TO isagu;
GRANT ALL ON miolo_user TO isagu;
GRANT ALL ON nova_transaction TO isagu;
GRANT ALL ON rshform TO isagu;
GRANT ALL ON rshquestion TO isagu;
GRANT ALL ON rshquestioncategory TO isagu;
GRANT ALL ON rshwho TO isagu;
GRANT ALL ON spranswers TO isagu;
GRANT ALL ON spranswersheet TO isagu;
GRANT ALL ON sprcourseexambalance TO isagu;
GRANT ALL ON sprcourseoccurrence TO isagu;
GRANT ALL ON sprexam TO isagu;
GRANT ALL ON sprinscriptionoption TO isagu;
GRANT ALL ON sprinscriptionsetting TO isagu;
GRANT ALL ON sprinscriptionstatus TO isagu;
GRANT ALL ON sprlanguage TO isagu;
GRANT ALL ON sprlanguageoccurrence TO isagu;
GRANT ALL ON sprnote TO isagu;
GRANT ALL ON sprothersattleofmatter TO isagu;
GRANT ALL ON sprplace TO isagu;
GRANT ALL ON sprplaceoccurrence TO isagu;
GRANT ALL ON sprplaceroom TO isagu;
GRANT ALL ON sprsattleofmatter TO isagu;
GRANT ALL ON sprselectiveprocessoccurrence TO isagu;
GRANT ALL ON sprselectiveprocesstypedata TO isagu;
GRANT ALL ON user_sagu TO isagu;
GRANT ALL ON acdschedule TO isagu;
GRANT ALL ON finprice TO isagu;
GRANT ALL ON accaccountbalance TO isagu;
GRANT ALL ON acdreason TO isagu;
GRANT ALL ON acdcomplementaryactivitiescategory TO isagu;
GRANT ALL ON acdinterchangetype TO isagu;
GRANT ALL ON acdenroll TO isagu;
GRANT ALL ON acdcontract TO isagu;
GRANT ALL ON acdenrollstatus TO isagu;
GRANT ALL ON basstate TO isagu;
GRANT ALL ON acccostcenter TO isagu;
GRANT ALL ON acccourseaccount TO isagu;
GRANT ALL ON finbankaccountinvoiceinfo TO isagu;
GRANT ALL ON acdevaluation TO isagu;
GRANT ALL ON sprinscription TO isagu;
GRANT ALL ON acdcourseversion TO isagu;
GRANT ALL ON acdcurricularcomponent TO isagu;
GRANT ALL ON basunit TO isagu;
GRANT ALL ON acdcurriculum TO isagu;
GRANT ALL ON finoperation TO isagu;
GRANT ALL ON acdsubclass TO isagu;
GRANT ALL ON acdstatecontract TO isagu;
GRANT ALL ON basperson TO isagu;
GRANT ALL ON acdcourseoccurrence TO isagu;
GRANT ALL ON acdacademiccalendar TO isagu;
GRANT ALL ON basphysicalpersonprofessor TO isagu;
GRANT ALL ON acdcertifiedtype TO isagu;
GRANT ALL ON acdenrollbookdata TO isagu;
GRANT ALL ON acdlearningperiod TO isagu;
GRANT ALL ON basdocumenttype TO isagu;
GRANT ALL ON basturn TO isagu;
GRANT ALL ON acdexternalcourse TO isagu;
GRANT ALL ON finincomesource TO isagu;
GRANT ALL ON acdevent TO isagu;
GRANT ALL ON admtransaction TO isagu;
GRANT ALL ON insphysicalresource TO isagu;
GRANT ALL ON acdmovementcontract TO isagu;
GRANT ALL ON finbank TO isagu;
GRANT ALL ON acdscheduleprofessor TO isagu;
GRANT ALL ON basprofessionalactivitylinktype TO isagu;
GRANT ALL ON acdtimesheet TO isagu;
GRANT ALL ON basemployeetype TO isagu;
GRANT ALL ON baslink TO isagu;
GRANT ALL ON basneighborhood TO isagu;
GRANT ALL ON basprofessionalactivityagent TO isagu;
GRANT ALL ON bastaskstatus TO isagu;
GRANT ALL ON bascity TO isagu;
GRANT ALL ON sprexamoccurrence TO isagu;
GRANT ALL ON ccpperiod TO isagu;
GRANT ALL ON ccpsector TO isagu;
GRANT ALL ON bassector TO isagu;
GRANT ALL ON finspc TO isagu;
GRANT ALL ON finspcreason TO isagu;
GRANT ALL ON finbankaccountmovement TO isagu;
GRANT ALL ON finconvenant TO isagu;
GRANT ALL ON finspecies TO isagu;
GRANT ALL ON finincentivetype TO isagu;
GRANT ALL ON insmaterial TO isagu;
GRANT ALL ON finincentive TO isagu;
GRANT ALL ON finpolicy TO isagu;
GRANT ALL ON finreasoncancellation TO isagu;
GRANT ALL ON insitemphysicalresource TO isagu;
GRANT ALL ON accaccountscheme TO isagu;
GRANT ALL ON finpayableinvoicestatus TO isagu;
GRANT ALL ON rshanswer TO isagu;
GRANT ALL ON rshoption TO isagu;
GRANT ALL ON rshanswertype TO isagu;
GRANT ALL ON sprselectiveprocess TO isagu;
GRANT ALL ON sprcoursevacant TO isagu;
GRANT ALL ON sprselectiveprocesstype TO isagu;
GRANT ALL ON acdclass_esp TO isagu;
GRANT ALL ON acdgroup_esp TO isagu;
GRANT ALL ON acdenroll_esp TO isagu;
GRANT ALL ON basphysicalpersonstudent_esp TO isagu;
GRANT ALL ON basphysicalpersonprofessor_esp TO isagu;




GRANT ALL ON seq_accountinglimitid TO isagu;
GRANT ALL ON seq_accountreduced TO isagu;
GRANT ALL ON seq_coursebalanceid TO isagu;
GRANT ALL ON seq_accintegrationid TO isagu;
GRANT ALL ON seq_certifiedtypeid TO isagu;
GRANT ALL ON seq_personbalanceid TO isagu;
GRANT ALL ON acdacademiccalendarevent_academiccalendareventid_seq TO isagu;
GRANT ALL ON seq_centerid TO isagu;
GRANT ALL ON acdcertified_certifiedid TO isagu;
GRANT ALL ON seq_complementaryactivitiesid TO isagu;
GRANT ALL ON seq_complementaryactivitiescategoryid TO isagu;
GRANT ALL ON seq_complementaryactivitiescategoryrulesid TO isagu;
GRANT ALL ON seq_complementaryenrollid TO isagu;
GRANT ALL ON acdconcept_conceptid_seq TO isagu;
GRANT ALL ON acdconceptgroup_conceptgroupid_seq TO isagu;
GRANT ALL ON seq_conditionid TO isagu;
GRANT ALL ON seq_contractid TO isagu;
GRANT ALL ON seq_contractexaminingboardid TO isagu;
GRANT ALL ON seq_courseabilityid TO isagu;
GRANT ALL ON seq_courseparentid TO isagu;
GRANT ALL ON seq_courseversiontypeid TO isagu;
GRANT ALL ON seq_curricularcomponentgroupid TO isagu;
GRANT ALL ON seq_curricularcomponenttypeid TO isagu;
GRANT ALL ON seq_curricularcomponentunblockid TO isagu;
GRANT ALL ON seq_curriculumid TO isagu;
GRANT ALL ON seq_curriculumtypeid TO isagu;
GRANT ALL ON seq_degreeid TO isagu;
GRANT ALL ON acddegreecurricularcomponentg_degreecurricularcomponentgrou_seq TO isagu;
GRANT ALL ON seq_degreeenrollid TO isagu;
GRANT ALL ON seq_educationareaid TO isagu;
GRANT ALL ON seq_enadestatusid TO isagu;
GRANT ALL ON seq_enrollid TO isagu;
GRANT ALL ON seq_enrollconfigid TO isagu;
GRANT ALL ON seq_statusid TO isagu;
GRANT ALL ON seq_enrollsummaryid TO isagu;
GRANT ALL ON seq_evaluationid TO isagu;
GRANT ALL ON acdevaluationcontrolmethod_evaluationcontrolmethodid_seq TO isagu;
GRANT ALL ON seq_evaluationenrollid TO isagu;
GRANT ALL ON acdevaluationtype_evaluationtypeid_seq TO isagu;
GRANT ALL ON seq_eventid TO isagu;
GRANT ALL ON seq_exploitationid TO isagu;
GRANT ALL ON seq_externalcourseid TO isagu;
GRANT ALL ON seq_formationlevelid TO isagu;
GRANT ALL ON seq_groupid TO isagu;
GRANT ALL ON seq_interchangetypeid TO isagu;
GRANT ALL ON seq_interchangeid TO isagu;
GRANT ALL ON seq_knowledgeareaid TO isagu;
GRANT ALL ON seq_learningperiodid TO isagu;
GRANT ALL ON seq_messagecontractrenewalid TO isagu;
GRANT ALL ON seq_periodenrolldateid TO isagu;
GRANT ALL ON seq_professorcurricularcomponentid TO isagu;
GRANT ALL ON seq_projectid TO isagu;
GRANT ALL ON seq_reasonid TO isagu;
GRANT ALL ON seq_reasoncancellationid TO isagu;
GRANT ALL ON seq_regimenid TO isagu;
GRANT ALL ON seq_restricteddocumentid TO isagu;
GRANT ALL ON seq_scheduleid TO isagu;
GRANT ALL ON seq_scheduleprofessorid TO isagu;
GRANT ALL ON acdscheduleprofessorcontent_scheduleprofessorcontentid_seq TO isagu;
GRANT ALL ON seq_statecontractid TO isagu;
GRANT ALL ON seq_statecontractfieldid TO isagu;
GRANT ALL ON seq_stateenrollbookid TO isagu;
GRANT ALL ON seq_stateenrollbookrulesid TO isagu;
GRANT ALL ON seq_testendcoursetypeid TO isagu;
GRANT ALL ON seq_acdtime_timeid TO isagu;
GRANT ALL ON seq_acdtimesheet_timesheetid TO isagu;
GRANT ALL ON acdtrainingdetail_trainingdetailid_seq TO isagu;
GRANT ALL ON acdtrainingemphasis_trainingemphasisid_seq TO isagu;
GRANT ALL ON basbadgeloan_loanid_seq TO isagu;
GRANT ALL ON basbadgestatus_badgestatusid_seq TO isagu;
GRANT ALL ON seq_cityid TO isagu;
GRANT ALL ON seq_companyid TO isagu;
GRANT ALL ON seq_cvslogid TO isagu;
GRANT ALL ON seq_documenttypeid TO isagu;
GRANT ALL ON seq_emailid TO isagu;
GRANT ALL ON seq_employeeid TO isagu;
GRANT ALL ON seq_employeetypeid TO isagu;
GRANT ALL ON seq_ethnicoriginid TO isagu;
GRANT ALL ON seq_fileid TO isagu;
GRANT ALL ON baskinship_kinshipid_seq TO isagu;
GRANT ALL ON seq_personid TO isagu;
GRANT ALL ON seq_legalpersontypeid TO isagu;
GRANT ALL ON seq_linkid TO isagu;
GRANT ALL ON seq_locationid TO isagu;
GRANT ALL ON seq_locationtypeid TO isagu;
GRANT ALL ON seq_neighborhoodid TO isagu;
GRANT ALL ON seq_persontitleid TO isagu;
GRANT ALL ON seq_professionalactivityid TO isagu;
GRANT ALL ON seq_professionalactivityagentid TO isagu;
GRANT ALL ON seq_professionalactivitylinktypeid TO isagu;
GRANT ALL ON seq_professionalactivitypeopleid TO isagu;
GRANT ALL ON seq_reportid TO isagu;
GRANT ALL ON seq_reportparameterid TO isagu;
GRANT ALL ON basresetpassword_resetpasswordid_seq TO isagu;
GRANT ALL ON seq_sectorid TO isagu;
GRANT ALL ON seq_bossid TO isagu;
GRANT ALL ON seq_specialnecessityid TO isagu;
GRANT ALL ON seq_stampid TO isagu;
GRANT ALL ON bastask_taskid_seq TO isagu;
GRANT ALL ON bastaskhistory_taskhistoryid_seq TO isagu;
GRANT ALL ON bastaskstatus_taskstatusid_seq TO isagu;
GRANT ALL ON seq_turnid TO isagu;
GRANT ALL ON seq_unitid TO isagu;
GRANT ALL ON seq_copyid TO isagu;
GRANT ALL ON seq_payrolldiscountid TO isagu;
GRANT ALL ON seq_periodid TO isagu;
GRANT ALL ON seq_personperiodid TO isagu;
GRANT ALL ON seq_personprinterid TO isagu;
GRANT ALL ON seq_sectorpersonid TO isagu;
GRANT ALL ON seq_printerid TO isagu;
GRANT ALL ON seq_requestid TO isagu;
GRANT ALL ON seq_ruleid TO isagu;
GRANT ALL ON seq_sectorperiodid TO isagu;
GRANT ALL ON seq_personsectorid TO isagu;
GRANT ALL ON seq_serviceid TO isagu;
GRANT ALL ON seq_agreementcommentsid TO isagu;
GRANT ALL ON seq_bankaccountid TO isagu;
GRANT ALL ON seq_countermovementid TO isagu;
GRANT ALL ON seq_closecounterid TO isagu;
GRANT ALL ON seq_collectiontypeid TO isagu;
GRANT ALL ON finconvenant_convenantid_seq TO isagu;
GRANT ALL ON finconvenantperson_convenantpersonid_seq TO isagu;
GRANT ALL ON seq_counterid TO isagu;
GRANT ALL ON seq_emissiontype TO isagu;
GRANT ALL ON seq_entryid TO isagu;
GRANT ALL ON seq_incentivetypeid TO isagu;
GRANT ALL ON seq_incentiveid TO isagu;
GRANT ALL ON seq_incomeforecastid TO isagu;
GRANT ALL ON seq_incomesourceid TO isagu;
GRANT ALL ON seq_invoiceid TO isagu;
GRANT ALL ON seq_invoicemessagetypeid TO isagu;
GRANT ALL ON fininvoicetype_invoicetypeid_seq TO isagu;
GRANT ALL ON seq_occurrenceoperationid TO isagu;
GRANT ALL ON finopencounter_opencounterid_seq TO isagu;
GRANT ALL ON seq_operationid TO isagu;
GRANT ALL ON seq_policyid TO isagu;
GRANT ALL ON finpolicydiscount_discountid_seq TO isagu;
GRANT ALL ON finpricepolicy_pricepolicyid_seq TO isagu;
GRANT ALL ON finreasoncancellation_reasoncancellationid_seq TO isagu;
GRANT ALL ON seq_receivableinvoicecommunicationid TO isagu;
GRANT ALL ON finrelease_releaseid_seq TO isagu;
GRANT ALL ON finspcmovement_movementid_seq TO isagu;
GRANT ALL ON finspcreason_reasonid_seq TO isagu;
GRANT ALL ON seq_speciesid TO isagu;
GRANT ALL ON seq_studentfinancingid TO isagu;
GRANT ALL ON seq_areatypeid TO isagu;
GRANT ALL ON seq_grouptypeid TO isagu;
GRANT ALL ON seq_itemphysicalresourceid TO isagu;
GRANT ALL ON seq_materialid TO isagu;
GRANT ALL ON seq_materialstate TO isagu;
GRANT ALL ON seq_materialtype TO isagu;
GRANT ALL ON seq_physicalresourceid TO isagu;
GRANT ALL ON seq_tombo TO isagu;
GRANT ALL ON seq_tombostate TO isagu;
GRANT ALL ON seq_answerid TO isagu;
GRANT ALL ON seq_formid TO isagu;
GRANT ALL ON seq_optionid TO isagu;
GRANT ALL ON seq_questionid TO isagu;
GRANT ALL ON seq_questioncategoryid TO isagu;
GRANT ALL ON seq_bankinvoiceinfo TO isagu;
GRANT ALL ON seq_conceptid TO isagu;
GRANT ALL ON seq_courseoccurrenceid TO isagu;
GRANT ALL ON seq_coursevacantid TO isagu;
GRANT ALL ON seq_examid TO isagu;
GRANT ALL ON seq_examoccurrenceid TO isagu;
GRANT ALL ON seq_inscriptionid TO isagu;
GRANT ALL ON seq_inscriptionoptionid TO isagu;
GRANT ALL ON seq_inscriptionstatus TO isagu;
GRANT ALL ON seq_languageid TO isagu;
GRANT ALL ON seq_miolo_group TO isagu;
GRANT ALL ON seq_miolo_user_iduser TO isagu;
GRANT ALL ON seq_othersattleofmatterid TO isagu;
GRANT ALL ON seq_placeid TO isagu;
GRANT ALL ON seq_placeroomid TO isagu;
GRANT ALL ON seq_requerimentid TO isagu;
GRANT ALL ON seq_sattleofmatterid TO isagu;
GRANT ALL ON seq_schedulelearningperiodid TO isagu;
GRANT ALL ON seq_selectiveprocesstypeid TO isagu;
GRANT ALL ON seq_webdailylogid TO isagu;
GRANT ALL ON basphysicalpersonprofessor_esp_id_seq TO isagu;
GRANT ALL ON acdgroup_esp_id_seq TO isagu;
GRANT ALL ON acdenroll_esp_id_seq TO isagu;
GRANT ALL ON acdclass_esp_id_seq TO isagu;
GRANT ALL ON basphysicalpersonstudent_esp_id_seq TO isagu;
