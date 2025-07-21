<?php

use App\Helpers\Constants;
use Saloon\XmlWrangler\XmlReader;

function validateDataFilesXml($archivo, $data)
{

    $errorMessages = [];
    $arrayExito = [];

    $arrayExito[] = validationFileXml($archivo, $data, $errorMessages);
    if ($arrayExito[0]['result'] == false) {
        return [
            'errorMessages' => $errorMessages,
            'totalErrorMessages' => count($errorMessages),
        ];
    }
    $dataXml = $arrayExito[0]['xmlData'];

    $attachedDocument = $dataXml['AttachedDocument'];
    $validation = $attachedDocument['cac:ParentDocumentLineReference']['cac:DocumentReference']['cac:ResultOfVerification'];

    $arrayExito[] = validationResultCode($dataXml, $validation['cbc:ValidationResultCode'], $errorMessages);

    $numFac = $attachedDocument['cbc:ID'];
    $arrayExito[] = RVC004($data['jsonContents'], $numFac, $errorMessages);

    $nitSenderVendor = $attachedDocument['cac:SenderParty']['cac:PartyTaxScheme']['cbc:CompanyID'];     
    $arrayExito[] = validationNitSenderVendor($data, $nitSenderVendor, $errorMessages);



    //Informacion de la factura
    $invoice = [
        "invoice_date" => $attachedDocument['cbc:IssueDate']
    ];

    $entity = [
        "nit" => $attachedDocument['cac:ReceiverParty']['cac:PartyTaxScheme']['cbc:CompanyID'],
        "corporate_name" => $attachedDocument['cac:ReceiverParty']['cac:PartyTaxScheme']['cbc:RegistrationName'],
    ];


    return [
        'errorMessages' => $errorMessages,
        'totalErrorMessages' => count($errorMessages),
        'info' => [
            'invoice' => $invoice,
            'entity' => $entity,
        ],
    ];
}

function validationResultCode($data, $value2, &$errorMessages)
{

    $validation = true;

    if ($value2 != '02') {
        $errorMessages[] = [
            'validacion' => 'validationResultCode',
            'validacion_type_Y' => 'R',
            'num_invoice' => $data['numInvoice'],
            'file' => $data['file_name'] ?? null,
            'row' => $data['row'] ?? null,
            'column' => 'ValidationResultCode',
            'data' => $value2,
            'error' => 'el ValidationResultCode debe ser el numero 2.',
        ];

        $validation = false;
    }

    return [
        'validacion_type_Y' => 'R',
        'result' => $validation,
    ];
}

function validationFileXml($archiveXml, $data, &$errorMessages)
{
    $xmlData = [];
    $validation = true;

    try {
        $contenidoXml = file_get_contents($archiveXml);
        $reader = XmlReader::fromString($contenidoXml);
        $xmlData = $reader->values(); // Array of values.
    } catch (\Throwable $th) {
        $errorMessages[] = [
            'validacion' => 'validationFileXML',
            'validacion_type_Y' => 'R',
            'num_invoice' => $data['numInvoice'],
            'file' => $data['file_name'] ?? null,
            'row' => null,
            'column' => 'validationFileXML',
            'data' => null,
            'error' => 'No se pudo leer el archivo XML.',
        ];

        $validation = false;
    }

    return [
        'valdiacion_type_Y' => 'R',
        'result' => $validation,
        'xmlData' => $xmlData,
    ];
}

function RVC004($dataTxt, $value2, &$errorMessages)
{
    $validation = true;

    if ($dataTxt[Constants::KEY_NUMFACT] != $value2) {
        $errorMessages[] = [
            'validacion' => 'RVC004',
            'validacion_type_Y' => 'R',
            'num_invoice' => $dataTxt[Constants::KEY_NUMFACT] ?? $dataTxt['numFEVPagoModerador'] ?? null,
            'file' => $dataTxt['file_name'] ?? null,
            'row' => $dataTxt['row'] ?? null,
            'column' => Constants::KEY_NUMFACT,
            'data' => $value2,
            'error' => 'El número de la factura informado en RIPS no coincide con el informado en la factura electrónica de venta.',
        ];

        $validation = false;
    }

    return [
        'validacion_type_Y' => 'R',
        'result' => $validation,
    ];
}



function validationNitSenderVendor($dataTxt, $value2, &$errorMessages)
{
    $validation = true;

    if ($dataTxt['service_vendor_nit'] != $value2) {
        $errorMessages[] = [
            'validacion' => 'validationNitSenderVendor',
            'validacion_type_Y' => 'R',
            'num_invoice' => $dataTxt['service_vendor_nit'] ?? $dataTxt['numFEVPagoModerador'] ?? null,
            'file' => $dataTxt['file_name'] ?? null,
            'row' => $dataTxt['row'] ?? null,
            'column' => 'service_vendor_nit',
            'data' => $value2,
            'error' => 'El nit del prestador no coincide.',
        ];

        $validation = false;
    }

    return [
        'validacion_type_Y' => 'R',
        'result' => $validation,
    ];
}
