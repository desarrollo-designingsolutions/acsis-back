<?php

namespace App\Http\Resources\Furips1;

use App\Http\Resources\Cie10\Cie10SelectInfiniteResource;
use App\Http\Resources\CupsRips\CupsRipsSelectInfiniteResource;
use App\Http\Resources\Departamento\DepartamentoSelectResource;
use App\Http\Resources\IpsCodHabilitacion\IpsCodHabilitacionSelectInfiniteResource;
use App\Http\Resources\Municipio\MunicipioSelectResource;
use App\Http\Resources\TipoIdPisis\TipoIdPisisSelectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Furips1FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'invoice_id' => $this->invoice_id,
            'previousFilingNumber' => $this->previousFilingNumber,
            'rgoResponse' => $this->rgoResponse,
            'consecutiveClaimNumber' => $this->consecutiveClaimNumber,
            'victimResidenceAddress' => $this->victimResidenceAddress,
            'victimPhone' => $this->victimPhone,
            'victimCondition' => $this->victimCondition,
            'eventNature' => $this->eventNature,
            'otherEventDescription' => $this->otherEventDescription,
            'eventOccurrenceAddress' => $this->eventOccurrenceAddress,
            'eventOccurrenceDate' => $this->eventOccurrenceDate,
            'eventOccurrenceTime' => $this->eventOccurrenceTime,
            'eventDepartmentCode_id' => new DepartamentoSelectResource($this->eventDepartmentCode),
            'eventMunicipalityCode_id' => new MunicipioSelectResource($this->eventMunicipalityCode),
            'eventZone' => $this->eventZone,
            'referenceType' => $this->referenceType,
            'referralDate' => $this->referralDate,
            'departureTime' => $this->departureTime,
            'referringHealthProviderCode_id' => new IpsCodHabilitacionSelectInfiniteResource($this->referringHealthProviderCode),
            'referringProfessional' => $this->referringProfessional,
            'referringPersonPosition' => $this->referringPersonPosition,
            'admissionDate' => $this->admissionDate,
            'admissionTime' => $this->admissionTime,
            'receivingHealthProviderCode_id' => new IpsCodHabilitacionSelectInfiniteResource($this->receivingHealthProviderCode),
            'receivingProfessional' => $this->receivingProfessional,
            'interinstitutionalTransferAmbulancePlate' => $this->interinstitutionalTransferAmbulancePlate,
            'vehicleBrand' => $this->vehicleBrand,
            'vehiclePlate' => $this->vehiclePlate,
            'vehicleType' => $this->vehicleType,
            'sirasFilingNumber' => $this->sirasFilingNumber,
            'insurerCapExhaustionCharge' => $this->insurerCapExhaustionCharge,
            'surgicalProcedureComplexity' => $this->surgicalProcedureComplexity,
            'ownerDocumentType_id' => new TipoIdPisisSelectResource($this->ownerDocumentType),
            'ownerDocumentNumber' => $this->ownerDocumentNumber,
            'ownerFirstLastName' => $this->ownerFirstLastName,
            'ownerSecondLastName' => $this->ownerSecondLastName,
            'ownerFirstName' => $this->ownerFirstName,
            'ownerSecondName' => $this->ownerSecondName,
            'ownerResidenceAddress' => $this->ownerResidenceAddress,
            'ownerResidencePhone' => $this->ownerResidencePhone,
            'ownerResidenceDepartmentCode_id' => new DepartamentoSelectResource($this->ownerResidenceDepartmentCode),
            'ownerResidenceMunicipalityCode_id' => new MunicipioSelectResource($this->ownerResidenceMunicipalityCode),
            'driverFirstLastName' => $this->driverFirstLastName,
            'driverSecondLastName' => $this->driverSecondLastName,
            'driverFirstName' => $this->driverFirstName,
            'driverSecondName' => $this->driverSecondName,
            'driverDocumentType_id' => new TipoIdPisisSelectResource($this->driverDocumentType),
            'driverDocumentNumber' => $this->driverDocumentNumber,
            'driverResidenceAddress' => $this->driverResidenceAddress,
            'driverResidenceDepartmentCode_id' => new DepartamentoSelectResource($this->driverResidenceDepartmentCode),
            'driverResidenceMunicipalityCode_id' => new MunicipioSelectResource($this->driverResidenceMunicipalityCode),
            'driverResidencePhone' => $this->driverResidencePhone,
            'primaryTransferAmbulancePlate' => $this->primaryTransferAmbulancePlate,
            'victimTransportFromEventSite' => $this->victimTransportFromEventSite,
            'victimTransportToEnd' => $this->victimTransportToEnd,
            'transportServiceType' => $this->transportServiceType,
            'victimPickupZone' => $this->victimPickupZone,
            'doctorIdType_id' => new TipoIdPisisSelectResource($this->doctorIdType),
            'doctorIdNumber' => $this->doctorIdNumber,
            'doctorFirstLastName' => $this->doctorFirstLastName,
            'doctorSecondLastName' => $this->doctorSecondLastName,
            'doctorFirstName' => $this->doctorFirstName,
            'doctorSecondName' => $this->doctorSecondName,
            'doctorRegistrationNumber' => $this->doctorRegistrationNumber,
            'totalBilledMedicalSurgical' => $this->totalBilledMedicalSurgical,
            'totalClaimedMedicalSurgical' => $this->totalClaimedMedicalSurgical,
            'totalBilledTransport' => $this->totalBilledTransport,
            'totalClaimedTransport' => $this->totalClaimedTransport,
            'enabledServicesConfirmation' => $this->enabledServicesConfirmation,
            'medicalAdmissionDate' => $this->medicalAdmissionDate,
            'medicalAdmissionTime' => $this->medicalAdmissionTime,
            'medicalDischargeDate' => $this->medicalDischargeDate,
            'medicalDischargeTime' => $this->medicalDischargeTime,
            'primaryAdmissionDiagnosisCode_id' => new Cie10SelectInfiniteResource($this->primaryAdmissionDiagnosisCode),
            'associatedAdmissionDiagnosisCode1_id' => new Cie10SelectInfiniteResource($this->associatedAdmissionDiagnosisCode1),
            'associatedAdmissionDiagnosisCode2_id' => new Cie10SelectInfiniteResource($this->associatedAdmissionDiagnosisCode2),
            'primaryDischargeDiagnosisCode_id' => new Cie10SelectInfiniteResource($this->primaryDischargeDiagnosisCode),
            'associatedDischargeDiagnosisCode1_id' => new Cie10SelectInfiniteResource($this->associatedDischargeDiagnosisCode1),
            'associatedDischargeDiagnosisCode2_id' => new Cie10SelectInfiniteResource($this->associatedDischargeDiagnosisCode2),
            'authorityIntervention' => $this->authorityIntervention,
            'policyExcessCharge' => $this->policyExcessCharge,
            'referralRecipientCharge' => $this->referralRecipientCharge,
            
            'mainHospitalizationCupsCode_id' => new CupsRipsSelectInfiniteResource($this->mainHospitalizationCupsCode),
            'mainSurgicalProcedureCupsCode_id' => new CupsRipsSelectInfiniteResource($this->mainSurgicalProcedureCupsCode),
            'secondarySurgicalProcedureCupsCode_id' => new CupsRipsSelectInfiniteResource($this->secondarySurgicalProcedureCupsCode),
            'uciServiceProvided' => $this->uciServiceProvided,
            'claimedUciDays' => $this->claimedUciDays,

        ];
    }
}
