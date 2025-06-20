<?php

namespace App\Http\Resources\Furtran;

use App\Http\Resources\Departamento\DepartamentoSelectResource;
use App\Http\Resources\IpsCodHabilitacion\IpsCodHabilitacionSelectInfiniteResource;
use App\Http\Resources\Municipio\MunicipioSelectResource;
use App\Http\Resources\TipoIdPisis\TipoIdPisisSelectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FurtranFormResource extends JsonResource
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

            'previousRecordNumber' => $this->previousRecordNumber,
            'rgResponse' => $this->rgResponse,
            'firstLastNameClaimant' => $this->firstLastNameClaimant,
            'secondLastNameClaimant' => $this->secondLastNameClaimant,
            'firstNameClaimant' => $this->firstNameClaimant,
            'secondNameClaimant' => $this->secondNameClaimant,
            'claimantIdType_id' => new TipoIdPisisSelectResource($this->claimantIdType),
            'claimantIdNumber' => $this->claimantIdNumber,
            'vehicleServiceType' => $this->vehicleServiceType,
            'vehiclePlate' => $this->vehiclePlate,
            'claimantAddress' => $this->claimantAddress,
            'claimantPhone' => $this->claimantPhone,
            'claimantDepartmentCode_id' => new DepartamentoSelectResource($this->claimantDepartmentCode),
            'claimantMunicipalityCode_id' => new MunicipioSelectResource($this->claimantMunicipalityCode),
            'victimGender' => $this->victimGender,
            'eventType' => $this->eventType,
            'pickupAddress' => $this->pickupAddress,
            'pickupDepartmentCode_id' => new DepartamentoSelectResource($this->pickupDepartmentCode),
            'pickupMunicipalityCode_id' => new MunicipioSelectResource($this->pickupMunicipalityCode),
            'pickupZone' => $this->pickupZone,
            'transferDate' => $this->transferDate,
            'transferTime' => $this->transferTime,
            'transferPickupDepartmentCode_id' => new DepartamentoSelectResource($this->transferPickupDepartmentCode),
            'transferPickupMunicipalityCode_id' => new MunicipioSelectResource($this->transferPickupMunicipalityCode),
            'victimCondition' => $this->victimCondition,
            'involvedVehicleType' => $this->involvedVehicleType,
            'involvedVehiclePlate' => $this->involvedVehiclePlate,
            'insurerCode' => $this->insurerCode,
            'sirasRecordNumber' => $this->sirasRecordNumber,
            'billedValue' => $this->billedValue,
            'claimedValue' => $this->claimedValue,
            'serviceEnabledIndication' => $this->serviceEnabledIndication,
            'ipsName' => $this->ipsName,
            'ipsNit' => $this->ipsNit,
            'ipsAddress' => $this->ipsAddress,
            'ipsPhone' => $this->ipsPhone,
            'ipsReceptionHabilitationCode_id' => new IpsCodHabilitacionSelectInfiniteResource($this->ipsReceptionHabilitationCode),
        ];
    }
}
