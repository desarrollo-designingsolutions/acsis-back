<?php

namespace App\Helpers;

class Constants
{
    // Agrega más constantes según sea necesario

    public const COMPANY_UUID = '23a0eb68-95b6-49c0-9ad3-0f60627bf220';

    public const ROLE_SUPERADMIN_UUID = '21626ff9-4940-4143-879a-0f75b46eadb7';

    public const COUNTRY_ID = '48'; // Colombia

    public const ITEMS_PER_PAGE = '10'; // PARA LA PAGINACIONES

    public const ERROR_MESSAGE_VALIDATION_BACK = 'Se evidencia algunos errores.';

    public const ERROR_MESSAGE_TRYCATCH = 'Algo Ocurrio, Comunicate Con El Equipo De Desarrollo.';

    public const REDIS_TTL = '315360000'; // 10 años en segundos

    public const DISK_FILES = 'public'; // sistema de archivos

    public const CHUNKSIZE = 10;

    // LLAVES PARA CONSTRUCCION Y VALIDACION DE FACTURAS
    public const KEY_NUMFACT = 'numFactura';

    // REGLAS O CODIGOS PARA LOS SELECECTS DEL FORMULARIO DEL SERVICIO
    public const CODS_SELECT_FORM_SERVICE_MEDICAL_CONSULTATION_CODCONSULTA = [
        '890101',
        '890102',
        '890105',
        '890201',
        '890202',
        '890202',
        '890204',
        '890205',
        '890206',
        '890207',
        '890208',
        '890208',
        '890209',
        '890209',
        '890210',
        '890211',
        '890212',
        '890213',
        '890214',
        '890284',
        '890285',
        '890301',
        '890302',
        '890302',
        '890303',
        '890303',
        '890304',
        '890305',
        '890306',
        '890307',
        '890308',
        '890308',
        '890309',
        '890309',
        '890310',
        '890311',
        '890312',
        '890313',
        '890314',
        '890384',
        '890385',
        '890501',
        '890502',
        '890503',
        '890703',
        '890704',
        '890402',
        '890403',
        '890404',
        '890405',
        '890406',
        '890408',
        '890409',
        '890410',
        '890411',
        '890412',
        '890413',
        '940100',
        '940101',
        '940200',
        '940201',
        '940301',
        '940302',
        '940700',
        '940701',
        '940900',
        '940901',
        '941100',
        '941101',
        '941301',
        '941400',
        '942600',
        '942601',
        '943101',
        '943102',
        '943500',
        '943501',
        '944001',
        '944002',
        '944101',
        '944102',
        '944201',
        '944202',
        '944301',
        '944901',
        '944902',
        '944903',
        '944904',
        '944905',
        '944906',
        '944910',
        '944915',
    ];

    public const CODS_SELECT_FORM_SERVICE_MEDICAL_CONSULTATION_FINALIDADTECNOLOGIASALUD = [
        '12',
        '13',
        '15',
        '16',
        '17',
        '18',
        '19',
        '20',
        '21',
        '22',
        '23',
        '24',
        '25',
        '27',
        '43',
        '44',
    ];

    public const CODS_SELECT_FORM_SERVICE_MEDICAL_CONSULTATION_CAUSAMOTIVOATENCION = [
        '21',
        '22',
        '23',
        '24',
        '25',
        '26',
        '27',
        '28',
        '29',
        '30',
    ];

    public const CODS_SELECT_FORM_SERVICE_TIPODOCUMENTOIDENTIFICACION = [
        'CC',
        'CE',
        'CD',
        'PA',
        'SC',
        'PE',
        'DE',
        'PT',
    ];

    public const CODS_SELECT_FORM_SERVICE_MEDICAL_CONSULTATION_CONCEPTORECAUDO = [
        '02',
        '03',
        '05',
    ];

    public const CODS_SELECT_FORM_SERVICE_PROCEDURE_FINALIDADTECNOLOGIASALUD = [
        '12',
        '13',
        '14',
        '15',
        '16',
        '17',
        '18',
        '19',
        '20',
        '22',
        '23',
        '24',
        '25',
        '26',
        '27',
        '28',
        '29',
        '30',
        '31',
        '32',
        '33',
        '34',
        '35',
        '36',
        '37',
        '38',
        '39',
        '40',
        '41',
        '42',
        '43',
        '44',
    ];

    public const CODS_SELECT_FORM_SERVICE_PROCEDURE_CONCEPTORECAUDO = [
        '01',
        '02',
        '03',
        '05',
    ];

    public const CODS_SELECT_FORM_SERVICE_NEWBORN_TIPODOCUMENTOIDENTIFICACION = [
        'CN',
        'RC',
        'MS',
    ];

    public const CODS_SELECT_FORM_SERVICE_OTHERSERVICE_CONCEPTORECAUDO = [
        '02',
        '03',
        '05',
    ];

    public const CODS_SELECT_FORM_SERVICE_MEDICINE_CONCEPTORECAUDO = [
        '01',
        '03',
        '05',
    ];

    public const CODS_SELECT_FORM_FURIPS1_OWNERDOCUMENTTYPE = [
        'CC',
        'CE',
        'CD',
        'DE',
        'SC',
        'PE',
        'PT',
        'NI',
    ];

    public const CODS_SELECT_FORM_FURIPS1_DRIVERDOCUMENTTYPE = [
        'CC',
        'CE',
        'PA',
        'RC',
        'TI',
        'CD',
        'SC',
        'DE',
        'PE',
        'PT',
    ];

    public const CODS_SELECT_FORM_FURTRAN_CLAIMANIDTYPE = [
        'CC',
        'CE',
        'PA',
        'CD',
        'PE',
        'PT',
        'DE',
    ];

    public const CODS_PDF_FURIPS1_VICTIMDOCUMENTTYPE = [
        'CC',
        'CE',
        'PA',
        'DE',
        'RC',
        'AS',
        'MS',
        'PT',
        'TI',
    ];

    public const CODS_PDF_FURIPS1_OWNERDOCUMENTTYPE = [
        'CC',
        'CE',
        'PA',
        'TI',
        'RC',
        'CD',
    ];

    public const CODS_PDF_FURIPS1_DRIVERDOCUMENTTYPE = [
        'CC',
        'CE',
        'PA',
        'TI',
        'RC',
        'AS',
        'CD',
    ];

    public const CODS_SELECT_FORM_FURIPS1_DOCTORIDTYPE = [
        'CC',
        'CE',
        'PA',
        'PE',
        'PT',
    ];

    public const CODS_TXT_FORM_FURIPS1_UCI = [
        '108A01',
        '109A01',
        '109A02',
        '109A03',
        '110A01',
        '110A02',
        '111A01',
        '125A01',
        'S12101',
        'S12102',
        'S12103',
    ];
}
